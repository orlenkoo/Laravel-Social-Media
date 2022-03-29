<?php

class Event360VendorFoodAndBeverageRequirement extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_food_and_beverage_requirements';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_food_and_beverage_requirement_id',
        'event360_vendor_profile_id',
        'status',

    ];

    public function event360FoodAndBeverageRequirement()
    {
        return $this->belongsTo('Event360EnquiryFoodAndBeverageRequirement', 'event360_food_and_beverage_requirement_id');
    }

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }




}