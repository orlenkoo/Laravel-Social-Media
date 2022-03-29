<?php

class Event360VenueType extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_venue_types';

    // Don't forget to fill this array
    protected $fillable = [
        'venue_type',
        'gcs_file_url',
        'image_url',
        'status',

    ];




}