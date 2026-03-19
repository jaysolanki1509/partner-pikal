<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Outlet;

class TimingTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('timeslot')->delete();


//        $Outlet=Outlet::where('Outlet_name','WOW')->first();
//        $OutletId=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','jungle bhookh')->first();
//        $OutletId1=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Shree Mehfil')->first();
//        $OutletId2=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Pleasure Trove')->first();
//        $OutletId3=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Shambhus Coffee Bar')->first();
//        $OutletId4=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Cafe Coffee Day')->first();
//        $OutletId5=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Saffron Outlet')->first();
//        $OutletId6=$Outlet->id;
//
//
//
//        $Outlet=Outlet::where('Outlet_name','Sankalp Outlet')->first();
//        $OutletId7=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Havmor Outlet')->first();
//        $OutletId8=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Not Just Grill')->first();
//        $OutletId9=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Dinner Bell2')->first();
//        $OutletId10=$Outlet->id;
//
//
//
//        $Outlet=Outlet::where('Outlet_name','Cafe Upper Crust')->first();
//        $OutletId11=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Cellad Eatery')->first();
//        $OutletId12=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Toritos')->first();
//        $OutletId13=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Tomatos')->first();
//        $OutletId14=$Outlet->id;
//
//
//        $Outlet=Outlet::where('Outlet_name','Page One')->first();
//        $OutletId15=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Dadaji')->first();
        $OutletId16=$Outlet->id;

//        $Outlet=Outlet::where('Outlet_name','Punjabi Bites')->first();
//        $OutletId17=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Purohit Sandwich')->first();
        $OutletId18=$Outlet->id;

        $timing = array(
//            array('outlet_id'=>$OutletId,'from_time'=>'12:00 NOON','to_time'=>'3:00 PM'),
//            array('outlet_id'=>$OutletId,'from_time'=>'7:00 PM','to_time'=>'10:30 PM'),
//            array('outlet_id'=>$OutletId1,'from_time'=>'11:00 AM','to_time'=>'2:30 PM'),
//            array('outlet_id'=>$OutletId1,'from_time'=>'7:00 PM','to_time'=>'10:30 PM'),
//            array('outlet_id'=>$OutletId2,'from_time'=>'11:00 AM','to_time'=>'3:00 PM'),
//            array('outlet_id'=>$OutletId2,'from_time'=>'6:30 PM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId3,'from_time'=>'11:00 AM','to_time'=>'2:30 PM'),
//            array('outlet_id'=>$OutletId3,'from_time'=>'7:00 AM','to_time'=>'10:30 PM'),
//            array('outlet_id'=>$OutletId4,'from_time'=>'8:00 AM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId5,'from_time'=>'11:00 AM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId6,'from_time'=>'12:00 NOON','to_time'=>'3:00 PM'),
//            array('outlet_id'=>$OutletId6,'from_time'=>'7:00 PM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId7,'from_time'=>'11:00 AM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId8,'from_time'=>'12:00 NOON','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId9,'from_time'=>'11:00 AM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId10,'from_time'=>'11:00 AM','to_time'=>'3:00 PM'),
//            array('outlet_id'=>$OutletId10,'from_time'=>'7:00 PM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId11,'from_time'=>'9:00 AM','to_time'=>'10:45 PM'),
//            array('outlet_id'=>$OutletId12,'from_time'=>'12:00 NOON','to_time'=>'3:00 PM'),
//            array('outlet_id'=>$OutletId12,'from_time'=>'7:30 PM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId13,'from_time'=>'12 NOON','to_time'=>'3:00 PM'),
//            array('outlet_id'=>$OutletId13,'from_time'=>'7:00 PM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId14,'from_time'=>'12 NOON','to_time'=>'3:00 PM'),
//            array('outlet_id'=>$OutletId14,'from_time'=>'7:30 PM','to_time'=>'11:00 PM'),
//            array('outlet_id'=>$OutletId15,'from_time'=>'12 NOON','to_time'=>'2:45 PM'),
//            array('outlet_id'=>$OutletId15,'from_time'=>'7 PM','to_time'=>'10:45 PM'),
            array('outlet_id'=>$OutletId16,'from_time'=>'9:30 AM','to_time'=>'11:00 PM'),
            array('outlet_id'=>$OutletId18,'from_time'=>'9:00 AM','to_time'=>'10:30 PM'),
        );
        DB::table('timeslot')->insert($timing);
    }

}


