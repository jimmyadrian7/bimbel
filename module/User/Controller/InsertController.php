<?php
namespace Bimbel\User\Controller;

use \Bimbel\Master\Controller\Controller;
use \Bimbel\User\Model\User;
use \Bimbel\User\Model\Role;
use Illuminate\Database\Capsule\Manager as DB;

class InsertController extends Controller
{
    public function insertRole($request, $args, &$response)
    {
        $result = [];

        try 
        {
            DB::beginTransaction();
            
            $data = $request->getParsedBody();
            if (count($data) === 0)
            {
                throw new \Error("Data is empty");
            }
            if (!array_key_exists('user_id', $data))
            {
                throw new \Error("User not found");
            }
            if (!array_key_exists('role_ids', $data) && count($data['role_ids']) === 0)
            {
                throw new \Error("Data is empty");
            }

            $user = new User();
            $user = $user->find($data['user_id']);

            if (!$user)
            {
                throw new \Error("User not found");
            }
            
            $hasRole = $user->role()->whereIn('role.id', $data['role_ids'])->exists();
            if ($hasRole)
            {
                throw new \Error("Role already exists");
            }

            $user->role()->attach($data['role_ids']);
            $result = ["id" => $user->id];

            DB::commit();
        }
        catch(\Error $e) 
        {
            DB::rollBack();
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }

    public function insertMenu($request, $args, &$response)
    {
        $result = [];

        try 
        {
            DB::beginTransaction();
            
            $data = $request->getParsedBody();
            if (count($data) === 0)
            {
                throw new \Error("Data is empty");
            }
            if (!array_key_exists('role_id', $data))
            {
                throw new \Error("Role not found");
            }
            if (!array_key_exists('menu_ids', $data) && count($data['menu_ids']) === 0)
            {
                throw new \Error("Data is empty");
            }

            $role = new Role();
            $role = $role->find($data['role_id']);

            if (!$role)
            {
                throw new \Error("Role not found");
            }
            
            $hasMenu = $role->menu()->whereIn('menu.id', $data['menu_ids'])->exists();
            if ($hasMenu)
            {
                throw new \Error("Menu already exists");
            }

            $role->menu()->attach($data['menu_ids']);
            $result = ["id" => $role->id];

            DB::commit();
        }
        catch(\Error $e) 
        {
            DB::rollBack();
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }
}
