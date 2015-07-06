<?php

class CronController extends \BaseController
{

    /**
     * Cron to be called on the 1st of the month
     * Time: 00:15
     *
     * @return Response
     */
    public function index($id)
    {
        $now = Carbon\Carbon::now();
        if ($now->day != 1) {
//            return "This crone shoud be run only on the 1st of the month! Today is " . $now->day . " .";
        }

        $beginingOfMonth = new \Carbon\Carbon('first day of this month');
        $endOfMonth = new \Carbon\Carbon('last day of this month');

        $beginingOfMonth = $beginingOfMonth->subDay()->endOfDay()->addSecond();
        $endOfMonth = $endOfMonth->endOfDay();

//        var_dump(array(
//            $beginingOfMonth->subDay()->endOfDay()->addSecond(),
//            $endOfMonth->endOfDay()
//        ));

        $actuatorValues = ActuatorValue::orderBy('created_at', 'asc')
            ->where('created_at', '>', $beginingOfMonth)
            ->where('created_at', '<', $endOfMonth)
            ->where('actuator_id', '=', $id)
            ->get();

        $periodOn = 0;
        $periodOff = 0;

//        var_dump(array(
//            $actuatorValues[0]->created_at,
//            $beginingOfMonth
//        ));

        if ($actuatorValues) {
            if ($actuatorValues[0]->value == 0) {
                //actuator was turned on on the beginning of the month
                $periodOn += $actuatorValues[0]->created_at->diffInHours($beginingOfMonth);
            } else {
                //actuator was turned off on the beginning of the month
                $periodOff += $actuatorValues[0]->created_at->diffInHours($beginingOfMonth);
            }

            $lastIndex = count($actuatorValues) - 1;

//            var_dump(array(
//                $actuatorValues[$lastIndex]->created_at,
//                $endOfMonth
//            ));

            if ($actuatorValues[$lastIndex]->value == 0) {
                //actuator was turned off on the end of the month
                $periodOff += $actuatorValues[$lastIndex]->created_at->diffInHours($endOfMonth);
            } else {
                //actuator was turned on on the end of the month
                $periodOn += $actuatorValues[$lastIndex]->created_at->diffInHours($endOfMonth);
            }

            foreach ($actuatorValues as $key => $actuatorValue) {
                if ($key == 0 || $key == $lastIndex) {
                    //already counted this period
                    continue;
                } else {
                    if ($actuatorValue->value == 0) {
                        //actuator was turned on on the beginning of the month
                        $periodOff += $actuatorValue->created_at->diffInHours($actuatorValues[$key + 1]->created_at);
                    } else {
                        //actuator was turned off on the beginning of the month
                        $periodOn += $actuatorValue->created_at->diffInHours($actuatorValues[$key + 1]->created_at);
                    }
                }
            }

        } else {
            // There are no actuator values for selected period
        }

        echo $periodOn;
        echo "<br>";
        echo $periodOff;

//        echo $actuatorValues->toJson();
    }

    public function getElectricityPrices()
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

    public function calculateCost($numOfHours, $powerConsumption, $prices, $kwSpentInMonth)
    {
        $kwSpent = $numOfHours*($powerConsumption/1000);

        if($kwSpentInMonth < 351) {
            if(($kwSpentInMonth + $kwSpent) < 351) {
                $cost = $kwSpent * $prices->zelenaZona->jednotarifno;
            }else if(($kwSpentInMonth + $kwSpent) < 1601) {
                $greenZoneCost = (351 - $kwSpentInMonth)*$prices->zelenaZona->jednotarifno;
                $blueZoneCost = ($kwSpent - (351 - $kwSpentInMonth))*$prices->plavaZona->jednotarifno;
                $cost = $greenZoneCost + $blueZoneCost;
            }else {
                $greenZoneCost = (351 - $kwSpentInMonth)*$prices->zelenaZona->jednotarifno;
                $blueZoneCost = ($kwSpent - (351 - $kwSpentInMonth))*$prices->plavaZona->jednotarifno;
                $redZoneCost = ($kwSpent (1601 - (351 - $kwSpentInMonth)))*$prices->crvenaZona->jednotarifno;
                $cost = $greenZoneCost + $blueZoneCost + $redZoneCost;
            }
        }else if ($kwSpentInMonth < 1601) {
            if(($kwSpentInMonth + $kwSpent) < 1601) {
                $cost = $kwSpent * $prices->plavaZona->jednotarifno;
            }else {
                $blueZoneCost = ($kwSpent - (351 - $kwSpentInMonth))*$prices->plavaZona->jednotarifno;
                $redZoneCost = ($kwSpent (1601 - (351 - $kwSpentInMonth)))*$prices->crvenaZona->jednotarifno;
                $cost = $blueZoneCost + $redZoneCost;
            }
        }else {
            $cost = $kwSpent * $prices->crvenaZona->jednotarifno;
        }

        return $cost;
    }

