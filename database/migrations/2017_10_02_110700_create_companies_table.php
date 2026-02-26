<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('companies', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->string('name');
            $table->integer('contact_salutation_id')->unsigned();
            $table->foreign('contact_salutation_id')->references('id')->on('salutations');
            $table->string('contact_first_name');
            $table->string('contact_last_name');
            $table->string('contact_gender',10);
            $table->date('contact_dob');
            $table->string('contact_designation',20);
            $table->string('contact_phone',15);
            $table->string('contact_extn',20);
            $table->string('contact_fax',15);
            $table->string('contact_mobile',15);
            $table->string('contact_email',100);
            $table->string('website',100);
            $table->text('office_address');
            $table->integer('office_country_id')->unsigned();
            $table->foreign('office_country_id')->references('id')->on('countries');
            $table->integer('office_state_id')->unsigned();
            $table->foreign('office_state_id')->references('id')->on('states');
            $table->integer('office_city_id')->unsigned();
            $table->foreign('office_city_id')->references('id')->on('cities');
            $table->string('office_pincode',10);
            $table->text('billing_address');
            $table->integer('billing_country_id')->unsigned();
            $table->foreign('billing_country_id')->references('id')->on('countries');
            $table->integer('billing_state_id')->unsigned();
            $table->foreign('billing_state_id')->references('id')->on('states');
            $table->integer('billing_city_id')->unsigned();
            $table->foreign('billing_city_id')->references('id')->on('cities');
            $table->string('billing_pincode',10)->nullable();
            $table->string('billing_phone',15)->nullable();
            $table->string('billing_fax',15)->nullable();
            $table->string('billing_extn',20)->nullable();
            $table->float('credit_limit');
            $table->string('payment_terms');
            $table->float('discount_percentage');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('owners');
            $table->SoftDeletingTrait();
            $table->timestamps();

            $table->index('name');
            $table->index('credit_limit');
            $table->index('discount_percentage');
            $table->index('contact_phone');
            $table->index('contact_first_name');
            $table->index('contact_last_name');
            $table->index('contact_mobile');
            $table->index('contact_email');
		});



        Schema::table('guests', function(Blueprint $table)
        {
            $table->integer('company_id')->unsigned()->nullable()->after('phone');
            $table->foreign('company_id')->references('id')->on('companies');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('companies');
	}

}
