<?php

class Event360VendorProfileVenueHighlight extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_profile_venue_highlights';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_vendor_profile_id',
        'venue_highlight',
        'status',


    ];

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }
}