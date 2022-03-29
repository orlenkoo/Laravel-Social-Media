<?php

/**
 * Created by PhpStorm.
 * User: Kasun
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360EventPlannerProfile extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_event_planner_profiles';

    // Don't forget to fill this array
    protected $fillable = [
        'salutation',
        'given_name',
        'surname',
        'company_name',
        'job_title',
        'email',
        'phone_number',
        'password',
        'sign_up_date',
        'status',
        'first_time_activation',
        'password_reset_token',
    ];

    public function event360MessengerThreads()
    {
        return $this->hasMany('Event360MessengerThread');
    }

    public function event360Enquiries()
    {
        return $this->hasMany('Event360Enquiry');
    }

    public function getEventPlannerName(){

        return $this->given_name . ' ' . $this->surname;
    }


}