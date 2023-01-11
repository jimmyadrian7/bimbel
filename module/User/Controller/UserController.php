<?php
namespace Bimbel\User\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\User\Model\User;
use \Bimbel\Master\Model\Menu;
use \Bimbel\Guru\Model\Guru;
use \Bimbel\Siswa\Model\Siswa;

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

        $menu = new Menu();
        if ($user->super_user)
        {
            $menu = $menu->get();
        }
        else
        {
            $menu_ids = [];
            $current_user = new User();
            $current_user = $current_user->find($user->id);

            foreach($user->role as $role)
            {
                $menu_ids = array_merge($menu_ids, $role->menu->pluck('id')->toArray());
            }

            $menu = $menu->whereIn('id', $menu_ids)->get();
        }

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
