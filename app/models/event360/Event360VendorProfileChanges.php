<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 13/10/2016
 * Time: 09:27
 */
class Event360VendorProfileChanges extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_profile_changes';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_vendor_profile_id',
        'changed_model',
        'changed_model_id',
        'before_snapshot',
        'after_snapshot',
        'changed_by',
        'changed_on',
        'status',
        'event360_remarks',
    ];

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }

    public function changedBy()
    {
        return $this->belongsTo('Employee', 'changed_by');
    }

}