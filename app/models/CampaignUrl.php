<?php

class CampaignUrl extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    protected $table = 'campaign_urls';

    // Don't forget to fill this array
    protected $fillable = [
        'campaign_id',
        'website_url',
        'campaign_source',
        'campaign_medium',
        'campaign_name',
        'campaign_term',
        'campaign_content',
    ];

    public function campaign()
    {
        return $this->belongsTo('Campaign', 'campaign_id');
    }

    public static function getUTMUrl($campaign_url) {
        $campaign_utm_url = $campaign_url->website_url;

        //https://www.example.com/?utm_source=google&utm_medium=cpc&utm_campaign=spring_sale&utm_term=spring&utm_content=ha%20ha%20ha

        if($campaign_url->campaign_source != '') {
            $campaign_utm_url .= "?utm_source=".urlencode($campaign_url->campaign_source);
        }

        if($campaign_url->campaign_medium != '') {
            $campaign_utm_url .= "&utm_medium=".urlencode($campaign_url->campaign_medium);
        }

        if($campaign_url->campaign_name != '') {
            $campaign_utm_url .= "&utm_campaign=".urlencode($campaign_url->campaign_name);
        }

        if($campaign_url->campaign_term != '') {
            $campaign_utm_url .= "&utm_term=".urlencode($campaign_url->campaign_term);
        }

        if($campaign_url->campaign_content != '') {
            $campaign_utm_url .= "&utm_content=".urlencode($campaign_url->campaign_content);
        }

        return $campaign_utm_url;
    }

}