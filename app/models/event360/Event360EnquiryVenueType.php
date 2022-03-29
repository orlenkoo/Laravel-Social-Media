<?php

class Event360EnquiryVenueType extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_enquiry_venue_types';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_enquiry_id',
        'event360_venue_type_id',

    ];

    public function event360Enquiry()
    {
        return $this->belongsTo('Event360Enquiry', 'event360_enquiry_id');
    }

    public function event360VenueType()
    {
        return $this->belongsTo('Event360VenueType', 'event360_venue_type_id');
    }




}