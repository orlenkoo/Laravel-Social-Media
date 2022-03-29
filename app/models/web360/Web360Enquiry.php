<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class Web360Enquiry extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'web360_enquiries';

    // Don't forget to fill this array
    protected $fillable = [
        'webtics_pixel_property_id',
        'datetime',
        'enquiry_details',

    ];

    public function webticsPixelProperty()
    {
        return $this->belongsTo('WebticsPixelProperty', 'webtics_pixel_property_id');
    }




}