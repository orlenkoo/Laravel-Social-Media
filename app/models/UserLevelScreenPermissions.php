<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 18/05/2016
 * Time: 07:48
 */
class UserLevelScreenPermissions extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'user_level_id',
        'screen_id',
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function userLevel()
    {
        return $this->belongsTo('UserLevel', 'user_level_id');
    }

    public function screen()
    {
        return $this->belongsTo('Screen', 'screen_id');
    }

}