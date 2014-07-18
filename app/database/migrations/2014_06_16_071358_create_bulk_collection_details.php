<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkCollectionDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bulk_collection_details', function(Blueprint $table)
		{
			$table->string('refno');
			$table->char('feecode', 12);
			$table->double('amt', 12, 2);
			$table->string('rem')->nullable();
			$table->string('lastuser')->default(DB::raw('"current_user"()'));

			$table->primary(array('refno', 'feecode'));
			$table->foreign('refno')->references('refno')->on('srgb.bulk_collection_header')->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('feecode')->references('feecode')->on('srgb.fees')->onDelete('restrict')->onUpdate('cascade');
		});
		
		DB::unprepared('REVOKE ALL ON TABLE bulk_collection_details FROM PUBLIC;');
		DB::unprepared('REVOKE ALL ON TABLE bulk_collection_details FROM postgres;');
		DB::unprepared('GRANT ALL ON TABLE bulk_collection_details TO postgres;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bulk_collection_details TO srgb_admin;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bulk_collection_details TO srgb_cashier;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_details TO srgb_clerk;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_details TO srgb_controller;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_details TO srgb_assessor;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_details TO srgb_view_all;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bulk_collection_details TO srgb_cashiertmp;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_details TO srgb_faculty;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_details TO srgb_registrar;'); 
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bulk_collection_details');
	}

}
