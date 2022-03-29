<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class LeadRatingUpdate extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'lead_rating_updates';

    // Don't forget to fill this array
    protected $fillable = [
        'lead_id',
        'lead_rating',
        'lead_rating_updated_on',
        'lead_rating_updated_by',
    ];

    public function lead()
    {
        return $this->belongsTo('Lead', 'lead_id');
    }


    public function leadRatingUpdatedBy()
    {
        return $this->belongsTo('Employee', 'lead_rating_updated_by');
    }

}