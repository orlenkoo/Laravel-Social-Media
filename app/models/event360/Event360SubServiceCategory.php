<?php

class Event360SubServiceCategory extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_sub_service_categories';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_service_category_id',
        'code',
        'service_category',
        'gcs_file_url',
        'image_url',
        'status',

    ];

    public function event360ServiceCategory()
    {
        return $this->belongsTo('Event360ServiceCategory', 'event360_service_category_id');
    }

    public function event360OrganizationPrimarySubServiceCategories()
    {
        $this->hasMany('Event360OrganizationSecondaryServiceCategory');
    }

    public function event360OrganizationSecondarySubServiceCategories()
    {
        $this->hasMany('Event360OrganizationSecondarySubServiceCategory');
    }

    public function event360EnquiryRequiredSubServices()
    {
        return $this->hasMany('Event360EnquiryRequiredSubService');
    }

}