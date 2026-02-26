<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Owner;
use App\Outlet;

class StatusTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('status')->delete();

        $user=Owner::where('user_name','parag')->first();
        $userID=$user->id;

        $Outlet=Outlet::where('Outlet_name','WOW')->first();
        $OutletId=$Outlet->id;


        $Outlet=Outlet::where('Outlet_name','jungle bhookh')->first();
        $OutletId1=$Outlet->id;


        $Outlet=Outlet::where('Outlet_name','Shree Mehfil')->first();
        $OutletId2=$Outlet->id;


        $Outlet=Outlet::where('Outlet_name','Pleasure Trove')->first();
        $OutletId3=$Outlet->id;


        $Outlet=Outlet::where('Outlet_name','Shambhus Coffee Bar')->first();
        $OutletId4=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Cafe Coffee Day')->first();
        $OutletId5=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Saffron Outlet')->first();
        $OutletId6=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Sankalp Outlet')->first();
        $OutletId7=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Havmor Outlet')->first();
        $OutletId8=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Not Just Grill')->first();
        $OutletId9=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Dinner Bell2')->first();
        $OutletId10=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Cafe Upper Crust')->first();
        $OutletId11=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Cellad Eatery')->first();
        $OutletId12=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Toritos')->first();
        $OutletId13=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Tomatos')->first();
        $OutletId14=$Outlet->id;

        $Outlet=Outlet::where('Outlet_name','Page One')->first();
        $OutletId15=$Outlet->id;




        $status = array(
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId1,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId1,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId1,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId1,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId2,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId2,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId2,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId2,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId3,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId3,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId3,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId3,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId4,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId4,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId4,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId4,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId5,'order'=>'1','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId5,'order'=>'2','status'=>'confirmed'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId5,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId5,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId6,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId6,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId6,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId6,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId7,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId7,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId7,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId7,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId8,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId8,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId8,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId8,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId9,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId9,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId9,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId9,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId10,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId10,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId10,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId10,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId11,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId11,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId11,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId11,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId12,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId12,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId12,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId12,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId13,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId13,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId13,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId13,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId14,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId14,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId14,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId14,'order'=>'4','status'=>'delievered'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId15,'order'=>'1','status'=>'received'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId15,'order'=>'2','status'=>'preparing'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId15,'order'=>'3','status'=>'prepared'),
//            array('created_by'=>$userID,'Outlet_name'=>$OutletId15,'order'=>'4','status'=>'delievered'),

        );


        DB::table('status')->insert($status);
    }

}



