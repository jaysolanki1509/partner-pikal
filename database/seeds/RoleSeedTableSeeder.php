<?php

use Illuminate\Database\Seeder;
use Bican\Roles\Models\Role;
use App\Owner;

// composer require laracasts/testdummy

class RoleSeedTableSeeder extends Seeder
{

    public function run()
    {
      //  DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Role::create([
            //'id'            => 1,
            'name'          => 'SuperAdmin',
            'slug'          => 'superadmin',
            'description'   => 'Use this account with extreme caution. When using this account it is possible to cause irreversible damage to the system.'
        ]);

        Role::create([
            //'id'            => 2,
            'name'          => 'Admin',
            'slug'          => 'admin',
            'description'   => 'Full access to create, edit, and update outlets, and orders.'
        ]);

        Role::create([
            //'id'            => 3,
            'name'          => 'Outlet Owner',
            'slug'          => 'outlet owner',
            'description'   => 'Full access to particular outlet  and orders.'
        ]);


    }


}
