<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpendingToActuatorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('actuators', function(Blueprint $table)
        {
            $table->decimal('spending');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('actuators', function(Blueprint $table)
        {
            $table->dropColumn('spending');
        });
	}

}
