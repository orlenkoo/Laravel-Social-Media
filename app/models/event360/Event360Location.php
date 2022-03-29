<?php

class Event360Location extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_locations';

    // Don't forget to fill this array
    protected $fillable = [
        'location',
        'status',

    ];

    public function event360EnquiryVenueLocations()
    {
        return $this->hasMany('Event360EnquiryVenueLocation');
    }

    public function event360LocationPostalSectors()
    {
        return $this->hasMany('Event360LocationPostalSector');
    }

    public function organizations()
    {
        return $this->hasMany('Organization');
    }


}