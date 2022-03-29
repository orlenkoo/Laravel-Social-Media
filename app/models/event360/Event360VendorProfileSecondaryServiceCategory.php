<?php

class Event360VendorProfileSecondaryServiceCategory extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_profile_secondary_service_categories';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_vendor_profile_id',
        'event360_service_category_id',
        'status',


    ];

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }

    public function event360ServiceCategory()
    {
        return $this->belongsTo('Event360ServiceCategory', 'event360_service_category_id');
    }

    public function event360VendorProfileSecondarySubServiceCategories()
    {
        $this->hasMany('Event360VendorProfileSecondarySubServiceCategory');
    }


}