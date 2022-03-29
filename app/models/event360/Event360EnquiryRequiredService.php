<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360EnquiryRequiredService extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_enquiry_required_services';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_enquiry_id',
        'event360_service_category_id',

    ];


    public function event360Enquiry()
    {
        return $this->belongsTo('Event360Enquiry', 'event360_enquiry_id');
    }

    public function event360ServiceCategory()
    {
        return $this->belongsTo('Event360ServiceCategory', 'event360_service_category_id');
    }

    public function event360EnquiryRequiredSubServices()
    {
        return $this->hasMany('Event360EnquiryRequiredSubService');
    }





}