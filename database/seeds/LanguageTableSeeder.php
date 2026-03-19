<?php
use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class LanguageTableSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();

        DB::table('languages')->insert(
            array(
                array(
                    'code' => 'en',
                    'name' => 'English'
                ),
                array(
                    'code' => 'hi',
                    'name' => 'Hindi'
                ),
                array(
                    'code' => 'gj',
                    'name' => 'Gujarati'
                )
            )
        );

    }

}