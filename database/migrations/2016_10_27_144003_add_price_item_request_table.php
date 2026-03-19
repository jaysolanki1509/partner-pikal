<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceItemRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item_request', function($table) {

			$table->float('price',8,4)->after('what_item')->default('0.00');

		});

		$request = \App\ItemRequest::all();

		if ( isset($request) && sizeof($request) > 0 ) {
			foreach( $request as $req ) {
				$req->price = \App\Menu::getItemIngredPrice($req->what_item_id);
				$req->save();
			}
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
