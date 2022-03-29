<?php

class LandingPage extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = "landing_pages";

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'page_name',
        'url',
        'status',
        'campaign_id',
        'ftp_host',
        'ftp_port',
        'ftp_user_name',
        'ftp_password',
        'ftp_path',
        'landing_page_html',
        'landing_page_css',
    ];


    public static $status = array(
        'Pending' => 'Pending',
        'Published' => 'Published',
        'Edited' => 'Edited'
    );

    // relationships

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function campaign()
    {
        return $this->belongsTo('Campaign', 'campaign_id');
    }

}