<?php

class Event360ServiceCategoryCombination extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_service_category_combinations';

    // Don't forget to fill this array
    protected $fillable = [
        'combination_name',
        'gcs_file_url',
        'image_url',
        'status',
    ];

    public function event360ServiceCategoryCombinationMappings()
    {
        return $this->hasMany('Event360ServiceCategoryCombinationMapping');
    }



}