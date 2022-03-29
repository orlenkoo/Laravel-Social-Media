<?php

class Industry extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
        'industry' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['organization_id', 'code', 'industry', 'status'];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function customers()
    {
        return $this->hasMany('Customer');
    }

}