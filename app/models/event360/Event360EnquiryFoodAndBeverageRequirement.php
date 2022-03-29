<?php

class Event360EnquiryFoodAndBeverageRequirement extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_enquiry_food_and_beverage_requirements';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_enquiry_id',
        'event360_food_and_beverage_requirement_id',

    ];

    public function event360Enquiry()
    {
        return $this->belongsTo('Event360Enquiry', 'event360_enquiry_id');
    }

    public function event360FoodAndBeverageRequirement()
    {
        return $this->belongsTo('Event360FoodAndBeverageRequirement', 'event360_food_and_beverage_requirement_id');
    }




}