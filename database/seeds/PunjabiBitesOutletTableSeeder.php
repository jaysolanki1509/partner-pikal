<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Outlet;
use App\Owner;
use App\OutletType;
use App\CuisineType;

class PunjabiBitesOutletTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        //Insert Outlet_types
        $Outlet=Outlet::where('name','Punjabi Bites')->first();
        $OutletId=$Outlet->id;


        DB::table('outlet_types_mapper')->where('outlet_id',$OutletId)->delete();
        $Outlet_Outlet_types=OutletType::where('type','Quick Bites')->first();
        $Outlet_Outlet_typesId = $Outlet_Outlet_types->id;

        $Outlet_Outlet_types = array(
            array('outlet_id'=>$OutletId,'Outlet_type_id'=>$Outlet_Outlet_typesId),
        );
        DB::table('outlet_types_mapper')->insert($Outlet_Outlet_types);




        //Insert Cuisine_types
        DB::table('outlet_cuisine_types')->where('outlet_id',$OutletId)->delete();
        $Outlet_cuisine_types=CuisineType::where('type','Fast Food')->first();
        $Outlet_cuisine_typesId=$Outlet_cuisine_types->id;
        $Outlet_cuisine_types=CuisineType::where('type','Street Food')->first();
        $Outlet_cuisine_typesId1=$Outlet_cuisine_types->id;
        $Outlet_cuisine_types=CuisineType::where('type','Pizza')->first();
        $Outlet_cuisine_typesId2=$Outlet_cuisine_types->id;
        $Outlet_cuisine_types=CuisineType::where('type','Sandwiches')->first();
        $Outlet_cuisine_typesId3=$Outlet_cuisine_types->id;
        $Outlet_cuisine_types=CuisineType::where('type','Punjabi')->first();
        $Outlet_cuisine_typesId4=$Outlet_cuisine_types->id;


        $Outlet_cuisine_types = array(
            array('outlet_id'=>$OutletId,'cuisine_type_id'=>$Outlet_cuisine_typesId),
            array('outlet_id'=>$OutletId,'cuisine_type_id'=>$Outlet_cuisine_typesId1),
            array('outlet_id'=>$OutletId,'cuisine_type_id'=>$Outlet_cuisine_typesId2),
            array('outlet_id'=>$OutletId,'cuisine_type_id'=>$Outlet_cuisine_typesId3),
            array('outlet_id'=>$OutletId,'cuisine_type_id'=>$Outlet_cuisine_typesId4),
        );

        DB::table('outlet_cuisine_types')->insert($Outlet_cuisine_types);

        //Insert OutletImages
        DB::table('outlet_images')->where('outlet_id',$OutletId)->delete();
        $OutletImages = array(
            array('outlet_id' => $OutletId,'image_name'=>'punjabi-01.jpg'),
            array('outlet_id' => $OutletId,'image_name'=>'punjabi-02.jpg'),
            array('outlet_id' => $OutletId,'image_name'=>'punjabi-03.jpg'),
        );
        DB::table('outlet_images')->insert($OutletImages);



        //Insert Outlet_Latlong
        DB::table('outlet_latlong')->where('outlet_id',$OutletId)->delete();
        $Outlet_latlong = array(
            array('outlet_id'=>$OutletId,'latitude'=>23.259933,'longitude'=>77.412615),
        );
        DB::table('outlet_latlong')->insert($Outlet_latlong);


        //Insert Outlet Status
        DB::table('status')->where('outlet_id',$OutletId)->delete();
        $user=Owner::where('user_name','parag')->first();
        $userID=$user->id;

        $status = array(
            array('owner_id'=>$userID,'outlet_id'=>$OutletId,'order'=>'1','status'=>'received'),
            array('owner_id'=>$userID,'outlet_id'=>$OutletId,'order'=>'2','status'=>'preparing'),
            array('owner_id'=>$userID,'outlet_id'=>$OutletId,'order'=>'3','status'=>'prepared'),
            array('owner_id'=>$userID,'outlet_id'=>$OutletId,'order'=>'4','status'=>'delivered'),
        );


        DB::table('status')->insert($status);


        //Insert Outlet Timing
        DB::table('timeslot')->where('outlet_id',$OutletId)->delete();
        $timing = array(
            array('outlet_id'=>$OutletId,'from_time'=>'11:30 AM','to_time'=>'10:00 PM'),
        );
        DB::table('timeslot')->insert($timing);

        //Insert MenuTitles
        //Insert Menus
    }
}