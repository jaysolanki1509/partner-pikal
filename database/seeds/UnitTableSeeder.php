<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UnitTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('unit')->delete();

        $units = array(
            array('name' => 'Kg'),
            array('name' => 'Liter'),
            array('name' => 'Gram'),
            array('name' => 'Ml'),
            array('name' => 'Piece')
        );

        DB::table('unit')->insert($units);
    }
}
