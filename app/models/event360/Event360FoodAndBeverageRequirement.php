<?php

class Event360FoodAndBeverageRequirement extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_food_and_beverage_requirements';

    // Don't forget to fill this array
    protected $fillable = [
        'requirement',
        'status',

    ];

    public function event360EnquiryFoodAndBeverageRequirements()
    {
        return $this->hasMany('Event360EnquiryFoodAndBeverageRequirement');
    }




}