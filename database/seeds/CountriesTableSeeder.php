<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Country;

class CountriesTableSeeder extends Seeder
{

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        DB::table('countries')->delete();

        // staid in restaurants table staid = restaurant_id dynamic

        $countries =
            array(
                array('name' => 'Afghanistan'),
                array('name' => 'Argentina'),
                array('name' => 'Australia'),
                array('name' => 'Austria'),
                array('name' => 'Bangladesh'),
                array('name' => 'Belgium'),
                array('name' => 'Bhutan'),
                array('name' => 'Brazil'),
                array('name' => 'Canada'),
                array('name' => 'Cameroon'),
                array('name' => 'China'),
                array('name' => 'Colombia'),
                array('name' => 'Costa Rica'),
                array('name' => 'Cuba'),
                array('name' => 'Denmark'),
                array('name' => 'Egypt'),
                array('name' => 'France'),
                array('name' => 'Finland'),
                array('name' => 'Georgia'),
                array('name' => 'Germany'),
                array('name' => 'Ghana'),
                array('name' => 'Greece'),
                array('name' => 'Guyana'),
                array('name' => 'India'),
                array('name' => 'Indonesia'),
                array('name' => 'Iran'),
                array('name' => 'Iraq'),
                array('name' => 'Ireland'),
                array('name' => 'Italy'),
                array('name' => 'Japan'),
                array('name' => 'Kazakhstan'),
                array('name' => 'Kenya'),
                array('name' => 'Malaysia'),
                array('name' => 'Maldives'),
                array('name' => 'Mexico'),
                array('name' => 'Morocco'),
                array('name' => 'Myanmar (Burma)'),
                array('name' => 'Nepal'),
                array('name' => 'Netherlands'),
                array('name' => 'New Zealand'),
                array('name' => 'North Korea'),
                array('name' => 'Norway'),
                array('name' => 'Pakistan'),
                array('name' => 'Philippines'),
                array('name' => 'Poland'),
                array('name' => 'Portugal'),
                array('name' => 'Qatar'),
                array('name' => 'Romania'),
                array('name' => 'Russia'),
                array('name' => 'Saudi Arabia'),
                array('name' => 'Singapore'),
                array('name' => 'South Africa'),
                array('name' => 'Spain'),
                array('name' => 'Sri Lanka'),
                array('name' => 'Swaziland'),
                array('name' => 'Switzerland'),
                array('name' => 'Taiwan'),
                array('name' => 'Tajikistan'),
                array('name' => 'Thailand'),
                array('name' => 'Trinidad and Tobago'),
                array('name' => 'Turkey'),
                array('name' => 'Uganda'),
                array('name' => 'Ukraine'),
                array('name' => 'UK (United Kingdom)'),
                array('name' => 'USA (United States of America)'),
                array('name' => 'Uzbekistan'),
                array('name' => 'Zimbabwe'),
                array('name' => 'Zambia'),
            );

        DB::table('countries')->insert($countries);
    }
}
