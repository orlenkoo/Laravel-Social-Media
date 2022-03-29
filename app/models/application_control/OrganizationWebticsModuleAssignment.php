<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/5/2016
 * Time: 3:31 PM
 */
class OrganizationWebticsModuleAssignment extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'webtics_module_id'
    ];


    public static function checkAssignment($organization_id,$webtics_module_id){

        $organization_webtics_module_assignment = OrganizationWebticsModuleAssignment::where('organization_id',$organization_id)->where('webtics_module_id',$webtics_module_id)->first();
        if(is_object($organization_webtics_module_assignment)){
            return true;
        }
        return false;
    }




}