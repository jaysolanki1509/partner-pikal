<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\City;


class OutletLocalityTableSeeder extends Seeder
{

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('locality')->delete();

            $city1 = City::where('name','Ahmedabad')->first();
            $city = $city1->id;

            $city2 = City::where('name','Mumbai')->first();
            $cityId1 = $city2->id;


        $locality = array(
            array('city_id' => $city,'locality'=>'Bopal'),
            array('city_id' => $city,'locality'=>'Ellis Bridge'),
            array('city_id' => $city,'locality'=>'South Bopal'),
            array('city_id' => $city,'locality'=>'S G Highway'),
            array('city_id' => $city,'locality'=>'Satellite'),
            array('city_id' => $city,'locality'=>'Gota'),
            array('city_id' => $city,'locality'=>'C G Road'),
            array('city_id' => $city,'locality'=>'Sola Road'),
            array('city_id' => $city,'locality'=>'Navarangpura'),
            array('city_id' => $city,'locality'=>'Ashram Road'),
            array('city_id' => $city,'locality'=>'Odhav'),
            array('city_id' => $city,'locality'=>'Bapunagar'),
            array('city_id' => $city,'locality'=>'Nava Wadaj'),
            array('city_id' => $city,'locality'=>'Jodhpur'),
            array('city_id' => $city,'locality'=>'Shahibaug'),
            array('city_id' => $city,'locality'=>'Nikol'),
            array('city_id' => $city,'locality'=>'Science City'),
            array('city_id' => $city,'locality'=>'New Ranip'),
            array('city_id' => $city,'locality'=>'Chandlodia'),
            array('city_id' => $city,'locality'=>'Memnagar'),
            array('city_id' => $city,'locality'=>'Gulabi Tekra'),
            array('city_id' => $city,'locality'=>'Sabarmati'),
            array('city_id' => $city,'locality'=>'Navrangpura'),
            array('city_id' => $city,'locality'=>'Paldi'),
            array('city_id' => $city,'locality'=>'Motera'),
            array('city_id' => $city,'locality'=>'Ghatlodia'),
            array('city_id' => $city,'locality'=>'AmbaVadi'),
            array('city_id' => $city,'locality'=>'Gurukul'),
            array('city_id' => $city,'locality'=>'Vejalpur'),
            array('city_id' => $city,'locality'=>'Bodakdev'),
            array('city_id' => $city,'locality'=>'Naranpura'),
            array('city_id' => $city,'locality'=>'Vastrapur'),
            array('city_id' => $city,'locality'=>'Prahlad Nagar'),
            array('city_id' => $city,'locality'=>'Chandkheda'),
            array('city_id' => $city,'locality'=>'Sarangpur'),
            array('city_id' => $cityId1,'locality'=>'Mira Road'),
            array('city_id' => $cityId1,'locality'=>'Andheri West'),
            array('city_id' => $cityId1,'locality'=>'Andheri East'),
            array('city_id' => $cityId1,'locality'=>'Dombivli East'),
            array('city_id' => $cityId1,'locality'=>'Powai'),
            array('city_id' => $cityId1,'locality'=>'Goregaon East'),
            array('city_id' => $cityId1,'locality'=>'Kandivali East'),
            array('city_id' => $cityId1,'locality'=>'Malad West'),
            array('city_id' => $cityId1,'locality'=>'Virar West'),
            array('city_id' => $cityId1,'locality'=>'Kalyan West'),
            array('city_id' => $cityId1,'locality'=>'Chembur'),
            array('city_id' => $cityId1,'locality'=>'Borivali West'),
            array('city_id' => $cityId1,'locality'=>'Bandra West'),
            array('city_id' => $cityId1,'locality'=>'Mulund West'),
            array('city_id' => $cityId1,'locality'=>'Kandivali West'),
            array('city_id' => $cityId1,'locality'=>'Badlapur East'),
            array('city_id' => $cityId1,'locality'=>'Goregaon West'),
            array('city_id' => $cityId1,'locality'=>'Borivali East'),
            array('city_id' => $cityId1,'locality'=>'Malad East'),
            array('city_id' => $cityId1,'locality'=>'Virar East'),
            array('city_id' => $cityId1,'locality'=>'Juhu'),
            array('city_id' => $cityId1,'locality'=>'Dadar West'),
            array('city_id' => $cityId1,'locality'=>'Mahim'),
            array('city_id' => $cityId1,'locality'=>'Bhayandar West'),
            array('city_id' => $cityId1,'locality'=>'Bandra East'),
            array('city_id' => $cityId1,'locality'=>'Mira Bhayandar'),
            array('city_id' => $cityId1,'locality'=>'Mumbai Central'),
        );


        DB::table('locality')->insert($locality);
    }

}
