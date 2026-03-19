<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalFieldPurchasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchases', function(Blueprint $table)
		{
			$table->index('created_by');
			$table->index('updated_by');
			$table->index('unit_id');
			$table->index('location_id');
			$table->index('purchase_date');
			$table->index('created_at');
			$table->index('updated_at');
			$table->float('total')->after('quantity');
		});

		$all = \App\Purchase::all();
		if ( isset($all) && sizeof($all) > 0 ) {
			foreach($all as $pur ) {
				$total = $pur->rate * $pur->quantity;
				\App\Purchase::where('id',$pur->id)->update(['total'=>$total]);
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
