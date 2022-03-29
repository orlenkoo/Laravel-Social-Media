<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360VendorTestimonial extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_testimonials';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_vendor_profile_id',
        'title',
        'testimonial',
        'author',
        'date',
        'gcs_file_url',
        'image_url',
        'thumbnail_url',
    ];

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }



}