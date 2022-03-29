<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 06/05/2018
 * Time: 08:48
 */
class Web360Module extends \Eloquent
{

    protected $table = "web360_modules";

    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'module_name',
        'module_rate',
        'module_rate_base',
        'status'
    ];

    public static $module_rate_bases = array(
        'Per User' => 'Per User',
        'Per Module' => 'Per Module',
    );

    public function web360ModuleOrganizationAssignments() {
        $this->hasMany('Web360ModuleOrganizationAssignment');
    }

    public function web360ModuleEmployeeAssignments() {
        $this->hasMany('Web360ModuleEmployeeAssignment');
    }



}