<?php

class CampaignMediaChannel extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    protected $table = 'campaign_media_channels';

    // Don't forget to fill this array
    protected $fillable = [
        'campaign_id',
        'media_channel_id',
    ];

    public function campaign()
    {
        return $this->belongsTo('Campaign', 'campaign_id');
    }

    public function mediaChannel()
    {
        return $this->belongsTo('MediaChannel', 'media_channel_id');
    }

}