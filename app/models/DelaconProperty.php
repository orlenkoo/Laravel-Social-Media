<?php

/**
 * Created by PhpStorm.
 * User: Kasun DeAlwis
 * Date: 30/12/2016
 * Time: 11:58
 */
class DelaconProperty extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'delacon_properties';

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'delacon_property_name',
        'delacon_tracking_numbers',
        'delacon_cid',
        'delacon_customer_account',
        'status',
        'created_at',
        'updated_at',
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }


}