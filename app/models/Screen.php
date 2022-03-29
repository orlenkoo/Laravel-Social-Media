<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class Screen extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'screen_name',
        'route',
        'action',
        'status'
    ];



    public function employeeScreenPermissions()
    {
        return $this->hasMany('EmployeeScreenPermission');
    }

    public function userLevelScreenPermissions()
    {
        return $this->hasMany('UserLevelScreenPermission');
    }


}