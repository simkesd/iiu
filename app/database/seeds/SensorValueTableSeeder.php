<?php

class SensorValueTableSeeder extends Seeder {

    public function run()
    {
        DB::table('sensor_values')->truncate();

        $createdAt = new DateTime('now');
        $createdAtDayBefore = new DateTime();
        $createdAtDayBefore->modify('-1 day');
        $createdAtTwoDaysBefore = new DateTime();
        $createdAtTwoDaysBefore->modify('-2 days');

        $updatedAt = $createdAt;
        $updatedAtDayBefore = $createdAtDayBefore;
        $updatedAtTwoDaysBefore = $createdAtTwoDaysBefore;

        SensorValue::create(array(
            'sensor_id' => '1',
            'value' => 27,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ));

        SensorValue::create(array(
            'sensor_id' => '1',
            'value' => 34,
            'created_at' => $createdAtDayBefore,
            'updated_at' => $updatedAtDayBefore
        ));

        SensorValue::create(array(
            'sensor_id' => '1',
            'value' => 34,
            'created_at' => $createdAtTwoDaysBefore,
            'updated_at' => $updatedAtTwoDaysBefore
        ));

        SensorValue::create(array(
            'sensor_id' => '2',
            'value' => 30,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ));


        SensorValue::create(array(
            'sensor_id' => '2',
            'value' => 30,
            'created_at' => $createdAtDayBefore,
            'updated_at' => $updatedAtDayBefore
        ));

        SensorValue::create(array(
            'sensor_id' => '2',
            'value' => 31,
            'created_at' => $createdAtTwoDaysBefore,
            'updated_at' => $updatedAtTwoDaysBefore
        ));
    }

}