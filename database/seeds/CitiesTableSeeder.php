<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\State;

class CitiesTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('cities')->delete();

        $state = State::where('name','Gujarat')->first();
        $stateId = $state->id;
        //print_r($stateId);exit;

        $state1 = State::where('name','Maharashtra')->first();
        $stateId1 = $state1->id;

        $state2 = State::where('name','Karnataka')->first();
        $stateId2 = $state2->id;

        $cities = array(
            array('staid'=>$stateId,'name' => 'Ahmedabad'),
            array('staid'=>$stateId,'name' => 'Baroda'),
            array('staid'=>$stateId,'name' => 'Surat'),
            array('staid'=>$stateId1,'name' => 'Mumbai'),
            array('staid'=>$stateId1,'name' => 'Pune'),
            array('staid'=>$stateId1,'name' => 'Thane'),
            array('staid'=>$stateId2,'name' => 'Bengaluru'),
            array('staid'=>$stateId2,'name' => 'Mysore'),
            array('staid'=>$stateId2,'name' => 'Mangalore'),
        );

        DB::table('cities')->insert($cities);
    }

}

