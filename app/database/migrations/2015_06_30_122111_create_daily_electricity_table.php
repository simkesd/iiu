<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyElectricityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('daily_electricity', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('actuator_id');
            $table->string('kw_spent');
            $table->string('kw_spent_in_month');
            $table->string('cost');
            $table->string('current_zone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('daily_electricity');
	}

}
