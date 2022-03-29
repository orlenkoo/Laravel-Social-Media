<?php

/**
 * Created by PhpStorm.
 * User: Kasun DeAlwis
 * Date: 27/12/2016
 * Time: 13:08
 */
class Event360VendorProfileAdBanner extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_profile_ad_banners';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_vendor_profile_id',
        'ad_title',
        'from_datetime',
        'to_datetime',
        'gcs_file_url',
        'image_url',
        'advertiser_own_ad',
        'ad_type',
        'video_url',
        'ad_url_type',
        'ad_url',
        'last_display_date_time',
        'status',
    ];

    public static $ad_type =  array(
        'Image' => 'Image',
        'Video' => 'Video'
    );

    public static $ad_url_type =  array(
        'Event360 Profile' => 'Event360 Profile',
        'Other' => 'Other'
    );

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }





    public static function getAdBannerForVendorProfile($event360_vendor_profile, $primary_service_category, $venue_type, $vendor_type) {

        /*
         * Logic: The primary service category or venue type is passed to this function along with the vendor type
         * the ad banner for that condition is taken from the ad banners table. The queue is by the last ad display time
         * after getting the ad banner update the last ad display time with current time stamp to send it to the back of
         * the queue.
         * Amendment: If its advertiser show that advertisers own ad banners on the profile
         */
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');


        $ad_banner_data = array();

        $query_builder = Event360VendorProfileAdBanner::where('status', 1);


        $query_builder->where('advertiser_own_ad', 1)
                ->where('event360_vendor_profile_id', $event360_vendor_profile->id);


        $event360_vendor_profile_ad_banner = $query_builder
            ->where('from_datetime', '<', $datetime)
            ->where('to_datetime', '>', $datetime)
            ->orderBy('last_display_date_time')
            ->first();

        syslog(LOG_INFO, '$event360_vendor_profile_ad_banner -- '. json_encode($event360_vendor_profile_ad_banner));

        if(is_object($event360_vendor_profile_ad_banner)) {
            // update the last display time for this ad to send it to back of the queue
            $data_event360_vendor_profile_ad_banner = array(
                'last_display_date_time' => $datetime
            );
            $event360_vendor_profile_ad_banner->update($data_event360_vendor_profile_ad_banner);
        }



        $slug = $event360_vendor_profile_ad_banner->event360VendorProfile->organization->slug;

        $profile_url = Event360VendorProfile::generateProfileUrl($slug, $vendor_type);

        $ad_banner_data['event360_vendor_profile_ad_banner'] = $event360_vendor_profile_ad_banner;
        $ad_banner_data['profile_url'] = $profile_url;

        return $ad_banner_data;
    }

}