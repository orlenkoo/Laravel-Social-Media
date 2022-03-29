<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 06/05/2018
 * Time: 08:48
 */
class Web360ModuleEmployeeAssignment extends \Eloquent
{

    protected $table = "web360_module_employee_assignments";

    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'web360_module_id',
        'employee_id',
        'enabled_date_time',
        'disabled_date_time',
        'status',
        'enabled_by',
        'disabled_by',
    ];

    public static $status = array(
        'Enabled' => 'Enabled',
        'Disabled' => 'Disabled',
    );

    public function web360Module() {
        $this->belongsTo('Web360Module', 'web360_module_id');
    }

    public function assignedTo() {
        $this->belongsTo('Employee', 'employee_id');
    }

    public function enabledBy() {
        $this->belongsTo('Employee', 'enabled_by');
    }

    public function disabledBy() {
        $this->belongsTo('Employee', 'disabled_by');
    }



}