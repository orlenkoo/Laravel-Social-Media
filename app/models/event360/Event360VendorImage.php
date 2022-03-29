<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360VendorImage extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_images';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_vendor_profile_id',
        'image_type',
        'gcs_file_url',
        'image_url',
        'thumbnail_url',
        'event360_vendor_service_id',

    ];

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }

    public function event360VendorService()
    {
        return $this->belongsTo('Event360VendorService', 'event360_vendor_service_id');
    }

    public static function getVendorImagesByType($event360_vendor_profile_id, $image_type,$event360_vendor_service_id)
    {
        $vendor_images = Event360VendorImage::where('event360_vendor_profile_id', $event360_vendor_profile_id)
            ->where('event360_vendor_service_id', $event360_vendor_service_id)
            ->where('image_type', $image_type)
            ->get();

        return $vendor_images;
    }

}