<?php

class EmailModuleEmailCampaign extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    protected $table = 'email_module_email_campaigns';

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'campaign_id',
        'campaign_name',
        'subject',
        'from_name',
        'from_email_address',
        'status',
        'start_date_time',
        'end_date_time',
        'start_automatically',
        'email_module_email_list_id',
        'email_content',
    ];

    public static $status = array(
        'New' => 'New',
        'Active' => 'Active',
        'Paused' => 'Paused',
        'Stopped' => 'Stopped',
    );

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function campaign()
    {
        return $this->belongsTo('Campaign', 'campaign_id');
    }

    public function emailModuleEmailList()
    {
        return $this->belongsTo('EmailModuleEmailList', 'email_module_email_list_id');
    }

}