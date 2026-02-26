<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Owner;

class OutletTypeTableSeeder extends Seeder
{

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('outlet_types')->delete();
        $createed_by=Owner::where('user_name','sanjay')->first();

        $Outlet_types = array(
            array('owner_id' => $createed_by->id,'type'=>'Takeaway'),
            array('owner_id' => $createed_by->id,'type'=>'Buffet'),
            array('owner_id' => $createed_by->id,'type'=>'Casual Dining'),
            array('owner_id' => $createed_by->id,'type'=>'Café or Bistro'),
            array('owner_id' => $createed_by->id,'type'=>'Quick Bites'),
            array('owner_id' => $createed_by->id,'type'=>'Fast Food'),
            array('owner_id' => $createed_by->id,'type'=>'Fine Dining'),
            array('owner_id' =>$createed_by->id,'type'=>'Family Style'),
            array('owner_id' => $createed_by->id,'type'=>'Dining room'),
            array('owner_id' => $createed_by->id,'type'=>'Dhaba'),
            array('owner_id' => $createed_by->id,'type'=>'Food court'),
            array('owner_id' => $createed_by->id,'type'=>'Ice cream'),
            array('owner_id' => $createed_by->id,'type'=>'Seafood Outlet'),
            array('owner_id' => $createed_by->id,'type'=>'Theme Outlet'),
            array('owner_id' => $createed_by->id,'type'=>'Food Truck'),
        );

        DB::table('outlet_types')->insert($Outlet_types);
    }

}
