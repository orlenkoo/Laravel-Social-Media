<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 21/09/2016
 * Time: 07:51
 */
class LeadView extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'lead_views';

    // Don't forget to fill this array
    protected $fillable = [
        'lead_id',
        'datetime',
        'viewed_by',
    ];

    public function event360Lead()
    {
        return $this->belongsTo('Lead', 'lead_id');
    }

    public static function getViewStatus($lead_id, $user_id)
    {
        $lead_view = LeadView::where('lead_id', $lead_id)
            ->where('viewed_by', $user_id)->count();
        if ($lead_view > 0) {
            return true;
        } else {
            return false;
        }
    }


}