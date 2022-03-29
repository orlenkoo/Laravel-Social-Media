<?php

class Event360VendorProfilePrimarySubServiceCategory extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_profile_primary_sub_service_categories';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_vendor_profile_id',
        'event360_sub_service_category_id',
        'status',


    ];

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }

    public function event360SubServiceCategory()
    {
        return $this->belongsTo('Event360SubServiceCategory', 'event360_sub_service_category_id');
    }




}