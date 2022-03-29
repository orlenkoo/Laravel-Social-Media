<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 06/05/2018
 * Time: 08:48
 */
class Web360ModuleOrganizationAssignment extends \Eloquent
{

    protected $table = "web360_module_organization_assignments";

    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'web360_module_id',
        'organization_id',
        'enabled_date_time',
        'disabled_date_time',
        'status',
        'enabled_by',
        'disabled_by',
        'assigned_employee_count',
    ];

    public static $status = array(
        'Enabled' => 'Enabled',
        'Disabled' => 'Disabled',
    );

    public function web360Module() {
        $this->belongsTo('Web360Module', 'web360_module_id');
    }

    public function organization() {
        $this->belongsTo('Organization', 'organization_id');
    }

    public function enabledBy() {
        $this->belongsTo('Employee', 'enabled_by');
    }

    public function disabledBy() {
        $this->belongsTo('Employee', 'disabled_by');
    }



}