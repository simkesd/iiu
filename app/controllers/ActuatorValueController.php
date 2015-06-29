<?php

class ActuatorValueController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
        $actuatorValues = ActuatorValue::where('actuator_id', $id);

        if (Request::get('from')) {
            $from = urldecode(Request::get('from'));
            $actuatorValues->where('created_at', '>', $from);
        }


        if (Request::get('to')) {
            $to = urldecode(Request::get('to'));
            $actuatorValues->where('created_at', '<', $to);
        }

        $actuatorValues = $actuatorValues->get();

        $actuator = Actuator::find($id);

        $periodOn = 0;
        $periodOff = 0;
        foreach($actuatorValues as $key => $actuatorValue) {
            $dateOne =  $actuatorValue->created_at;
            $dateTwo = (isset($actuatorValues[$key + 1])) ? $actuatorValues[$key + 1]->created_at : null;

//            var_dump(array($dateOne, $dateTwo));

            if($actuatorValue->value == 0) {
                if($dateTwo) {
                    $periodOff += $dateOne->diffInMinutes($dateTwo);
                }else {
                    $periodOff += $dateOne->diffInMinutes(\Carbon\Carbon::now());
                }
            }else {
                if($dateTwo) {
                    $periodOn += $dateOne->diffInMinutes($dateTwo);
                }else {
                    $periodOn += $dateOne->diffInMinutes(\Carbon\Carbon::now());
                }
            }
        }

        return Response::json(array(
            'error' => false,
            'actuatorValues' => $actuatorValues,
            'latest_value' => ActuatorValue::where('actuator_id', '=', $actuator->id)
                ->orderBy('created_at', 'desc')
                ->first(),
            'actuator' => Actuator::find($id),
            'periodOn' => $periodOn,
            'periodOff' => $periodOff
        ), 200);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($id)
	{
        $actuatorValue = new ActuatorValue();

        $actuatorValue->value = Request::get('value');
        $actuatorValue->actuator_id = $id;

        // Validation and Filtering is sorely needed!!
        // Seriously, I'm a bad person for leaving that out.

        $actuatorValue->save();

        return Response::json(array(
            'error' => false,
            'actuators' => $actuatorValue->toArray()),
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
        $actuatorValue = ActuatorValue::where('actuator_id', $id)
            ->where('id', $valueId)
            ->take(1)
            ->get();

        return Response::json(array(
            'error' => false,
            'actuator' => $actuatorValue
        ), 200);
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, $valueId)
	{
        $actuatorValue = ActuatorValue::where('actuator_id', $id)->find($valueId);

        if (Request::get('value')) {
            $actuatorValue->value = Request::get('value');
        }

        $actuatorValue->save();

        return Response::json(array(
            'error' => false,
            'message' => 'Actuator updated',
            'details' => $actuatorValue->toArray()
        ), 200);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, $valueId)
	{
        $actuatorValue = ActuatorValue::where('actuator_id', $id)
            ->find($valueId);

        if($actuatorValue) {
            $actuatorValueBackup = $actuatorValue;

            $actuatorValue->delete();

            return Response::json(array(
                'error' => false,
                'message' => 'Actuator deleted',
                'details' => $actuatorValueBackup->toArray()
            ), 200);
        }else {
            return Response::json(array(
                'error' => true,
                'message' => 'Actuator with given id not found.'
            ), 404);
        }
	}


}
