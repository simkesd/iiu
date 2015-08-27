<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPeriodsToDailyElectricity extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('daily_electricity', function(Blueprint $table)
        {
            $table->integer('period_on');
            $table->integer('period_off');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('daily_electricity', function(Blueprint $table)
        {
            $table->dropColumn('period_on');
            $table->dropColumn('period_off');
        });
	}

}
