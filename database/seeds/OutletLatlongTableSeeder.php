<?php

use Illuminate\Database\Seeder;
use App\Outlet;

// composer require laracasts/testdummy
//use Laracasts\TestDummy\Factory as TestDummy;

class OutletLatlongTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('Outletlatlong')->delete();

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

        $Outlet_latlong = array(
            array('outlet_id'=>$OutletId,'latitude'=>23.0222,'longitude'=>72.5753),
            array('outlet_id'=>$OutletId1,'latitude'=>23.0222,'longitude'=>72.5753),
            array('outlet_id'=>$OutletId2,'latitude'=>23.061117,'longitude'=>72.53503739999996),
            array('outlet_id'=>$OutletId3,'latitude'=>22.58,'longitude'=>72.35),
            array('outlet_id'=>$OutletId4,'latitude'=>23.0127847000,'longitude'=>72.50587789999997000),
            array('outlet_id'=>$OutletId5,'latitude'=>23.0360,'longitude'=>72.5294),
            array('outlet_id'=>$OutletId6,'latitude'=>23.0370447,'longitude'=>72.56595579999998),
            array('outlet_id'=>$OutletId7,'latitude'=>22.676753,'longitude'=>72.88092430000006),
            array('outlet_id'=>$OutletId8,'latitude'=>23.03398,'longitude'=>72.50951199999997),
            array('outlet_id'=>$OutletId9,'latitude'=>23.0115919,'longitude'=>72.50527710000006),
            array('outlet_id'=>$OutletId10,'latitude'=>23.0523843,'longitude'=>72.53371820000007),
            array('outlet_id'=>$OutletId11,'latitude'=>23.0344488,'longitude'=>72.56468500000005),
            array('outlet_id'=>$OutletId12,'latitude'=>23.0271349,'longitude'=>72.55153840000003),
            array('outlet_id'=>$OutletId13,'latitude'=>23.0225936,'longitude'=>72.55611019999992),
            array('outlet_id'=>$OutletId14,'latitude'=>23.0156717,'longitude'=>72.55850579999992),
            array('outlet_id'=>$OutletId15,'latitude'=>23.0382581,'longitude'=>72.52330380000001),
           );

        DB::table('Outletlatlong')->insert($Outlet_latlong);
    }

}

