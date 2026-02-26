<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSatisfiedUnitIdItemRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item_request', function(Blueprint $table)
		{
			$table->integer('satisfied_unit_id')->after('statisfied_qty')->nullable();
		});

		$request = \App\ItemRequest::where('satisfied','Yes')->get();
		if ( isset($request) && sizeof($request) > 0 ) {
			foreach ( $request as $req ) {
				$req->satisfied_unit_id = $req->unit_id;
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
