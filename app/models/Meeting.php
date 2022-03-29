<?php

class Meeting extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = "meetings";

    // Don't forget to fill this array
    protected $fillable = [
        'customer_id',
        'created_datetime',
        'agenda',
        'venue',
        'summary',
        'meeting_date',
        'assigned_to',
        'task',
        'scheduled_start_time',
        'scheduled_end_time',
        'meeting_person',
        'meeting_status',
    ];

    public static $meeting_status = [
        'start' => 'Started',
        'stop' => 'Stopped',
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

    public function meetingStatusLogs()
    {
        return $this->hasMany('MeetingStatusLog');
    }

    public static function getNumberOfMeetingsForGivenCustomer($customer){
        $meeting_count = is_object($customer) ? sizeof($customer->meetings) : 0 ;
        return $meeting_count;
    }
}