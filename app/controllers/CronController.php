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

    /**
     * For CRON that runs once a day. Calculates data for day before.
     *
     * @param null $date
     */
    public function calculate($date = null)
    {
        $date = new \Carbon\Carbon();
        $firstOfMonth = $date->firstOfMonth();
        $res = Services\Electricity::calculateDaily($firstOfMonth);
        return Response::json($res);
    }



}
