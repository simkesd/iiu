<?php

class ActuatorValueTableSeeder extends Seeder {

    public function run()
    {
        DB::table('actuator_values')->truncate();

        $createdAt = new \Carbon\Carbon();
        $createdAt->firstOfMonth();

        /*
         ***********************
         * Day one
         ***********************
         */
        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt,
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->addHour(),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->addHours(2),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->addHours(5),
        ));

        /*
         ***********************
         * Day two
         ***********************
         */

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->addDays(1),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->addDays(1)->addHours(2),
        ));


        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->addDays(1)->addHours(5),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->addDays(1)->addHours(7),
        ));


        /*
         ***********************
         * Day seven
         ***********************
         */
        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->addDays(7)->addHours(7),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->addDays(7)->addHours(10),
        ));


        /*
         ***********************
         * Day three
         ***********************
         */
        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->addDays(3)->addHours(2),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->addDays(3)->addHours(11),
        ));


        /*
         ***********************
         * Day eighteen
         ***********************
         */
        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->addDays(18)->addHours(5),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->addDays(18)->addHours(16),
        ));
    }

}