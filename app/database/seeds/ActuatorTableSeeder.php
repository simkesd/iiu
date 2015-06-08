<?php

class ActuatorTableSeeder extends Seeder {

    public function run()
    {
        DB::table('actuators')->truncate();

        Actuator::create(array(
            'name' => 'Window1',
            'description' => 'Windows in classroom 304'
        ));

        Actuator::create(array(
            'name' => 'Window2',
            'description' => 'Windows in classroom 202'
        ));

        Actuator::create(array(
            'name' => 'Lights1',
            'description' => 'Lights in classroom 1001'
        ));

        Actuator::create(array(
            'name' => 'Lights2',
            'description' => 'Lights in classroom B101'
        ));
    }

}