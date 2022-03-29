<?php

class Call extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = "calls";

    // Don't forget to fill this array
    protected $fillable = [
        'customer_id',
        'created_datetime',
        'agenda',
        'summary',
        'call_date',
        'assigned_to',
        'task',
        'scheduled_start_time',
        'scheduled_end_time',
        'call_with'
    ];

    // relationships

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo('Employee', 'assigned_to');
    }

    public static function getNumberOfCallsForGivenCustomer($customer){
        $call_count = is_object($customer) ? sizeof($customer->calls) : 0 ;
        return $call_count;
    }

}