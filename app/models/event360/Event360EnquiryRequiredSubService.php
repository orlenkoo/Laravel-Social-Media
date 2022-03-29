<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360EnquiryRequiredSubService extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_enquiry_required_sub_services';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_enquiry_required_service_id',
        'event360_sub_service_category_id',
        'event360_remarks',
        'event360_remarks_entered_by',

    ];


    public function event360EnquiryRequiredService()
    {
        return $this->belongsTo('Event360EnquiryRequiredService', 'event360_enquiry_required_service_id');
    }

    public function event360SubServiceCategory()
    {
        return $this->belongsTo('Event360SubServiceCategory', 'event360_sub_service_category_id');
    }




}