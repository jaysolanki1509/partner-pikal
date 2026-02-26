<?php

use Illuminate\Database\Seeder;
use App\OutletOutletType;
use App\Outlet;
use App\OutletType;

// composer require laracasts/testdummy
//use Laracasts\TestDummy\Factory as TestDummy;

class OutletOutletTypeTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('Outlet_Outlet_types')->delete();

//        $Outlet=Outlet::where('Outlet_name','WOW')->first();
//        $OutletId=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Casual Dining')->first();
//        $Outlet_Outlet_typesId = $Outlet_Outlet_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','jungle bhookh')->first();
//        $OutletId1=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Theme Outlet')->first();
//        $Outlet_Outlet_typesId1=$Outlet_Outlet_types->id;
//        // echo "asdas";exit;
//
//        $Outlet=Outlet::where('Outlet_name','Shree Mehfil')->first();
//        $OutletId2=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Quick Bites')->first();
//        $Outlet_Outlet_typesId2=$Outlet_Outlet_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Pleasure Trove')->first();
//        $OutletId3=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Casual Dining')->first();
//        $Outlet_Outlet_typesId3=$Outlet_Outlet_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Shambhus Coffee Bar')->first();
//        $OutletId4=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Quick Bites')->first();
//        $Outlet_Outlet_typesId4=$Outlet_Outlet_types->id;
//        $Outlet_Outlet_types=OutletType::where('type','Café or Bistro')->first();
//        $Outlet_Outlet_typesId5=$Outlet_Outlet_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Cafe Coffee Day')->first();
//        $OutletId5=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Café or Bistro')->first();
//        $Outlet_Outlet_typesId6=$Outlet_Outlet_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Saffron Outlet')->first();
//        $OutletId6=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Casual Dining')->first();
//        $Outlet_Outlet_typesId7 = $Outlet_Outlet_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Sankalp Outlet')->first();
//        $OutletId7=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Casual Dining')->first();
//        $Outlet_Outlet_typesId8 = $Outlet_Outlet_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Havmor Outlet')->first();
//        $OutletId8=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Ice cream')->first();
//        $Outlet_Outlet_typesId9 = $Outlet_Outlet_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Not Just Grill')->first();
//        $OutletId9=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Casual Dining')->first();
//        $Outlet_Outlet_typesId10 = $Outlet_Outlet_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Dinner Bell2')->first();
//        $OutletId10=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Casual Dining')->first();
//        $Outlet_Outlet_typesId11 = $Outlet_Outlet_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Cafe Upper Crust')->first();
//        $OutletId11=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Café or Bistro')->first();
//        $Outlet_Outlet_typesId12 = $Outlet_Outlet_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Cellad Eatery')->first();
//        $OutletId12=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Food court')->first();
//        $Outlet_Outlet_typesId13 = $Outlet_Outlet_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Toritos')->first();
//        $OutletId13=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Theme Outlet')->first();
//        $Outlet_Outlet_typesId14 = $Outlet_Outlet_types->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Tomatos')->first();
//        $OutletId14=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Theme Outlet')->first();
//        $Outlet_Outlet_typesId15 = $Outlet_Outlet_types->id;
//
//        $Outlet=Outlet::where('Outlet_name','Page One')->first();
//        $OutletId15=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Casual Dining')->first();
//        $Outlet_Outlet_typesId16 = $Outlet_Outlet_types->id;

//        $Outlet=Outlet::where('Outlet_name','Dadaji')->first();
//        $OutletId16=$Outlet->id;
//        $Outlet_Outlet_types=OutletType::where('type','Quick Bites')->first();
//        $Outlet_Outlet_typesId17 = $Outlet_Outlet_types->id;
//
//
//
//        $Outlet=Outlet::where('Outlet_name','Purohit Sandwich')->first();
//        $OutletId18=$Outlet->id;



        $Outlet_Outlet_types = array(
//            array('outlet_id'=>$OutletId,'Outlet_type_id'=>$Outlet_Outlet_typesId),
//            array('outlet_id'=>$OutletId1,'Outlet_type_id'=>$Outlet_Outlet_typesId1),
//            array('outlet_id'=>$OutletId2,'Outlet_type_id'=>$Outlet_Outlet_typesId2),
//            array('outlet_id'=>$OutletId3,'Outlet_type_id'=>$Outlet_Outlet_typesId3),
//            array('outlet_id'=>$OutletId4,'Outlet_type_id'=>$Outlet_Outlet_typesId4),
//            array('outlet_id'=>$OutletId4,'Outlet_type_id'=>$Outlet_Outlet_typesId5),
//            array('outlet_id'=>$OutletId5,'Outlet_type_id'=>$Outlet_Outlet_typesId6),
//            array('outlet_id'=>$OutletId6,'Outlet_type_id'=>$Outlet_Outlet_typesId7),
//            array('outlet_id'=>$OutletId7,'Outlet_type_id'=>$Outlet_Outlet_typesId8),
//            array('outlet_id'=>$OutletId8,'Outlet_type_id'=>$Outlet_Outlet_typesId9),
//            array('outlet_id'=>$OutletId9,'Outlet_type_id'=>$Outlet_Outlet_typesId10),
//            array('outlet_id'=>$OutletId10,'Outlet_type_id'=>$Outlet_Outlet_typesId11),
//            array('outlet_id'=>$OutletId11,'Outlet_type_id'=>$Outlet_Outlet_typesId12),
//            array('outlet_id'=>$OutletId12,'Outlet_type_id'=>$Outlet_Outlet_typesId13),
//            array('outlet_id'=>$OutletId13,'Outlet_type_id'=>$Outlet_Outlet_typesId14),
//            array('outlet_id'=>$OutletId14,'Outlet_type_id'=>$Outlet_Outlet_typesId15),
//            array('outlet_id'=>$OutletId15,'Outlet_type_id'=>$Outlet_Outlet_typesId16),
//            array('outlet_id'=>$OutletId16,'Outlet_type_id'=>$Outlet_Outlet_typesId17),
//            array('outlet_id'=>$OutletId18,'Outlet_type_id'=>$Outlet_Outlet_typesId17),
        );

        DB::table('Outlet_Outlet_types')->insert($Outlet_Outlet_types);
    }

}

