<?php

class Event360ServiceCategory extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_service_categories';

    // Don't forget to fill this array
    protected $fillable = [
        'industry_id',
        'code',
        'service_category',
        'auto_select_sub_categories',
        'gcs_file_url',
        'image_url',
        'status',

    ];

    public function industry()
    {
        return $this->belongsTo('Industry', 'industry_id');
    }

    public function googleIndustry()
    {
        return $this->belongsTo('GoogleIndustry', 'industry_id');
    }

    public function event360SubServiceCategories()
    {
        return $this->hasMany('Event360SubServiceCategory');
    }

    public function event360ServiceCategoryCombinationMappings()
    {
        return $this->hasMany('Event360ServiceCategoryCombinationMapping');
    }


}