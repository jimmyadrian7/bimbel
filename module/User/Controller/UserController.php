<?php
namespace Bimbel\User\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\User\Model\User;
use \Bimbel\Master\Model\Menu;
use \Bimbel\Guru\Model\Guru;
use \Bimbel\Siswa\Model\Siswa;
use Illuminate\Database\Capsule\Manager as DB;

class UserController extends Controller
{
    public function authenticateUser($request, $args)
    {
        $result = false;

        try 
        {
            $data = $request->getParsedBody();
            if (count($data) === 0)
            {
                throw new \Error("Data is empty");
            }
            if (!array_key_exists('username', $data))
            {
                throw new \Error("username is empty");
            }
            if (!array_key_exists('password', $data))
            {
                throw new \Error("password is empty");
            }

            $user = new user();
            $user = $user->where('username', $data['username'])->where('status', 'a')->first();

            if (!$user)
            {
                throw new \Error("Username or password is incorrect");
            }

            $verify = password_verify($data['password'], $user->password);

            if (!$verify)
            {
                throw new \Error("Username or password is incorrect");
            }

            $user->offsetUnset('password');
            $user->offsetUnset('unenpass');
            $user->offsetUnset('orang_id');
            $user->offsetUnset('role');

            $result = true;
            $session = $this->container->get('Session');
            $session->set('user', $user);
        }
        catch(\Error $e) 
        {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }

    public function getCurrentUser()
    {
        $session = $this->container->get('Session');
        $user = $session->get('user');

        if (!$user)
        {
            throw new \Error("You are currently not logged in", 501);
        }

        $user = $user->refresh();
        $user->offsetUnset('password');
        $user->offsetUnset('unenpass');

        $menu = new Menu();
        if ($user->super_user)
        {
            $query = "
                SELECT 
                    menu.id, menu.kode, menu.nama, menu.parent,
                    1 AS 'create', 1 AS 'update', 1 AS 'delete'
                FROM menu
            ";
        }
        else
        {
            $query = "
                SELECT 
                    menu.id, menu.kode, menu.nama, menu.parent,
                    role_menu.create, role_menu.update, role_menu.delete
                FROM role_menu
                LEFT JOIN menu ON menu.id = role_menu.menu_id
                WHERE role_id = (:role_id)
            ";
        }

        $role_ids = $user->role->pluck('id')->toArray();
        $menu = DB::select(DB::raw($query), ['role_id' => join(", ", $role_ids)]);

        $guru = new Guru();
        $guru = $guru->with('orang')->where('orang_id', $user->orang_id)->first();

        $siswa = new Siswa();
        $siswa = $siswa->with('orang')->where('orang_id', $user->orang_id)->first();

        $user->{'menu'} = $menu;
        $user->{'guru'} = $guru;
        $user->{'siswa'} = $siswa;

        return $user;
    }

    public function logoutCurrentUser()
    {
        $session = $this->container->get('Session');
        $user = $session->get('user');

        if (!$user)
        {
            throw new \Error("You are currently not logged in");
        }

        $session->killAll();
        return true;
    }
}
