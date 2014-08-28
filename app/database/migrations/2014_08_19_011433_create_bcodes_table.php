<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bcodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('bcode');
			$table->string('desc')->nullable();

			$table->unique('bcode');
		});

		DB::table('bcodes')->insert(array(
			array('bcode' => 'FCB', 'desc' => 'First Consolidated Bank'),
			array('bcode' => 'RF', 'desc' => 'From Refund'),
			array('bcode' => 'ALAY', 'desc' => 'Alay-lakad Inc.'),
			array('bcode' => 'LGUTAG', 'desc' => 'LGU-Tagbilaran')
		));

		$e = DB::select("SELECT 1 FROM pg_constraint WHERE conname = 'bulk_collection_header_bcode_fk'");
		if (!$e)
			DB::unprepared("ALTER TABLE bulk_collection_header ADD CONSTRAINT bulk_collection_header_bcode_fk FOREIGN KEY (bcode) REFERENCES bcodes(bcode) ON DELETE RESTRICT ON UPDATE CASCADE");

		DB::unprepared('REVOKE ALL ON TABLE bcodes FROM PUBLIC;');
		DB::unprepared('REVOKE ALL ON TABLE bcodes FROM postgres;');
		DB::unprepared('GRANT ALL ON TABLE bcodes TO postgres;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bcodes TO srgb_admin;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bcodes TO srgb_cashier;');
		DB::unprepared('GRANT SELECT ON TABLE bcodes TO srgb_clerk;');
		DB::unprepared('GRANT SELECT ON TABLE bcodes TO srgb_controller;');
		DB::unprepared('GRANT SELECT ON TABLE bcodes TO srgb_assessor;');
		DB::unprepared('GRANT SELECT ON TABLE bcodes TO srgb_view_all;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bcodes TO srgb_cashiertmp;');
		DB::unprepared('GRANT SELECT ON TABLE bcodes TO srgb_faculty;');
		DB::unprepared('GRANT SELECT ON TABLE bcodes TO srgb_registrar;'); 
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$e = DB::select("SELECT 1 FROM pg_constraint WHERE conname = 'bulk_collection_header_bcode_fk'");
		if ($e) {
			DB::unprepared("ALTER TABLE bulk_collection_header DROP CONSTRAINT bulk_collection_header_bcode_fk");
		}
		Schema::drop('bcodes');
	}

}
