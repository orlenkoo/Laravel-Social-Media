<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/5/2016
 * Time: 3:31 PM
 */
class WebticsProject extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'project_name',
        'status',
    ];

    public function customers() {
        return $this->hasMany('Customer');
    }

    public function organizations() {
        return $this->belongsToMany('Organization', 'webtics_project_organization_mappings');
    }

    public function webticsModule() {
        return $this->belongsToMany('WebticsModule', 'webtics_project_webtics_module_assignments');
    }

    public function webticsPixelProperties()
    {
        return $this->hasMany('WebticsPixelProperty');
    }



}