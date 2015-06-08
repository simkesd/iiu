<?php

class ActuatorValueTableSeeder extends Seeder {

    public function run()
    {
        DB::table('actuator_values')->truncate();

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => true,
        ));

        ActuatorValue::create(array(
            'actuator_id' => '1',
            'value' => false,
        ));

        ActuatorValue::create(array(
            'actuator_id' => '3',
            'value' => false,
        ));


        ActuatorValue::create(array(
            'actuator_id' => '3',
            'value' => false,
        ));

        ActuatorValue::create(array(
            'actuator_id' => '4',
            'value' => true,
        ));

        ActuatorValue::create(array(
            'actuator_id' => '4',
            'value' => false,
        ));
    }

}