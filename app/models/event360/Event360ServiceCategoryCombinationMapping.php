<?php

class Event360ServiceCategoryCombinationMapping extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_service_category_combination_mappings';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_service_category_combination_id',
        'event360_service_category_id',
    ];

    public function event360ServiceCategoryCombination(){
        return $this->belongsTo('Event360ServiceCategoryCombination', 'event360_service_category_combination_id');
    }

    public function event360ServiceCategory(){
        return $this->belongsTo('Event360ServiceCategory', 'event360_service_category_id');
    }

}