    /**
     * For CRON that runs once a day. Calculates data for day before.
     *
     * @param $id
     */
    public function calculateDay($id, $date)
    {
        if($date) {
            $date = new \Carbon\Carbon(urldecode(Request::get('date')));
        }else {
            $date = new \Carbon\Carbon('now');
        }

        $beginingOfTheDay = $date->copy()->subDay()->startOfDay();
        $endOfTheDay = $date->copy()->subDay()->endOfDay()->addSecond();

//        var_dump([$beginingOfTheDay, $endOfTheDay]);

        $actuator = Actuator::find($id);
        $actuatorValues = ActuatorValue::orderBy('created_at', 'asc')
            ->where('created_at', '>', $beginingOfTheDay)
            ->where('created_at', '<', $endOfTheDay)
            ->where('actuator_id', '=', $id)
            ->get();

        $firstOfMonth = $beginingOfTheDay->firstOfMonth();
        $periodOn = 0;
        $periodOff = 0;
        $kw_spent = 0;
        $cost = 0;
        $current_zone = 'green';
        $powerConsumption = $actuator->power_consumption/1000;

        $prices = $this->getElectricityPrices();

        if($firstOfMonth->diffInDays($beginingOfTheDay) == 0){
            $kw_spent_in_month = 0;
        }else {
            $dailyBegining = $endOfTheDay->copy()->subDay()->subDay();
            $dailyEnd = $endOfTheDay->copy()->subDay();
            $dailyElectricity = DailyElectricity::orderBy('created_at', 'acs')
                ->where('created_at', '>', $dailyBegining)
                ->where('created_at', '<', $dailyEnd)
                ->where('actuator_id', '=', $id)
                ->get();
            $kw_spent_in_month = $dailyBegining->kw_spent_in_month;
        }

        if (count($actuatorValues) > 0) {
//            echo $actuatorValues->toJson();

            if ($actuatorValues[0]->value == 0) {
                //actuator was turned on on the beginning of the day
                $periodOnInTheBeginingOfTheDay = $actuatorValues[0]->created_at->diffInHours($beginingOfTheDay);
                $periodOn += $periodOnInTheBeginingOfTheDay;
                $cost += $this->calculateCost($periodOnInTheBeginingOfTheDay, $actuator->power_consumption, $prices, $kw_spent_in_month);
                $kw_spent += $periodOnInTheBeginingOfTheDay * $powerConsumption;
            } else {
                //actuator was turned off on the beginning of the day
                $periodOff += $actuatorValues[0]->created_at->diffInHours($endOfTheDay);
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
                        $cost += $this->calculateCost($tempPeriodOn, $actuator->power_consumption, $prices, $kw_spent_in_month);
                        $kw_spent += $tempPeriodOn * $powerConsumption;
                    }
                }
            }


            if ($actuatorValues[$lastIndex]->value == 0) {
                //actuator was turned off on the end of the month
                $periodOff += $actuatorValues[$lastIndex]->created_at->diffInHours($endOfTheDay);
            } else {
                //actuator was turned on on the end of the month
                $periodOnInTheEndOfTheDay = $actuatorValues[$lastIndex]->created_at->diffInHours($endOfTheDay);
                $periodOn += $periodOnInTheEndOfTheDay;
                $cost += $this->calculateCost($periodOnInTheEndOfTheDay, $actuator->power_consumption, $prices, $kw_spent_in_month);
                $kw_spent += $periodOnInTheEndOfTheDay * $powerConsumption;
            }

        } else {
            echo 'no values for today';
            // get current state of actuator

            if($actuator->current_state = 1) {
                // if it is turned on, cost will exist
            }else {
                // if its not costs will be null
                // result will be the same as previous day
            }
        }

        var_dump(array(
            'kw_spent' => $kw_spent,
            'kw_spent_in_month' => $kw_spent + $kw_spent_in_month,
            'cost' => $cost,
            'periodOff' => $periodOff,
            'periodOn' => $periodOn
        ));

        DailyElectricity::create(array(
            'actuator_id' => $id,
            'kw_spent' => $kw_spent,
            'kw_spent_in_month' => $kw_spent_in_month + $kw_spent,
            'cost' => $cost,
            'current_zone' => $current_zone
        ));
    }


}
