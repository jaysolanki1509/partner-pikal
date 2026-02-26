<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UsersTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('owners')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $users = array(

            array('user_name' => 'govind','email'=>'govind@savitriya.com','password'=>Hash::make('govind123'),'remember_token'=>str_random(64)),
            array('user_name' => 'sanjay','email'=>'sanjay@savitriya.com','password'=>Hash::make('sanjay123'),'remember_token'=>str_random(64)),

        );

        DB::table('owners')->insert($users);


    }

}