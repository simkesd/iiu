<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class DailyElectricity extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'daily_electricity';

//    protected $guarded = array();
    protected $fillable = array('kw_spent_in_month', 'kw_spent', 'current_zone', 'cost', 'actuator_id');


}