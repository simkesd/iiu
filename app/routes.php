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
        /**
         * Extending Sensor resource
         */
        Route::get('{id}/predict', 'SensorController@predict');
    });
    Route::get('', 'FooController@bar');
    Route::resource('sensor', 'SensorController');
});