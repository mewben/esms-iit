<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundHeaderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('refund_header', function(Blueprint $table)
		{
			$table->string('refno');
			$table->char('sy', 9);
			$table->char('sem', 1);
			$table->char('studid', 10)->nullable();
			$table->string('payee');
			$table->date('paydate')->default(DB::raw('date(now())'));
			$table->string('remarks')->nullable();
			$table->string('lastuser')->default(DB::raw('"current_user"()'));

			$table->primary('refno');
			$table->foreign('studid')->references('studid')->on('srgb.student')->onDelete('restrict')->onUpdate('cascade');
		});

		DB::unprepared('REVOKE ALL ON TABLE refund_header FROM PUBLIC;');
		DB::unprepared('REVOKE ALL ON TABLE refund_header FROM postgres;');
		DB::unprepared('GRANT ALL ON TABLE refund_header TO postgres;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE refund_header TO srgb_admin;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE refund_header TO srgb_cashier;');
		DB::unprepared('GRANT SELECT ON TABLE refund_header TO srgb_clerk;');
		DB::unprepared('GRANT SELECT ON TABLE refund_header TO srgb_controller;');
		DB::unprepared('GRANT SELECT ON TABLE refund_header TO srgb_assessor;');
		DB::unprepared('GRANT SELECT ON TABLE refund_header TO srgb_view_all;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE refund_header TO srgb_cashiertmp;');
		DB::unprepared('GRANT SELECT ON TABLE refund_header TO srgb_faculty;');
		DB::unprepared('GRANT SELECT ON TABLE refund_header TO srgb_registrar;'); 

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('refund_header');
	}

}
