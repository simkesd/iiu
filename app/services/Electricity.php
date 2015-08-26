<?php

namespace Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Actuator;
use ActuatorValue;
//use Services\Electricity;
use DailyElectricity;
use Illuminate\Support\Facades\Response;

class Electricity extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'electricity'; }

    public static function calculateZone($kw)
    {
        if ($kw < 351) {
            return 'green';
        } else if ($kw < 1601) {
            return 'blue';
        } else {
            return 'red';
        }
    }

    public static function getElectricityPrices()
    {
//        $client = new \GuzzleHttp\Client([
//            // Base URI is used with relative requests
//            'base_uri' => 'http://happyfist.co:8081/',
//            // You can set any number of default request options.
//            'timeout' => 2.0,
//        ]);
//
//        $response = $client->get('scrape');
//
//        $url = "http://happyfist.co:8081/scrape";
//        $curl = curl_init($url);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//        $output = curl_exec($curl);
//        curl_close($curl);
//
//        $jsonResponse = json_decode($output);

//        return $jsonResponse;
        return json_decode('{
            "zelenaZona": {
                "jednotarifno": 5.68,
            "visa": 6.49,
            "niza": 1.62,
            "DUT": 1.62,
            "obracunskaSnaga": 51.41,
            "naknadaZaMernoMesto": 145.55
            },
            "plavaZona": {
                        "jednotarifno": 8.52,
            "visa": 9.79,
            "niza": 2.44,
            "DUT": 2.44,
            "obracunskaSnaga": 51.41,
            "naknadaZaMernoMesto": 145.55
            },
            "crvenaZona": {
                        "jednotarifno": 17.05,
            "visa": 19.48,
            "niza": 4.87,
            "DUT": 4.87,
            "obracunskaSnaga": 51.41,
            "naknadaZaMernoMesto": 145.55
            }}'
        );
    }

    public static function calculateCost($numOfHours, $powerConsumption, $prices, $kwSpentInMonth)
    {
        $kwSpent = $numOfHours * ($powerConsumption / 1000);

//        echo $kwSpentInMonth."<br>";
        if ($kwSpentInMonth < 351) {

            if (($kwSpentInMonth + $kwSpent) < 351) {
                $cost = $kwSpent * $prices->zelenaZona->jednotarifno;
            } else if (($kwSpentInMonth + $kwSpent) < 1601) {
                $greenZoneCost = (351 - $kwSpentInMonth) * $prices->zelenaZona->jednotarifno;
                $blueZoneCost = ($kwSpent - (351 - $kwSpentInMonth)) * $prices->plavaZona->jednotarifno;
                $cost = $greenZoneCost + $blueZoneCost;
            } else {
                $greenZoneCost = (351 - $kwSpentInMonth) * $prices->zelenaZona->jednotarifno;
                $blueZoneCost = ($kwSpent - (351 - $kwSpentInMonth)) * $prices->plavaZona->jednotarifno;
                $redZoneCost = ($kwSpent - (1601 - (351 - $kwSpentInMonth))) * $prices->crvenaZona->jednotarifno;
                $cost = $greenZoneCost + $blueZoneCost + $redZoneCost;
            }
        } else if ($kwSpentInMonth < 1601) {
            if (($kwSpentInMonth + $kwSpent) < 1601) {
                $cost = $kwSpent * $prices->plavaZona->jednotarifno;
            } else {
                $blueZoneCost = (1601 - $kwSpentInMonth) * $prices->plavaZona->jednotarifno;
                $redZoneCost = ($kwSpent - (1601 - $kwSpentInMonth)) * $prices->crvenaZona->jednotarifno;
                $cost = $blueZoneCost + $redZoneCost;
            }
        } else {
            $cost = $kwSpent * $prices->crvenaZona->jednotarifno;
        }

        return $cost;
    }

    public static function calculateDaily($date = null) {
//        DailyElectricity::truncate();

        $actuators = Actuator::all();
        $prices = Electricity::getElectricityPrices();
        $daily = [];
        /*
         * If no date param is passed, function will calculate daily electricity
         * for the day before today
         */
        if ($date != null) {
        } else {
            $date = new \Carbon\Carbon('now');
        }

        $beginingOfTheDay = $date->copy()->startOfDay();
        $endOfTheDay = $date->copy()->endOfDay();


        foreach($actuators as $actuator) {
            $periodOn = 0;
            $periodOff = 0;
            $kw_spent = 0;
            $cost = 0;
            $powerConsumption = $actuator->power_consumption / 1000; // in kw
            $lastValue = 0;

            $actuatorValues = ActuatorValue::orderBy('created_at', 'asc')
                ->where('created_at', '>=', $beginingOfTheDay)
                ->where('created_at', '<=', $endOfTheDay)
                ->where('actuator_id', '=', $actuator->id)
                ->get();

            if ($date->diffInDays($date->copy()->firstOfMonth()) == 0) {
                $kw_spent_in_month = 0;
            } else {
                $dailyElectricity = DailyElectricity::orderBy('id', 'desc')
                    ->where('created_at', '<', $endOfTheDay)
                    ->where('actuator_id', '=', $actuator->id)
                    ->first();

                if($dailyElectricity) {
                    $kw_spent_in_month = $dailyElectricity->kw_spent_in_month;
                }else {
//                    exit('Error');
                }
            }

            if(count($actuatorValues) > 0) {
                if ($actuatorValues[0]->value == 0) {
                    //actuator was turned on on the beginning of the day
                    $periodOnInTheBeginingOfTheDay = $actuatorValues[0]->created_at->diffInHours($beginingOfTheDay);
                    $periodOn += $periodOnInTheBeginingOfTheDay;
                    $kw_spent += $periodOnInTheBeginingOfTheDay * $powerConsumption;
                    $cost += \Services\Electricity::calculateCost($periodOnInTheBeginingOfTheDay, $actuator->power_consumption, $prices, $kw_spent);
                } else {
                    //actuator was turned off on the beginning of the day
                    $periodOff += $actuatorValues[0]->created_at->diffInHours($beginingOfTheDay);
                }

                $lastIndex = count($actuatorValues) - 1;
                foreach ($actuatorValues as $key => $actuatorValue) {
                    if ($key == $lastIndex) {
                        //already counted this period
                        continue;
                    } else {
                        if ($actuatorValue->value == 0) {
                            //actuator was turned on on the beginning of the day
                            $periodOff += $actuatorValue->created_at->diffInHours($actuatorValues[$key + 1]->created_at);
                        } else {
                            //actuator was turned off on the beginning of the day
                            $tempPeriodOn = $actuatorValue->created_at->diffInHours($actuatorValues[$key + 1]->created_at);
                            $periodOn += $tempPeriodOn;
                            $kw_spent += $tempPeriodOn * $powerConsumption;
                            $cost += \Services\Electricity::calculateCost($tempPeriodOn, $actuator->power_consumption, $prices, $kw_spent);
                        }
                    }
                }

                if ($actuatorValues[$lastIndex]->value == 0) {
                    //actuator was turned off on the end of the day
                    $periodOff += $actuatorValues[$lastIndex]->created_at->diffInHours($endOfTheDay);
                    $lastValue = 0;
                } else {
                    //actuator was turned on on the end of the day
                    $periodOnInTheEndOfTheDay = $actuatorValues[$lastIndex]->created_at->diffInHours($endOfTheDay);
                    $periodOn += $periodOnInTheEndOfTheDay;
                    $kw_spent += $periodOnInTheEndOfTheDay * $powerConsumption;
                    $cost += \Services\Electricity::calculateCost($periodOnInTheEndOfTheDay, $actuator->power_consumption, $prices, $kw_spent);
                    $lastValue = 1;
                }

                $data = array(
                    'actuator_id' => $actuator->id,
                    'kw_spent' => $kw_spent,
                    'kw_spent_in_month' => $kw_spent_in_month + $kw_spent,
                    'cost' => $cost,
                    'current_zone' => \Services\Electricity::calculateZone($kw_spent_in_month + $kw_spent),
                    'last_value' => $lastValue,
                    'created_at' => $date
                );

            }else {
                // get current state of actuator
                $lastDaySpendings = DailyElectricity::orderBy('id', 'desc')
                    ->first();
                if($lastDaySpendings) {
                    if($lastDaySpendings->last_value == 0) {
                        $kw_spent = 0;
                    }else {
                        $kw_spent = 24 * $powerConsumption;
                        $cost = \Services\Electricity::calculateCost(24, $actuator->power_consumption, $prices, $lastDaySpendings->kw_spent_in_month);
                    }
                    $kw_spent_in_month = $lastDaySpendings->kw_spent_in_month + $kw_spent;
                }else {
                    $kw_spent = 0;
                    $cost = 0;
                    $kw_spent_in_month = 0;
                }

                $data = array(
                    'actuator_id' => $actuator->id,
                    'kw_spent' => $kw_spent,
                    'kw_spent_in_month' => $kw_spent_in_month,
                    'cost' => $cost,
                    'current_zone' => Electricity::calculateZone($kw_spent_in_month + $kw_spent),
                    'last_value' => $lastValue,
                    'created_at' => $date
                );

            }

            DailyElectricity::create($data);
            $daily[] = $data;

        }

        return $daily;
    }

    public static function calculateDailyOld($date = null)
    {
        $daily = array();
        $actuators = Actuator::all();
        // Call the REST service for getting current electricity prices
        $prices = Electricity::getElectricityPrices();

        foreach ($actuators as $actuator) {
            /*
             * If no date param is passed, function will calculate daily electricity
             * for the day before today
             */
            if ($date != null) {
            } else {
                $date = new \Carbon\Carbon('now');
            }

            $beginingOfTheDay = $date->copy()->startOfDay();
            $endOfTheDay = $date->copy()->endOfDay();

            $actuatorValues = ActuatorValue::orderBy('created_at', 'asc')
                ->where('created_at', '>=', $beginingOfTheDay)
                ->where('created_at', '<=', $endOfTheDay)
                ->where('actuator_id', '=', $actuator->id)
                ->get();

            $periodOn = 0;
            $periodOff = 0;
            $kw_spent = 0;
            $cost = 0;
            $kw_spent_in_month = 0;
            $current_zone = 'green';
            $powerConsumption = $actuator->power_consumption / 1000; // in kw
            $lastValue = 0;

            /*
             * If it is the first of month, there is no electricity spent and we set the variable value to 0
             * else we get the value from last daily electricity table. We are getting the daily result for
             * day before the day we are calculating the results for. kw_spent_in_month variable
             * contains cumulative value of spent electricity from the begining of the month
             */
            if ($date->diffInDays($date->copy()->firstOfMonth()) == 0) {
                $kw_spent_in_month = 0;
            } else {
                $dailyElectricity = DailyElectricity::orderBy('id', 'desc')
                    ->where('created_at', '<', $endOfTheDay)
                    ->where('actuator_id', '=', $actuator->id)
                    ->first();

                if($dailyElectricity) {
                    $kw_spent_in_month = $dailyElectricity->kw_spent_in_month;
                }else {
                    $kw_spent_in_month = 0;
                }

            }

            /*
             * Two cases. If there are values for selected day, and if there are no values.
             * If there are values we are calculating the electricity spent.
             * If there are no values we are getting the value from last known state of actuator before selected day,
             * and calculating the value as actuator was turned off/on the whole day.
             */
            if (count($actuatorValues) > 0) {
                /*
                 * Special case of calculating the value from the beginning of the day,
                 * until the first value is tracked.
                 */
                if ($actuatorValues[0]->value == 0) {
                    //actuator was turned on on the beginning of the day
                    $periodOnInTheBeginingOfTheDay = $actuatorValues[0]->created_at->diffInHours($beginingOfTheDay);
                    $periodOn += $periodOnInTheBeginingOfTheDay;
                    $kw_spent += $periodOnInTheBeginingOfTheDay * $powerConsumption;
                    $cost += \Services\Electricity::calculateCost($periodOnInTheBeginingOfTheDay, $actuator->power_consumption, $prices, $kw_spent);
                } else {
                    //actuator was turned off on the beginning of the day
                    $periodOff += $actuatorValues[0]->created_at->diffInHours($beginingOfTheDay);
                }

                $lastIndex = count($actuatorValues) - 1;

                /*
                 * Calculating electricity spent in between the first tracked
                 * and last tracked value in selected day
                 */
                foreach ($actuatorValues as $key => $actuatorValue) {
                    if ($key == $lastIndex) {
                        //already counted this period
                        continue;
                    } else {
                        if ($actuatorValue->value == 0) {
                            //actuator was turned on on the beginning of the day
                            $periodOff += $actuatorValue->created_at->diffInHours($actuatorValues[$key + 1]->created_at);
                        } else {
                            //actuator was turned off on the beginning of the day
                            $tempPeriodOn = $actuatorValue->created_at->diffInHours($actuatorValues[$key + 1]->created_at);
                            $periodOn += $tempPeriodOn;
                            $kw_spent += $tempPeriodOn * $powerConsumption;
//                            echo $kw_spent . '=' .$tempPeriodOn * $powerConsumption."<br>";
                            $cost += \Services\Electricity::calculateCost($tempPeriodOn, $actuator->power_consumption, $prices, $kw_spent);
                        }
                    }
                }


                /*
                 * Special case of calculating the value from the last tracked value,
                 * until the end of month
                 */
                if ($actuatorValues[$lastIndex]->value == 0) {
                    //actuator was turned off on the end of the day
                    $periodOff += $actuatorValues[$lastIndex]->created_at->diffInHours($endOfTheDay);
                    $lastValue = 0;
                } else {
                    //actuator was turned on on the end of the day
                    $periodOnInTheEndOfTheDay = $actuatorValues[$lastIndex]->created_at->diffInHours($endOfTheDay);
                    $periodOn += $periodOnInTheEndOfTheDay;
                    $kw_spent += $periodOnInTheEndOfTheDay * $powerConsumption;
                    $cost += \Services\Electricity::calculateCost($periodOnInTheEndOfTheDay, $actuator->power_consumption, $prices, $kw_spent);
                    $lastValue = 1;
                }

                $data = array(
                    'actuator_id' => $actuator->id,
                    'kw_spent' => $kw_spent,
                    'kw_spent_in_month' => $kw_spent_in_month + $kw_spent,
                    'cost' => $cost,
                    'current_zone' => \Services\Electricity::calculateZone($kw_spent_in_month + $kw_spent),
                    'last_value' => $lastValue,
                    'created_at' => $date
                );
            } else {

                // get current state of actuator
                $lastDaySpendings = DailyElectricity::orderBy('id', 'desc')
                    ->first();

                if($lastDaySpendings) {
                    if($lastDaySpendings->last_value == 0) {
                        $kw_spent = 0;
                    }else {
                        $kw_spent = 24 * $powerConsumption;
                        $cost = \Services\Electricity::calculateCost(24, $actuator->power_consumption, $prices, $lastDaySpendings->kw_spent_in_month);
                    }

                    $kw_spent_in_month = $lastDaySpendings->kw_spent_in_month + $kw_spent;
                }else {
                    $kw_spent = 0;
                    $cost = 0;
                    $kw_spent_in_month = 0;
                }

                $data = array(
                    'actuator_id' => $actuator->id,
                    'kw_spent' => $kw_spent,
                    'kw_spent_in_month' => $kw_spent_in_month,
                    'cost' => $cost,
                    'current_zone' => Electricity::calculateZone($kw_spent_in_month + $kw_spent),
                    'last_value' => $lastValue,
                    'created_at' => $date
                );
            }

            DailyElectricity::create($data);
            $daily[] = $data;

        }

        return $daily;
    }

    public static function calculateMonthly($date = null)
    {
        if ($date != null) {
        } else {
            $date = new \Carbon\Carbon('now');
        }

        $beginingOfTheMonth = $date->copy()->startOfMonth();
        $endOfTheMonth = $date->copy()->endOfMonth();

        $date->daysInMonth;
        for($i = 0; $i <= $date->daysInMonth; $i ++) {
            if($i == 0) {
                Electricity::calculateDaily($beginingOfTheMonth);
            }else {
                Electricity::calculateDaily($beginingOfTheMonth->addDay());
            }
        }
    }
}