<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class LeadForward extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'lead_forwards';

    // Don't forget to fill this array
    protected $fillable = [
        'lead_id',
        'email',
        'message',
        'lead_forwarded_on',
        'lead_forwarded_by',
    ];

    public function lead()
    {
        return $this->belongsTo('Lead', 'lead_id');
    }

    public function leadForwardedBy() {
        return $this->belongsTo('Employee', 'lead_forwarded_by');
    }
}