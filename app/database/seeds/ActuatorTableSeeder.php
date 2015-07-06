    <?php

class ActuatorTableSeeder extends Seeder {

    public function run()
    {
        DB::table('actuators')->truncate();

        Actuator::create(array(
            'name' => 'Window1',
            'description' => 'Windows in classroom 304',
            'current_state' => true
        ));

        Actuator::create(array(
            'name' => 'Window2',
            'description' => 'Windows in classroom 202',
            'current_state' => true
        ));

        Actuator::create(array(
            'name' => 'Lights1',
            'description' => 'Lights in classroom 1001',
            'current_state' => true
        ));

        Actuator::create(array(
            'name' => 'Lights2',
            'description' => 'Lights in classroom B101',
            'current_state' => true
        ));
    }

}