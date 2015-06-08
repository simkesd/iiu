<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		 $this->call('UserTableSeeder');
		 $this->call('SensorTableSeeder');
		 $this->call('SensorValueTableSeeder');
		 $this->call('ActuatorTableSeeder');
		 $this->call('ActuatorValueTableSeeder');
	}

}
