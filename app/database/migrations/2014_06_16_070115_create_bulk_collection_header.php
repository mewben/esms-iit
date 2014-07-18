<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkCollectionHeader extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bulk_collection_header', function(Blueprint $table)
		{
			$table->string('refno');
			$table->string('fund', 3)->nullable();
			$table->char('sy', 9);
			$table->char('sem', 1);
			$table->char('studid', 10)->nullable();
			$table->string('payee');
			$table->string('bcode');
			$table->date('paydate')->default(DB::raw('date(now())'));
			$table->string('lastuser')->default(DB::raw('"current_user"()'));

			$table->primary('refno');
			$table->foreign('fund')->references('code')->on('ngas.funds')->onDelete('restrict')->onUpdate('cascade');
			$table->foreign('studid')->references('studid')->on('srgb.student')->onDelete('restrict')->onUpdate('cascade');
		});
		
		DB::unprepared('REVOKE ALL ON TABLE bulk_collection_header FROM PUBLIC;');
		DB::unprepared('REVOKE ALL ON TABLE bulk_collection_header FROM postgres;');
		DB::unprepared('GRANT ALL ON TABLE bulk_collection_header TO postgres;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bulk_collection_header TO srgb_admin;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bulk_collection_header TO srgb_cashier;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_header TO srgb_clerk;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_header TO srgb_controller;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_header TO srgb_assessor;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_header TO srgb_view_all;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE bulk_collection_header TO srgb_cashiertmp;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_header TO srgb_faculty;');
		DB::unprepared('GRANT SELECT ON TABLE bulk_collection_header TO srgb_registrar;'); 

		// CREATE TYPE rptsummaryofbilling2type
		$e = DB::select("SELECT 1 FROM pg_type WHERE typname = 'rptsummaryofbilling2type';");
		if (!$e) {
			DB::unprepared('CREATE TYPE "srgb"."rptsummaryofbilling2type" AS ("num" int4, "feecode" char(12), "feedesc" varchar(30), "amount" numeric(16,2), "acctcode" varchar(8));');
			DB::unprepared('ALTER TYPE "srgb"."rptsummaryofbilling2type" OWNER TO "postgres";');
		}

		// CREATE TYPE rptbulkcollectionstype
		$f = DB::select("SELECT 1 FROM pg_type WHERE typname = 'rptbulkcollectionstype';");
		if (!$f) {
			DB::unprepared('CREATE TYPE "srgb"."rptbulkcollectionstype" AS ("paydate" date, "refno" varchar(255), "studid" char(10), "payee" varchar(40), "acctcode" varchar(12), "acctname" varchar(30), "amount" numeric(12,2));');
			DB::unprepared('ALTER TYPE "srgb"."rptbulkcollectionstype" OWNER TO "postgres";');	
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bulk_collection_header');
	}

}
