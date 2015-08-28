<?php

//use Services;

class DailyElectricityTableSeeder extends Seeder {

    public function run()
    {
        DB::table('daily_electricity')->truncate();

        $date = new \Carbon\Carbon();
        \Services\Electricity::calculateMonthly($date);
        \Services\Electricity::calculateMonthly($date->addMonth());

    }

}