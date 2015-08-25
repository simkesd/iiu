<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrentValueFieldToDailyElectricity extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('daily_electricity', function(Blueprint $table)
        {
            $table->integer('last_value');
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
            $table->dropColumn('last_value');
        });
	}

}
