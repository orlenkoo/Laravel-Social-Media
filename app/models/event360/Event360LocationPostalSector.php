<?php

class Event360LocationPostalSector extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_location_postal_sectors';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_location_id',
        'postal_code',
        'status',

    ];

    public function event360Location()
    {
        return $this->belongsTo('Event360Location', 'event360_location_id');
    }


}