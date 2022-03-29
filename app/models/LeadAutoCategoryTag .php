<?php

class LeadAutoCategoryTag extends \Eloquent
{

    protected $table = "lead_auto_category_tags";

    protected $fillable = [
        'lead_id',
        'category_tag'
    ];

    // relationships

    public function lead()
    {
        return $this->belongsTo('Lead', 'lead_id');
    }

}