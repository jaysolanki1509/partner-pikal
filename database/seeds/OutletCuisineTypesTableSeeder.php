<?php

use Illuminate\Database\Seeder;
use App\CuisineType;
use App\Outlet;

// composer require laracasts/testdummy
//use Laracasts\TestDummy\Factory as TestDummy;

class OutletCuisineTypeTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('Outlet_cuisine_types')->delete();

//        $Outlet=Outlet::where('Outlet_name','WOW')->first();
//        $OutletId=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId = $Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Chinese')->first();
//        $Outlet_cuisine_typesId1 = $Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Mughlai')->first();
//        $Outlet_cuisine_typesId2 = $Outlet_cuisine_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','jungle bhookh')->first();
//        $OutletId1=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId3=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Chinese')->first();
//        $Outlet_cuisine_typesId4=$Outlet_cuisine_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Shree Mehfil')->first();
//        $OutletId2=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId5=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Chinese')->first();
//        $Outlet_cuisine_typesId6=$Outlet_cuisine_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Pleasure Trove')->first();
//        $OutletId3=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Mughlai')->first();
//        $Outlet_cuisine_typesId7=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Chinese')->first();
//        $Outlet_cuisine_typesId8=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Continental')->first();
//        $Outlet_cuisine_typesId9=$Outlet_cuisine_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Shambhus Coffee Bar')->first();
//        $OutletId4=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Fast Food')->first();
//        $Outlet_cuisine_typesId10=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Cafe')->first();
//        $Outlet_cuisine_typesId11=$Outlet_cuisine_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Cafe Coffee Day')->first();
//        $OutletId5=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Cafe')->first();
//        $Outlet_cuisine_typesId12=$Outlet_cuisine_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Saffron Outlet')->first();
//        $OutletId6=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId13=$Outlet_cuisine_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Sankalp Outlet')->first();
//        $OutletId7=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','South Indian')->first();
//        $Outlet_cuisine_typesId14=$Outlet_cuisine_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Havmor Outlet')->first();
//        $OutletId8=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId15=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Continental')->first();
//        $Outlet_cuisine_typesId16=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Fast Food')->first();
//        $Outlet_cuisine_typesId17=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Ice Creams')->first();
//        $Outlet_cuisine_typesId18=$Outlet_cuisine_types->id;
//
//
//
//        $Outlet=Outlet::where('Outlet_name','Not Just Grill')->first();
//        $OutletId9=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId19=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Pan-Asian')->first();
//        $Outlet_cuisine_typesId20=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Mediterranean')->first();
//        $Outlet_cuisine_typesId21=$Outlet_cuisine_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Dinner Bell2')->first();
//        $OutletId10=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId22=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Chinese')->first();
//        $Outlet_cuisine_typesId23=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Continental')->first();
//        $Outlet_cuisine_typesId24=$Outlet_cuisine_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Cafe Upper Crust')->first();
//        $OutletId11=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId25=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Continental')->first();
//        $Outlet_cuisine_typesId26=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Steakhouse')->first();
//        $Outlet_cuisine_typesId27=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Desserts')->first();
//        $Outlet_cuisine_typesId28=$Outlet_cuisine_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Cellad Eatery')->first();
//        $OutletId12=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Healthy Food')->first();
//        $Outlet_cuisine_typesId29=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','American')->first();
//        $Outlet_cuisine_typesId30=$Outlet_cuisine_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Toritos')->first();
//        $OutletId13=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Italian')->first();
//        $Outlet_cuisine_typesId31=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Mexican')->first();
//        $Outlet_cuisine_typesId32=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Continental')->first();
//        $Outlet_cuisine_typesId33=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Cafe')->first();
//        $Outlet_cuisine_typesId34=$Outlet_cuisine_types->id;
//
//
//
//        $Outlet=Outlet::where('Outlet_name','Tomatos')->first();
//        $OutletId14=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Mexican')->first();
//        $Outlet_cuisine_typesId35=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId36=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Pan-Asian')->first();
//        $Outlet_cuisine_typesId37=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Continental')->first();
//        $Outlet_cuisine_typesId38=$Outlet_cuisine_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Page One')->first();
//        $OutletId15=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','North Indian')->first();
//        $Outlet_cuisine_typesId39=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Italian')->first();
//        $Outlet_cuisine_typesId40=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Chinese')->first();
//        $Outlet_cuisine_typesId41=$Outlet_cuisine_types->id;

//        $Outlet=Outlet::where('Outlet_name','Dadaji')->first();
//        $OutletId16=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Fast Food')->first();
//        $Outlet_cuisine_typesId42=$Outlet_cuisine_types->id;


//        $Outlet=Outlet::where('Outlet_name','Purohit Sandwich')->first();
//        $OutletId17=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Fast Food')->first();
//        $Outlet_cuisine_typesId43=$Outlet_cuisine_types->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Street Food')->first();
//        $Outlet_cuisine_typesId44=$Outlet_cuisine_types->id;


