<?php

class SensorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $sensors = Sensor::all();

        return Response::json(array(
            'error' => false,
            'sensors' => $sensors
        ), 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $sensor = new Sensor();
        $sensor->name = Request::get('name');
        $sensor->description = Request::get('description');

        // Validation and Filtering is sorely needed!!
        // Seriously, I'm a bad person for leaving that out.

        $sensor->save();

        return Response::json(array(
            'error' => false,
            'sensors' => $sensor->toArray()),
            200
        );
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        // Make sure current user owns the requested resource
        $sensor = Sensor::where('id', $id)
            ->take(1)
            ->get();

        return Response::json(array(
            'error' => false,
            'sensors' => $sensor->toArray()),
            200
        );
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $sensor = Sensor::find($id);

        if ( Request::get('name') )
        {
            $sensor->name = Request::get('name');
        }

        if ( Request::get('description') )
        {
            $sensor->description = Request::get('description');
        }

        $sensor->save();

        return Response::json(array(
            'error' => false,
            'message' => 'Sensor updated',
            'details' => $sensor->toArray()
        ), 200);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $sensor = Sensor::find($id);
        $sensorBackup = $sensor;

        $sensor->delete();

        return Response::json(array(
            'error' => false,
            'message' => 'Sensor deleted',
            'details' => $sensorBackup->toArray()
        ), 200);
	}

    public function predict($id)
    {
        return Response::json(array(
            'error' => false,
            'message' => 'Sensor prediction for ' . $id,
        ), 200);
    }

}
