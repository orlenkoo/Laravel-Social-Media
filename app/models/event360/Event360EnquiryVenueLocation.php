<?php

class Event360EnquiryVenueLocation extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_enquiry_venue_locations';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_enquiry_id',
        'event360_location_id',

    ];

    public function event360Enquiry()
    {
        return $this->belongsTo('Event360Enquiry', 'event360_enquiry_id');
    }

    public function event360Location()
    {
        return $this->belongsTo('Event360Location', 'event360_location_id');
    }




}