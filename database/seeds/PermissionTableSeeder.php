<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class PermissionTableSeeder extends Seeder
{

    public function run()
    {

        $permission = new Permission();
        $permission->name = 'Manage Outlets';
        $permission->slug = 'manage.outlets';
        $permission->description = '';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Manage Users';
        $permission->slug = 'manage.users';
        $permission->description = '';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Manage Menus';
        $permission->slug = 'manage.menus';
        $permission->description = '';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Manage Inventory';
        $permission->slug = 'manage.inventory';
        $permission->description = '';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Manage Orders';
        $permission->slug = 'manage.orders';
        $permission->description = '';
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Manage Recipe';
        $permission->slug = 'manage.recipe';
        $permission->description = '';
        $permission->save();


    }

}
