<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CategoriesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('categories')->delete();

        $catagories = array(
            array('id' => '1','title'=>'Grocery','display_order'=> '1'),
            array('id' => '2','title'=>'Vegetables','display_order'=> '2'),
            array('id' => '3','title'=>'Dairy','display_order'=> '3'),
            array('id' => '4','title'=>'Disposables','display_order'=> '4'),
            array('id' => '5','title'=>'Toiletries','display_order'=> '5'),
        );

        DB::table('categories')->insert($catagories);


    }

}