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
            'created_at' => $createdAt->copy()->addHour(),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->addHours(3),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $createdAt->copy()->addHours(5),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $createdAt->copy()->addHours(10),
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


        /*
         ***********************
         * MONTH TWO
         ***********************
         */
        $monthTwo = $createdAt->copy()->addMonth();

        /*
        ***********************
        * Day one
        ***********************
        */
        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $monthTwo->copy()->addHour(),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $monthTwo->copy()->addHours(13),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $monthTwo->copy()->addHours(18),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $monthTwo->copy()->addHours(19),
        ));

        /*
         ***********************
         * Day two
         ***********************
         */

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $monthTwo->copy()->addDays(1),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $monthTwo->copy()->addDays(1)->addHours(14),
        ));


        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $monthTwo->copy()->addDays(1)->addHours(2),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $monthTwo->copy()->addDays(1)->addHours(19),
        ));


        /*
         ***********************
         * Day seven
         ***********************
         */
        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $monthTwo->copy()->addDays(7)->addHours(7),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $monthTwo->copy()->addDays(7)->addHours(11),
        ));


        /*
         ***********************
         * Day three
         ***********************
         */
        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $monthTwo->copy()->addDays(8)->addHours(2),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $monthTwo->copy()->addDays(8)->addHours(20),
        ));


        /*
         ***********************
         * Day eighteen
         ***********************
         */
        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
            'created_at' => $monthTwo->copy()->addDays(13)->addHours(5),
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
            'created_at' => $monthTwo->copy()->addDays(13)->addHours(12),
        ));
    }

}