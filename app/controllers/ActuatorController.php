<?php

class ActuatorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $actuators = Actuator::orderBy('created_at');

        if(Request::get('from')) {
            $actuators->where('updated_at', '>', new DateTime(Request::get('from')));
        }

        if(Request::get('to')) {
            $actuators->where('updated_at', '<', new DateTime(Request::get('to')));
        }

        return Response::json(array(
            'error' => false,
            'sensors' => $actuators->get()
        ), 200);

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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
