<?php

class Event360EventType extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_event_types';

    // Don't forget to fill this array
    protected $fillable = [
        'event_type',
        'gcs_file_url',
        'image_url',
        'status',

    ];

    public function event360Enquiry()
    {
        return $this->hasMany('Event360Enquiry');
    }


}