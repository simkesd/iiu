<?php

class SensorValueTableSeeder extends Seeder {

    public function run()
    {
        DB::table('sensor_values')->truncate();

        SensorValue::create(array(
            'sensor_id' => '1',
            'value' => 27,
        ));

        SensorValue::create(array(
            'sensor_id' => '1',
            'value' => 34,
        ));

        SensorValue::create(array(
            'sensor_id' => '1',
            'value' => 30,
        ));


        SensorValue::create(array(
            'sensor_id' => '2',
            'value' => 30,
        ));

        SensorValue::create(array(
            'sensor_id' => '2',
            'value' => 31,
        ));

        SensorValue::create(array(
            'sensor_id' => '2',
            'value' => 28,
        ));
    }

}