<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
//use Laracasts\TestDummy\Factory as TestDummy;
use App\Owner;

class CuisineTypeTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('cuisine_types')->delete();
        $createed_by=Owner::where('user_name','sanjay')->first();
        $cuisine_types = array(
            array('owner_id' => $createed_by->id,'type'=>'Gujarati'),
            array('owner_id' => $createed_by->id,'type'=>'South Indian'),
            array('owner_id' => $createed_by->id,'type'=>'Punjabi'),
            array('owner_id' => $createed_by->id,'type'=>'Mughlai'),
            array('owner_id' => $createed_by->id,'type'=>'Mexican'),
            array('owner_id' => $createed_by->id,'type'=>'Italian'),
            array('owner_id' => $createed_by->id,'type'=>'Chinese'),
            array('owner_id' => $createed_by->id,'type'=>'North Indian'),
            array('owner_id' => $createed_by->id,'type'=>'Continental'),
            array('owner_id' => $createed_by->id,'type'=>'Fast Food'),
            array('owner_id' => $createed_by->id,'type'=>'Cafe'),
            array('owner_id' => $createed_by->id,'type'=>'Ice Creams'),
            array('owner_id' => $createed_by->id,'type'=>'Pan-Asian'),
            array('owner_id' => $createed_by->id,'type'=>'Mediterranean'),
            array('owner_id' => $createed_by->id,'type'=>'Desserts'),
            array('owner_id' => $createed_by->id,'type'=>'Cakes-Bakery'),
            array('owner_id' => $createed_by->id,'type'=>'Steakhouse'),
            array('owner_id' => $createed_by->id,'type'=>'Healthy Food'),
            array('owner_id' => $createed_by->id,'type'=>'American'),
            array('owner_id' => $createed_by->id,'type'=>'Pizza'),
            array('owner_id' => $createed_by->id,'type'=>'Sandwiches'),
            array('owner_id' => $createed_by->id,'type'=>'Street Food'),
            array('owner_id' => $createed_by->id,'type'=>'Thai'),
            array('owner_id' => $createed_by->id,'type'=>'Hyderabadi'),
            array('owner_id' => $createed_by->id,'type'=>'Sea Food'),
            array('owner_id' => $createed_by->id,'type'=>'Non-Veg'),
            array('owner_id' => $createed_by->id,'type'=>'Biryani'),

        );

        DB::table('cuisine_types')->insert($cuisine_types);
    }

}

