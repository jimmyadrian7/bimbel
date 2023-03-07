<?php
namespace Bimbel\User\Controller;

use \Bimbel\Master\Controller\Controller;

use \Bimbel\Master\Model\Menu;
use \Bimbel\Master\Model\Session;
use \Bimbel\User\Model\User;
use \Bimbel\Guru\Model\Guru;
use \Bimbel\Siswa\Model\Siswa;
use Illuminate\Database\Capsule\Manager as DB;

class UserController extends Controller
{
    public function authenticateUser($request, $args, &$response)
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

            $result = true;
            $session = $this->container->get('Session');
            $session->set('user_id', $user->id);
        }
        catch(\Error $e) 
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function getCurrentUser($requrest, $args, &$response)
    {
        $result = [];

        try
        {
            $session = new Session();
            $user = $session->getCurrentUser();

            $menu = new Menu();
            if ($session->isSuperUser())
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
                    WHERE role_id IN (%s)
                ";
            }

            $role_ids = $user->role->pluck('id')->toArray();
            $query = sprintf($query, join(", ", $role_ids));
            $menu = DB::select(DB::raw($query));

            $guru = new Guru();
            $guru = $guru
                ->with('orang', 'gaji', 'tunjangan_guru', 'pp', 'kursus')
                ->where('orang_id', $user->orang_id)
                ->first();

            $siswa = new Siswa();
            $siswa = $siswa->with('orang', 'jadwal')->where('orang_id', $user->orang_id)->first();

            $user->{'menu'} = $menu;
            $user->{'guru'} = $guru;
            $user->{'siswa'} = $siswa;

            $result = $user;
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response, 501);
        }

        return $result;
    }

    public function logoutCurrentUser(&$response)
    {
        try {
            $session = $this->container->get('Session');
            $user = $session->get('user_id');

            if (!$user)
            {
                throw new \Error("You are currently not logged in");
            }

            $session->killAll();
        }
        catch(\Error $e) 
        {
            $result = $this->container->get('error')($e, $response);
        }
        
        return true;
    }
}
