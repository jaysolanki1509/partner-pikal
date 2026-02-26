<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\State;

class StatesTableSeeder extends Seeder
{

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('states')->delete();

            // staid in Outlets table staid = outlet_id dynamic

        $states =
            array(
            array('name' => 'Gujarat'),
            array('name' => 'Maharashtra'),
            array('name' => 'Karnataka'),

        );
        DB::table('states')->insert($states);
    }
}
