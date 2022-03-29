<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class LeadAssignment extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'lead_assignments';

    // Don't forget to fill this array
    protected $fillable = [
        'lead_id',
        'assigned_to',
        'assigned_datetime',
    ];

    public function lead()
    {
        return $this->belongsTo('Lead', 'lead_id');
    }


    public function leadAssignedTo()
    {
        return $this->belongsTo('Employee', 'assigned_to');
    }

}