//        $Outlet=Outlet::where('Outlet_name','Grill INN')->first();
//        $OutletId18=$Outlet->id;
//        $Outlet_cuisine_types=CuisineType::where('type','Fast Food')->first();
//        $Outlet_cuisine_typesId45=$Outlet_cuisine_types->id;



        $Outlet_cuisine_types = array(
//            array('outlet_id'=>$OutletId,'cuisine_type_id'=>$Outlet_cuisine_typesId),
//            array('outlet_id'=>$OutletId,'cuisine_type_id'=>$Outlet_cuisine_typesId1),
//            array('outlet_id'=>$OutletId,'cuisine_type_id'=>$Outlet_cuisine_typesId2),
//            array('outlet_id'=>$OutletId1,'cuisine_type_id'=>$Outlet_cuisine_typesId3),
//            array('outlet_id'=>$OutletId1,'cuisine_type_id'=>$Outlet_cuisine_typesId4),
//            array('outlet_id'=>$OutletId2,'cuisine_type_id'=>$Outlet_cuisine_typesId5),
//            array('outlet_id'=>$OutletId2,'cuisine_type_id'=>$Outlet_cuisine_typesId6),
//            array('outlet_id'=>$OutletId3,'cuisine_type_id'=>$Outlet_cuisine_typesId7),
//            array('outlet_id'=>$OutletId3,'cuisine_type_id'=>$Outlet_cuisine_typesId8),
//            array('outlet_id'=>$OutletId3,'cuisine_type_id'=>$Outlet_cuisine_typesId9),
//            array('outlet_id'=>$OutletId4,'cuisine_type_id'=>$Outlet_cuisine_typesId10),
//            array('outlet_id'=>$OutletId4,'cuisine_type_id'=>$Outlet_cuisine_typesId11),
//            array('outlet_id'=>$OutletId5,'cuisine_type_id'=>$Outlet_cuisine_typesId12),
//            array('outlet_id'=>$OutletId6,'cuisine_type_id'=>$Outlet_cuisine_typesId13),
//            array('outlet_id'=>$OutletId7,'cuisine_type_id'=>$Outlet_cuisine_typesId14),
//            array('outlet_id'=>$OutletId8,'cuisine_type_id'=>$Outlet_cuisine_typesId15),
//            array('outlet_id'=>$OutletId8,'cuisine_type_id'=>$Outlet_cuisine_typesId16),
//            array('outlet_id'=>$OutletId8,'cuisine_type_id'=>$Outlet_cuisine_typesId17),
//            array('outlet_id'=>$OutletId8,'cuisine_type_id'=>$Outlet_cuisine_typesId18),
//            array('outlet_id'=>$OutletId9,'cuisine_type_id'=>$Outlet_cuisine_typesId19),
//            array('outlet_id'=>$OutletId9,'cuisine_type_id'=>$Outlet_cuisine_typesId20),
//            array('outlet_id'=>$OutletId9,'cuisine_type_id'=>$Outlet_cuisine_typesId21),
//            array('outlet_id'=>$OutletId10,'cuisine_type_id'=>$Outlet_cuisine_typesId22),
//            array('outlet_id'=>$OutletId10,'cuisine_type_id'=>$Outlet_cuisine_typesId23),
//            array('outlet_id'=>$OutletId10,'cuisine_type_id'=>$Outlet_cuisine_typesId24),
//            array('outlet_id'=>$OutletId11,'cuisine_type_id'=>$Outlet_cuisine_typesId25),
//            array('outlet_id'=>$OutletId11,'cuisine_type_id'=>$Outlet_cuisine_typesId26),
//            array('outlet_id'=>$OutletId11,'cuisine_type_id'=>$Outlet_cuisine_typesId27),
//            array('outlet_id'=>$OutletId11,'cuisine_type_id'=>$Outlet_cuisine_typesId28),
//            array('outlet_id'=>$OutletId12,'cuisine_type_id'=>$Outlet_cuisine_typesId29),
//            array('outlet_id'=>$OutletId12,'cuisine_type_id'=>$Outlet_cuisine_typesId30),
//            array('outlet_id'=>$OutletId13,'cuisine_type_id'=>$Outlet_cuisine_typesId31),
//            array('outlet_id'=>$OutletId13,'cuisine_type_id'=>$Outlet_cuisine_typesId32),
//            array('outlet_id'=>$OutletId13,'cuisine_type_id'=>$Outlet_cuisine_typesId33),
//            array('outlet_id'=>$OutletId13,'cuisine_type_id'=>$Outlet_cuisine_typesId34),
//            array('outlet_id'=>$OutletId14,'cuisine_type_id'=>$Outlet_cuisine_typesId35),
//            array('outlet_id'=>$OutletId14,'cuisine_type_id'=>$Outlet_cuisine_typesId36),
//            array('outlet_id'=>$OutletId14,'cuisine_type_id'=>$Outlet_cuisine_typesId37),
//            array('outlet_id'=>$OutletId14,'cuisine_type_id'=>$Outlet_cuisine_typesId38),
//            array('outlet_id'=>$OutletId15,'cuisine_type_id'=>$Outlet_cuisine_typesId39),
//            array('outlet_id'=>$OutletId15,'cuisine_type_id'=>$Outlet_cuisine_typesId40),
//            array('outlet_id'=>$OutletId15,'cuisine_type_id'=>$Outlet_cuisine_typesId41),
//            array('outlet_id'=>$OutletId16,'cuisine_type_id'=>$Outlet_cuisine_typesId42),
//            array('outlet_id'=>$OutletId17,'cuisine_type_id'=>$Outlet_cuisine_typesId43),
//            array('outlet_id'=>$OutletId17,'cuisine_type_id'=>$Outlet_cuisine_typesId44),
//            array('outlet_id'=>$OutletId18,'cuisine_type_id'=>$Outlet_cuisine_typesId45),
        );

        DB::table('Outlet_cuisine_types')->insert($Outlet_cuisine_types);
    }

}

