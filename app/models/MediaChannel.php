<?php

class MediaChannel extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    protected $table = 'media_channels';

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'media_channel',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function campaignMediaChannels()
    {
        return $this->hasMany('CampaignMediaChannel');
    }

    public static function getMediaChannelsFilters()
    {
        $organization_id = Session::get('user-organization-id');

        $media_channels = MediaChannel::select('media_channel','id')
            ->where('organization_id', $organization_id)
            ->where('status', 1)
            ->get();

        return json_encode($media_channels);
    }

}