<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatestSensorValueToSensorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('sensors', function(Blueprint $table)
        {
            $table->integer('latest_value');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('sensors', function(Blueprint $table)
        {
            $table->dropColumn('latest_value');
        });
	}

}
