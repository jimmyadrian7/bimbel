<?php
namespace Bimbel\FixData\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Utils
{
    public static function addMenuReport($menuName, $label, $parent = 'laporan', $roleIds = [1, 4])
    {
        $menu = new \Bimbel\Master\Model\Menu();
        $menu = $menu->where('kode', $menuName)->first();

        if (empty($menu))
        {
            $menu = new \Bimbel\Master\Model\Menu();
            $menu = $menu->create(['kode' => $menuName, 'nama' => $label, 'parent' => $parent]);
        }

        foreach ($roleIds as $value) {
            $role_menu = new \Bimbel\User\Model\RoleMenu();
            $role_menu = $role_menu->where('role_id', $value)->where('menu_id', $menu->id)->first();
            if (empty($role_menu))
            {
                $role_menu = new \Bimbel\User\Model\RoleMenu();
                $role_menu->create([
                    'role_id' => $value,
                    'menu_id' => $menu->id,
                    'create' => null,
                    'update' => null,
                    'delete' => null
                ]);
            }
        }
    }

    public static function addField($table, $field, $type, $after, $default=false, $enumOption = [])
    {
        $isColExist = DB::getSchemaBuilder()->hasColumn($table, $field);
        if (!$isColExist)
        {
            DB::getSchemaBuilder()->table($table, function($table) use ($field, $type, $after, $default, $enumOption) {

                $fieldArgs = [$field];

                if ($type == 'enum')
                {
                    $fieldArgs[] = $enumOption;
                }

                if ($default)
                {
                    $table->{$type}(...$fieldArgs)->default($default)->after($after);
                }
                else
                {
                    $table->{$type}(...$fieldArgs)->nullable()->after($after);
                }
            });
        }
    }
}