<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('refund_details', function(Blueprint $table)
		{
			$table->string('refno');
			$table->char('feecode', 12);
			$table->double('amt', 12, 2);
			$table->string('lastuser')->default(DB::raw('"current_user"()'));

			$table->primary(array('refno', 'feecode'));
			$table->foreign('refno')->references('refno')->on('srgb.refund_header')->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('feecode')->references('feecode')->on('srgb.fees')->onDelete('restrict')->onUpdate('cascade');
		});

		DB::unprepared('REVOKE ALL ON TABLE refund_details FROM PUBLIC;');
		DB::unprepared('REVOKE ALL ON TABLE refund_details FROM postgres;');
		DB::unprepared('GRANT ALL ON TABLE refund_details TO postgres;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE refund_details TO srgb_admin;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE refund_details TO srgb_cashier;');
		DB::unprepared('GRANT SELECT ON TABLE refund_details TO srgb_clerk;');
		DB::unprepared('GRANT SELECT ON TABLE refund_details TO srgb_controller;');
		DB::unprepared('GRANT SELECT ON TABLE refund_details TO srgb_assessor;');
		DB::unprepared('GRANT SELECT ON TABLE refund_details TO srgb_view_all;');
		DB::unprepared('GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE refund_details TO srgb_cashiertmp;');
		DB::unprepared('GRANT SELECT ON TABLE refund_details TO srgb_faculty;');
		DB::unprepared('GRANT SELECT ON TABLE refund_details TO srgb_registrar;'); 
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('refund_details');
	}

}
