<?php

class ActuatorController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $actuators = Actuator::orderBy('created_at');

        if (Request::get('from')) {
            $actuators->where('updated_at', '>', new DateTime(Request::get('from')));
        }

        if (Request::get('to')) {
            $actuators->where('updated_at', '<', new DateTime(Request::get('to')));
        }

        $actuators = $actuators->get();

        foreach ($actuators as $key => $actuator) {
            $actuator->latest_value = SensorValue::where('sensor_id', '=', $actuator->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return Response::json(array(
            'error' => false,
            'actuators' => $actuators
        ), 200);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $actuator = new Actuator();
        $actuator->name = Input::get('name');
        $actuator->description = Input::get('description');

        // Validation and Filtering is sorely needed!!
        // Seriously, I'm a bad person for leaving that out.

        $actuator->save();

        return Response::json(array(
            'error' => false,
            'actuators' => $actuator->toArray()),
            200
        );
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        // Make sure current user owns the requested resource
        $actuator = Actuator::where('id', $id)
            ->take(1)
            ->get();

        return Response::json(array(
            'error' => false,
            'actuator' => $actuator->first()), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    function DOMinnerHTML(DOMNode $element)
    {
        $innerHTML = "";
        $children = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    public function getElectricityPrices()
    {
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://happyfist.co:8081/',
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);

        $response = $client->get('scrape');

        $url = "http://happyfist.co:8081/scrape";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);

        $jsonResponse = json_decode($output);

        return $jsonResponse;
    }

    public function calculatePrice($id)
    {
        if (Request::get('from')) {
            $from = urldecode(Request::get('from'));
        }

        if (Request::get('to')) {
            $to = urldecode(Request::get('to'));
        }

        $actuator = Actuator::find($id);
        $actuatorValues = ActuatorValue::orderBy('created_at')->where('actuator_id', '=', $id)->get();

        $timetable = array();

        $prices = $this->getElectricityPrices();
        $electricitySpent = 0;
        $price = 0;
        $zone = '';

        foreach ($actuatorValues as $index => $actuatorValue) {
            $key = $actuatorValue->created_at->year;
            $key .= $actuatorValue->created_at->month;
            $key .= $actuatorValue->created_at->day;

            $periodOn = 0;
            $periodOff = 0;

            $dateOne = $actuatorValue->created_at;
            $dateTwo = (isset($actuatorValues[$index + 1])) ? $actuatorValues[$index + 1]->created_at : null;

            if ($actuatorValue->value == 1) {
                if ($dateTwo) {
                    $periodOn += $dateOne->diffInMinutes($dateTwo);
                    $tempPeriodOn = $dateOne->diffInMinutes($dateTwo);
                } else {
                    $periodOn += $dateOne->diffInMinutes(\Carbon\Carbon::now());
                    $tempPeriodOn = $dateOne->diffInMinutes(\Carbon\Carbon::now());
                }

                $hoursOn = ($tempPeriodOn / 60);
                $electricitySpent +=  $hoursOn * $actuator->spending;
                $electricitySpentInKW =  $electricitySpent/1000;

//                var_dump(array(
//                    'hoursOn' => $hoursOn,
//                    'KWspent' => $electricitySpentInKW,
//                    'price' => $price
//                ));

                if($electricitySpentInKW < 351) {
                    $price += $electricitySpentInKW * $prices->zelenaZona->jednotarifno;
                    $zone = 'green';
                }else if($electricitySpentInKW < 1601) {
                    $price += $electricitySpentInKW * $prices->plavaZona->jednotarifno;
                    $zone = 'blue';
                }else {
                    $price += $electricitySpentInKW * $prices->crvenaZona->jednotarifno;
                    $zone = 'red';
                }
            }
        }

        return Response::json(array('zone' => $zone, 'price' => $price));
    }


}
