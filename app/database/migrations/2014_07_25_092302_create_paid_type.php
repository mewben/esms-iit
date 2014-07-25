<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$e = DB::select("SELECT 1 FROM pg_type WHERE typname = 'paidtype';");
		if (!$e) {
			DB::unprepared('CREATE TYPE "srgb"."paidtype" AS ("ref" varchar(30), "paydate" date, "amt" numeric(16,2));');
			DB::unprepared('ALTER TYPE "srgb"."paidtype" OWNER TO "postgres";');
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
