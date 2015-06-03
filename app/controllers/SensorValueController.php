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
        if ($id) {
            $sensorValues = SensorValue::where('sensor_id', $id)
                ->get();

            return Response::json(array(
                'error' => false,
                'sensors' => $sensorValues
            ), 200);
        } else {
            return Response::json(array(
                'error' => true,
                'message' => 'There is no sensor with given id.'
            ), 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
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


}
