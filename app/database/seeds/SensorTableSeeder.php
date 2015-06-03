<?php

class SensorTableSeeder extends Seeder {

    public function run()
    {
        DB::table('sensors')->truncate();

        Sensor::create(array(
            'name' => 'Temperature304',
            'description' => 'Temperature in classroom 304',
            'value_type' => '°C'
        ));

        Sensor::create(array(
            'name' => 'Temperature202',
            'description' => 'Temperature in classroom 202',
            'value_type' => '°C'
        ));

        Sensor::create(array(
            'name' => 'Temperature1001',
            'description' => 'Temperature in classroom 1001',
            'value_type' => '°C'
        ));

        Sensor::create(array(
            'name' => 'TemperatureB101',
            'description' => 'Temperature in classroom B101',
            'value_type' => '°C'
        ));
    }

}