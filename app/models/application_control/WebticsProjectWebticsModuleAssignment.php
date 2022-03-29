<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/5/2016
 * Time: 3:31 PM
 */
class WebticsProjectWebticsModuleAssignment extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'webtics_project_id',
        'webtics_module_id'
    ];


    public static function checkAssignment($webtics_project_id,$webtics_module_id){

        $webtics_project_webtics_module_assignment = WebticsProjectWebticsModuleAssignment::where('webtics_project_id',$webtics_project_id)->where('webtics_module_id',$webtics_module_id)->first();
        if(is_object($webtics_project_webtics_module_assignment)){
            return true;
        }
        return false;
    }



}