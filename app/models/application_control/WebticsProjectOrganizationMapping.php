<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/5/2016
 * Time: 3:31 PM
 */
class WebticsProjectOrganizationMapping extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'webtics_project_id',
        'organization_id',
        'status',
    ];

    public static function checkMapping($organization_id,$project_id){
        $webtics_project_webtics_module_assignment = WebticsProjectOrganizationMapping::where('webtics_project_id',$project_id)->where('organization_id',$organization_id)->where('status',1)->first();
        if(is_object($webtics_project_webtics_module_assignment)){
            return true;
        }
        return false;

    }



}