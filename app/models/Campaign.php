<?php

class Campaign extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    protected $table = 'campaigns';

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'campaign_name',
        'campaign_content',
        'call_tracking_number',
        'cost',
        'start_date',
        'end_date',
        'status',
        'point_of_contact',
    ];

    public static $campaign_status = array(
        1 => 'Active',
        0 => 'Suspended',
    );

    /*
     * Following campaigns should be auto assigned to leads
     */
    public static $auto_tagged_campaigns = [
        'Direct Website',           // Source = Direct + Medium = None
        'Google Organic Campaign',  // Source contains “Google” +  Medium contains “Referral”
        'Google Ad Campaign',       // There is GLCID
                                    // Source contains “Google” + Source , OR
                                    // “Tracking Number” in Calls Submission Data = “Tracking Number” in Campaign, see next slide)
        'Facebook Ad Campaign',     // Source = “Facebook” / “Instagram” + Medium = “CPC” / “CPM”)
        'Facebook Referral',        // Source contains “Facebook” , Medium contains “Referral” )
        'Newsletter Campaign',      // (Source = “Newsletter”)
        'EDM Campaign',             // (Source = “EDM”)

    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function leads()
    {
        return $this->hasMany('Lead');
    }

    public function campaignMediaChannels()
    {
        return $this->hasMany('CampaignMediaChannel');
    }

    public function campaignUrls()
    {
        return $this->hasMany('CampaignUrl');
    }

    public static function getAllMediaChannelsForGivenCampaign($campaign_id){

        $campaign_media_channels = CampaignMediaChannel::where('campaign_id', $campaign_id)
            ->lists('media_channel_id');

        return $campaign_media_channels;
    }

    public static function getCampaign($utm_campaign,$utm_content,$utm_source,$organization_id){

        $media_channels_ids = MediaChannel::where('media_channel',$utm_source)
            ->where('organization_id',$organization_id)
            ->lists('id');

        $campaigns_ids = Campaign::where('campaign_name', $utm_campaign)
            ->where('organization_id', $organization_id)
            ->where('campaign_content', $utm_content)
            ->lists('id');

        $campaign = CampaignMediaChannel::whereIn('media_channel_id',$media_channels_ids)
            ->whereIn('campaign_id',$campaigns_ids)
            ->first();

        if(is_object($campaign)){
            return $campaign->campaign_id;
        }else{
            return null;
        }
    }

    public static function getCampaignIDForLeadTagging($utm_campaign,$utm_content,$organization_id){

        $campaign = Campaign::where('campaign_name', $utm_campaign)
            ->where('organization_id', $organization_id)
            ->where('campaign_content', $utm_content)
            ->first();

        if(is_object($campaign)){
            return $campaign->id;
        }else{
            return null;
        }
    }

    public static function getCampaignForLeadAutoTagging($organization_id, $field_type, $field_value){

        $campaign_name = DB::table('campaigns')
            ->where($field_type, $field_value)
            ->where('organization_id', $organization_id)
            ->pluck('campaign_name');

        return $campaign_name;

    }

    public static function getCampaignListForSelect(){

        $organization_id = Session::get('user-organization-id');
        $campaigns = Campaign::where('organization_id',$organization_id)->lists('campaign_name','id');

        return $campaigns;
    }
}