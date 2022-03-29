<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 31/05/2016
 * Time: 08:48
 */
class UserLevel extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'user_level',
        'status'
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function userLevelScreenPermissions()
    {
        return $this->hasMany('UserLevelScreenPermission');
    }

    public function userLevelActionPermissions()
    {
        return $this->hasMany('UserLevelActionPermission');
    }

}