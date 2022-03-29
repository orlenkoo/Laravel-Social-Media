<?php

class Event360VendorProfileEventType extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_profile_event_types';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_vendor_profile_id',
        'event360_event_type_id',
        'status',


    ];

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }

    public function event360EventType()
    {
        return $this->belongsTo('Event360EventType', 'event360_event_type_id');
    }




}