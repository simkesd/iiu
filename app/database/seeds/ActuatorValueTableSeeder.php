<?php

class ActuatorValueTableSeeder extends Seeder {

    public function run()
    {
        DB::table('actuator_values')->truncate();

        $createdAt = new \Carbon\Carbon('2015-07-02 10:00:00');
        $createdAtDayBefore = new \Carbon\Carbon();
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
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->subHour(),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->subHours(2),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->subHours(5),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->subDays(1),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->subDays(1)->subHours(2),
        ));


        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->subDays(1)->subHours(5),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->subDays(1w)->subHours(7),
        ));
//        ActuatorValue::create(array(
//            'actuator_id' => '3',
//            'value' => false,
//            'created_at' => $createdAt,
//            'updated_at' => $updatedAt
//        ));
//
//
//        ActuatorValue::create(array(
//            'actuator_id' => '3',
//            'value' => true,
//            'created_at' => $createdAtDayBefore,
//            'updated_at' => $updatedAtDayBefore
//        ));
//
//        ActuatorValue::create(array(
//            'actuator_id' => '3',
//            'value' => false,
//            'created_at' => $createdAtTwoDaysBefore,
//            'updated_at' => $updatedAtTwoDaysBefore
//        ));
//
//        ActuatorValue::create(array(
//            'actuator_id' => '4',
//            'value' => true,
//            'created_at' => $createdAt,
//            'updated_at' => $updatedAt
//        ));
//
//        ActuatorValue::create(array(
//            'actuator_id' => '4',
//            'value' => false,
//            'created_at' => $createdAtDayBefore,
//            'updated_at' => $updatedAtDayBefore
//        ));
//
//        ActuatorValue::create(array(
//            'actuator_id' => '4',
//            'value' => true,
//            'created_at' => $createdAtTwoDaysBefore,
//            'updated_at' => $updatedAtTwoDaysBefore
//        ));
    }

}