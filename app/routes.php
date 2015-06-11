<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/authtest', array('before' => 'auth.basic', function()
{
    return View::make('hello');
}));

Route::group(array('prefix' => 'api/v1.0', 'before' => 'auth.basic'), function()
{
    /**
     * Extending Sensor resource
     */
    Route::group(array('prefix' => 'sensor'), function()
    {
        Route::get('{id}/predict', 'SensorController@predict');

        Route::get('{id}/values', 'SensorValueController@index');
        Route::get('{id}/values/{valueId}', 'SensorValueController@show');
        Route::post('{id}/values', 'SensorValueController@store');
        Route::put('{id}/values/{valueId}', 'SensorValueController@update');
        Route::delete('{id}/values/{valueId}', 'SensorValueController@destroy');
    });

    Route::resource('sensor', 'SensorController');


    Route::group(array('prefix' => 'actuator'), function()
    {
        Route::get('power', 'ActuatorController@power');
    });
    Route::resource('actuator', 'ActuatorController');
});