<?php

class SensorValueController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        $sensorValues = SensorValue::where('sensor_id', $id)
            ->get();

        return Response::json(array(
            'error' => false,
            'sensorValues' => $sensorValues,
            'sensor' => Sensor::find($id)
        ), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $sensorValue = new SensorValue();

        $sensorValue->value = Request::get('value');
        $sensorValue->sensor_id = Request::get('sensor_id');

        // Validation and Filtering is sorely needed!!
        // Seriously, I'm a bad person for leaving that out.

        $sensorValue->save();

        return Response::json(array(
            'error' => false,
            'sensors' => $sensorValue->toArray()),
            200
        );
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id, $valueId)
    {
        $sensorValue = SensorValue::where('sensor_id', $id)
            ->where('id', $valueId)
            ->take(1)
            ->get();

        return Response::json(array(
            'error' => false,
            'sensors' => $sensorValue
        ), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id, $valueId)
    {
        $sensorValue = SensorValue::where('sensor_id', $id)->find($valueId);

        if (Request::get('value')) {
            $sensorValue->value = Request::get('value');
        }

        $sensorValue->save();

        return Response::json(array(
            'error' => false,
            'message' => 'Sensor updated',
            'details' => $sensorValue->toArray()
        ), 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id, $valueId)
    {
        $sensorValue = SensorValue::where('sensor_id', $id)
            ->find($valueId);

        if($sensorValue) {
            $sensorValueBackup = $sensorValue;

            $sensorValue->delete();

            return Response::json(array(
                'error' => false,
                'message' => 'Sensor deleted',
                'details' => $sensorValueBackup->toArray()
            ), 200);
        }else {
            return Response::json(array(
                'error' => true,
                'message' => 'Sensor with given id not found.'
            ), 404);
        }
    }
}
