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

        foreach($actuators as $key => $actuator) {
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
            'timeout'  => 2.0,
        ]);

        $response = $client->get('scrape');
//        foreach ($response->getHeaders() as $name => $values) {
//            echo $name . ': ' . implode(', ', $values) . "\r\n";
//        }

//        libxml_use_internal_errors(true);
        $url = "http://happyfist.co:8081/scrape";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);

        $jsonResponse = json_decode($output);

        return $jsonResponse;
    }


}
