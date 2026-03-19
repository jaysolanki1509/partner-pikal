<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();


        $this->call('LanguageTableSeeder');
        $this->call('CountriesTableSeeder');
        $this->call('StatesTableSeeder');
        $this->call('CitiesTableSeeder');
        $this->call('UsersTableSeeder');
       // $this->call('PermissionTableSeeder');
        $this->call('OutletTypeTableSeeder');
        $this->call('CuisineTypeTableSeeder');
        $this->call('OutletLocalityTableSeeder');
        //$this->call('OutletsTableSeeder');

      //  $this->call('DadajiOutletTableSeeder');
      //  $this->call('PunjabiBitesOutletTableSeeder');
      //  $this->call('PurohitSandwichOutletTableSeeder');
      //  $this->call('GrillINNOutletTableSeeder');
      //  $this->call('BiryaniOutletTableSeeder');
      //  $this->call('KnifeAndForkOutletTableSeeder');
        $this->call('UnitTableSeeder');
       // $this->call('CategoriesTableSeeder');
      //  $this->call('ItemMasterTableSeeder');
        $this->call('RoleSeedTableSeeder');
//        $this->call('StatusTableSeeder');


        //$this->call('TimingTableSeeder');

        //$this->call('OutletLatlongTableSeeder');

	}

}
