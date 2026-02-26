<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
//use Laracasts\TestDummy\Factory as TestDummy;
use App\Outletimage;
use App\Outlet;

class OutletImageTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('Outletimages')->delete();

        //Call Menu
//        $Outlet=Outlet::where('Outlet_name','WOW')->first();
//        $OutletId = $Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','jungle bhookh')->first();
//        $OutletId1=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Shree Mehfil')->first();
//        $OutletId2= $Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Pleasure Trove')->first();
//        $OutletId3=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Shambhus Coffee Bar')->first();
//        $OutletId4=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Cafe Coffee Day')->first();
//        $OutletId5=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Saffron Outlet')->first();
//        $OutletId6=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Sankalp Outlet')->first();
//        $OutletId7=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Havmor Outlet')->first();
//        $OutletId8=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Not Just Grill')->first();
//        $OutletId9=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Dinner Bell2')->first();
//        $OutletId10=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Cafe Upper Crust')->first();
//        $OutletId11=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Cellad Eatery')->first();
//        $OutletId12=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Toritos')->first();
//        $OutletId13=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Tomatos')->first();
//        $OutletId14=$Outlet->id;
//
//        $Outlet=Outlet::where('Outlet_name','Page One')->first();
//        $OutletId15=$Outlet->id;

//        $Outlet=Outlet::where('Outlet_name','Dadaji')->first();
//        $OutletId16=$Outlet->id;
//        $Outlet=Outlet::where('Outlet_name','Punjabi Bites')->first();
//        $OutletId17=$Outlet->id;
//        $Outlet=Outlet::where('Outlet_name','Purohit Sandwich')->first();
//        $OutletId18=$Outlet->id;
//        $Outlet=Outlet::where('Outlet_name','Grill INN')->first();
//        $OutletId19=$Outlet->id;

        $OutletImages = array(
//            array('outlet_id' => $OutletId,'image_name'=>'WOW(B1).jpg'),
//            array('outlet_id' => $OutletId,'image_name'=>'wow(B2).jpg'),
//            array('outlet_id' => $OutletId,'image_name'=>'WOW(B3).jpg'),
//            array('outlet_id' => $OutletId,'image_name'=>'WOW(B4).jpg'),
//            array('outlet_id' => $OutletId,'image_name'=>'WOW(B5).jpg'),
//            array('outlet_id' => $OutletId1,'image_name'=>'JUNGLEBHOOKH(B1).jpg'),
//            array('outlet_id' => $OutletId1,'image_name'=>'JUNGLE(B2).jpg'),
//            array('outlet_id' => $OutletId1,'image_name'=>'JUNGLE BHOOKH(3).jpg'),
//            array('outlet_id' => $OutletId1,'image_name'=>'JUNGLE(B4).jpg'),
//            array('outlet_id' => $OutletId1,'image_name'=>'JUNGLE(B5).jpg'),
//            array('outlet_id' => $OutletId2,'image_name'=>'SREEMEHFIL(B1).jpg'),
//            array('outlet_id' => $OutletId2,'image_name'=>'SREEMEHFIL(B2).jpg'),
//            array('outlet_id' => $OutletId2,'image_name'=>'SREEMEHFIL(B3).jpg'),
//            array('outlet_id' => $OutletId2,'image_name'=>'SREEMEHFIL(B4).jpg'),
//            array('outlet_id' => $OutletId2,'image_name'=>'SREEMEHFIL(B5).jpg'),
//            array('outlet_id' => $OutletId3,'image_name'=>'PLEASURETROVE(B1).jpg'),
//            array('outlet_id' => $OutletId3,'image_name'=>'PLEASURETROVE(B2).jpg'),
//            array('outlet_id' => $OutletId3,'image_name'=>'PLEASURETROVE(B3).jpg'),
//            array('outlet_id' => $OutletId3,'image_name'=>'PLEASURETROVE(B4).jpg'),
//            array('outlet_id' => $OutletId3,'image_name'=>'PLEASURETROVE(B5).jpg'),
//            array('outlet_id' => $OutletId4,'image_name'=>'SHAMBHU(B1).jpg'),
//            array('outlet_id' => $OutletId4,'image_name'=>'SHAMBHU(B2).jpg'),
//            array('outlet_id' => $OutletId4,'image_name'=>'SHAMBHU(B3).jpg'),
//            array('outlet_id' => $OutletId4,'image_name'=>'SHAMBHU(B4).jpg'),
//            array('outlet_id' => $OutletId4,'image_name'=>'SHAMBHU(B5).jpg'),
//            array('outlet_id' => $OutletId5,'image_name'=>'CAFE(B1).jpg'),
//            array('outlet_id' => $OutletId5,'image_name'=>'CAFE(B2).jpg'),
//            array('outlet_id' => $OutletId5,'image_name'=>'CAFE(B3).jpg'),
//            array('outlet_id' => $OutletId5,'image_name'=>'CAFE(B4).jpg'),
//            array('outlet_id' => $OutletId5,'image_name'=>'CAFE(B5).jpg'),
//            array('outlet_id' => $OutletId6,'image_name'=>'Saffron1(original size).jpg'),
//            array('outlet_id' => $OutletId6,'image_name'=>'Saffron2(original size).jpg'),
//            array('outlet_id' => $OutletId6,'image_name'=>'Saffron3(original size).JPG'),
//            array('outlet_id' => $OutletId6,'image_name'=>'Saffron4(original size).JPG'),
//            array('outlet_id' => $OutletId6,'image_name'=>'Saffron5(original size).JPG'),
//            array('outlet_id' => $OutletId7,'image_name'=>'Sankalp1(Original).jpg'),
//            array('outlet_id' => $OutletId7,'image_name'=>'Sankalp2(Original).jpg'),
//            array('outlet_id' => $OutletId7,'image_name'=>'Sankalp3(Original).jpg'),
//            array('outlet_id' => $OutletId7,'image_name'=>'Sankalp4(Original).jpg'),
//            array('outlet_id' => $OutletId7,'image_name'=>'Sankalp5(Original).jpg'),
//            array('outlet_id' => $OutletId8,'image_name'=>'Havemore(original1).jpg'),
//            array('outlet_id' => $OutletId8,'image_name'=>'Havemore(original2).jpg'),
//            array('outlet_id' => $OutletId8,'image_name'=>'Havemore(original3).jpeg'),
//            array('outlet_id' => $OutletId8,'image_name'=>'Havemore(original4).jpeg'),
//            array('outlet_id' => $OutletId8,'image_name'=>'Havemore(original5).jpg'),
//            array('outlet_id' => $OutletId9,'image_name'=>'Not Just Grill(Original1).jpg'),
//            array('outlet_id' => $OutletId9,'image_name'=>'Not Just Grill(Original2).jpg'),
//            array('outlet_id' => $OutletId9,'image_name'=>'Not Just Grill(Original3).jpg'),
//            array('outlet_id' => $OutletId9,'image_name'=>'Not Just Grill(Original4).jpg'),
//            array('outlet_id' => $OutletId9,'image_name'=>'Not Just Grill(Original5).jpg'),
//            array('outlet_id' => $OutletId10,'image_name'=>'Dinner Bell(Original1).jpeg'),
//            array('outlet_id' => $OutletId10,'image_name'=>'Dinner Bell(Original2).jpg'),
//            array('outlet_id' => $OutletId10,'image_name'=>'Dinner Bell(Original3).jpg'),
//            array('outlet_id' => $OutletId10,'image_name'=>'Dinner Bell(Original4).jpg'),
//            array('outlet_id' => $OutletId10,'image_name'=>'Dinner Bell(Original5).jpg'),
//            array('outlet_id' => $OutletId11,'image_name'=>'Cafe Upper Crust(Original1).jpg'),
//            array('outlet_id' => $OutletId11,'image_name'=>'Cafe Upper Crust(Original2).jpg'),
//            array('outlet_id' => $OutletId11,'image_name'=>'Cafe Upper Crust(Original3).jpg'),
//            array('outlet_id' => $OutletId11,'image_name'=>'Cafe Upper Crust(Original4).jpg'),
//            array('outlet_id' => $OutletId11,'image_name'=>'Cafe Upper Crust(Original5).jpg'),
//            array('outlet_id' => $OutletId12,'image_name'=>'Cellad Eatery(1).jpg'),
//            array('outlet_id' => $OutletId12,'image_name'=>'Cellad Eatery(2).jpg'),
//            array('outlet_id' => $OutletId12,'image_name'=>'Cellad Eatery(3).jpg'),
//            array('outlet_id' => $OutletId12,'image_name'=>'Cellad Eatery(4).jpg'),
//            array('outlet_id' => $OutletId12,'image_name'=>'Cellad Eatery(5).jpg'),
//            array('outlet_id' => $OutletId13,'image_name'=>'Toritos(1).jpg'),
//            array('outlet_id' => $OutletId13,'image_name'=>'Toritos(2).jpg'),
//            array('outlet_id' => $OutletId13,'image_name'=>'Toritos(3).jpg'),
//            array('outlet_id' => $OutletId13,'image_name'=>'Toritos(4).jpg'),
//            array('outlet_id' => $OutletId13,'image_name'=>'Toritos(5).jpg'),
//            array('outlet_id' => $OutletId14,'image_name'=>'Tomato(1).jpg'),
//            array('outlet_id' => $OutletId14,'image_name'=>'Tomato(2).jpg'),
//            array('outlet_id' => $OutletId14,'image_name'=>'Tomato(3).jpg'),
//            array('outlet_id' => $OutletId14,'image_name'=>'Tomato(4).jpg'),
//            array('outlet_id' => $OutletId14,'image_name'=>'Tomato(5).jpg'),
//            array('outlet_id' => $OutletId15,'image_name'=>'Pageone(1).jpg'),
//            array('outlet_id' => $OutletId15,'image_name'=>'Pageone(2).jpg'),
//            array('outlet_id' => $OutletId15,'image_name'=>'Pageone(3).jpg'),
//            array('outlet_id' => $OutletId15,'image_name'=>'Pageone(4).jpg'),
//            array('outlet_id' => $OutletId15,'image_name'=>'Pageone(5).jpg'),

//            array('outlet_id' => $OutletId16,'image_name'=>'Dadaji-01.jpg'),
//            array('outlet_id' => $OutletId16,'image_name'=>'Dadaji-02.jpg'),
//            array('outlet_id' => $OutletId16,'image_name'=>'Dadaji-03.jpg'),
//            array('outlet_id' => $OutletId17,'image_name'=>'punjabi-01.jpg'),
//            array('outlet_id' => $OutletId17,'image_name'=>'punjabi-02.jpg'),
//            array('outlet_id' => $OutletId17,'image_name'=>'punjabi-03.jpg'),
//            array('outlet_id' => $OutletId18,'image_name'=>'purohit-01.jpg'),
//            array('outlet_id' => $OutletId18,'image_name'=>'purohit-02.jpg'),
//            array('outlet_id' => $OutletId18,'image_name'=>'purohit-03.jpg'),
//            array('outlet_id' => $OutletId18,'image_name'=>'purohit-04.jpg'),
//            array('outlet_id' => $OutletId19,'image_name'=>'purohit-04.jpg'),
//            array('outlet_id' => $OutletId19,'image_name'=>'purohit-04.jpg'),
//            array('outlet_id' => $OutletId19,'image_name'=>'purohit-04.jpg'),

//            array('outlet_id' => $OutletId16,'image_name'=>'Dadaji-01.jpg'),
//            array('outlet_id' => $OutletId16,'image_name'=>'Dadaji-02.jpg'),
//            array('outlet_id' => $OutletId16,'image_name'=>'Dadaji-03.jpg'),
//            array('outlet_id' => $OutletId17,'image_name'=>'punjabi-01.jpg'),
//            array('outlet_id' => $OutletId17,'image_name'=>'punjabi-02.jpg'),
//            array('outlet_id' => $OutletId17,'image_name'=>'punjabi-03.jpg'),
//            array('outlet_id' => $OutletId18,'image_name'=>'purohit-01.jpg'),
//            array('outlet_id' => $OutletId18,'image_name'=>'purohit-02.jpg'),
//            array('outlet_id' => $OutletId18,'image_name'=>'purohit-03.jpg'),
//            array('outlet_id' => $OutletId18,'image_name'=>'purohit-04.jpg'),
//            array('outlet_id' => $OutletId19,'image_name'=>'cover1.jpg'),
//            array('outlet_id' => $OutletId19,'image_name'=>'cover1.jpg'),
//            array('outlet_id' => $OutletId19,'image_name'=>'cover1.jpg'),

//
        );

        DB::table('Outletimages')->insert($OutletImages);
    }
}

