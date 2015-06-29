<?php

class SensorValueTableSeeder extends Seeder {

    public function run()
    {
        DB::table('sensor_values')->truncate();

        $createdAt = new DateTime('now');
        $updatedAt = $createdAt;

        SensorValue::create(array(
            'sensor_id' => '1',
            'value' => 27,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ));

        SensorValue::create(array(
            'sensor_id' => '1',
            'value' => 34,
            'created_at' => $createdAt->modify('-1 day'),
            'updated_at' => $updatedAt
        ));

        SensorValue::create(array(
            'sensor_id' => '1',
            'value' => 30,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ));


        SensorValue::create(array(
            'sensor_id' => '2',
            'value' => 30,
            'created_at' => $createdAt->modify('-1 day'),
            'updated_at' => $updatedAt
        ));

        SensorValue::create(array(
            'sensor_id' => '2',
            'value' => 31,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ));

        SensorValue::create(array(
            'sensor_id' => '2',
            'value' => 28,
            'created_at' => $createdAt->modify('-1 day'),
            'updated_at' => $updatedAt
        ));
    }

}