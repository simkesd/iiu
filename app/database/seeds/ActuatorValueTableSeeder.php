<?php

class ActuatorValueTableSeeder extends Seeder {

    public function run()
    {
        DB::table('actuator_values')->truncate();

        $createdAt = new DateTime('now');
        $createdAtDayBefore = new DateTime();
        $createdAtDayBefore->modify('-1 day');
        $createdAtTwoDaysBefore = new DateTime();
        $createdAtTwoDaysBefore->modify('-2 days');

        $updatedAt = $createdAt;
        $updatedAtDayBefore = $createdAtDayBefore;
        $updatedAtTwoDaysBefore = $createdAtTwoDaysBefore;

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAtDayBefore,
            'updated_at' => $updatedAtDayBefore
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAtTwoDaysBefore,
            'updated_at' => $updatedAtTwoDaysBefore
        ));

        ActuatorValue::create(array(
            'actuator_id' => '3',
            'value' => false,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ));


        ActuatorValue::create(array(
            'actuator_id' => '3',
            'value' => true,
            'created_at' => $createdAtDayBefore,
            'updated_at' => $updatedAtDayBefore
        ));

        ActuatorValue::create(array(
            'actuator_id' => '3',
            'value' => false,
            'created_at' => $createdAtTwoDaysBefore,
            'updated_at' => $updatedAtTwoDaysBefore
        ));

        ActuatorValue::create(array(
            'actuator_id' => '4',
            'value' => true,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ));

        ActuatorValue::create(array(
            'actuator_id' => '4',
            'value' => false,
            'created_at' => $createdAtDayBefore,
            'updated_at' => $updatedAtDayBefore
        ));

        ActuatorValue::create(array(
            'actuator_id' => '4',
            'value' => true,
            'created_at' => $createdAtTwoDaysBefore,
            'updated_at' => $updatedAtTwoDaysBefore
        ));
    }

}