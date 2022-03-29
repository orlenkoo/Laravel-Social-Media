<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/5/2016
 * Time: 9:42 AM
 */


use Illuminate\Support\Facades\Hash;

class Event360VendorProfileAdBannersController extends BaseController
{
    /**
     * Display a listing of organizations
     *
     * @return Response
     */
    public function index()
    {
        $event360_service_categories = Event360ServiceCategory::get();
        $event360_venue_types = Event360VenueType::get();
        return View::make('event360_vendor_profile_ad_banners.index', compact('event360_service_categories', 'event360_venue_types'));
    }

    public function ajaxGetEvent360VendorProfileAdBanners(){

        $search_query = Input::get('search_query');

        $event360_vendor_profile_id = Input::get('event360_vendor_profile_id');


        if ($search_query == '') {
            $search_query = '%';
        }

        $event360_vendor_profile_ad_banners = Event360VendorProfileAdBanner::where('ad_title', 'LIKE', '%' . $search_query . '%')
        ->where('advertiser_own_ad', 1)
        ->where('event360_vendor_profile_id', $event360_vendor_profile_id);



        $event360_vendor_profile_ad_banners = $event360_vendor_profile_ad_banners->paginate(10);


        return View::make('event360_vendor_profile_ad_banners._ajax_partials.event360_vendor_profile_ad_banners_list', compact('event360_vendor_profile_ad_banners'))->render();

    }

    public function store(){

        $validator = Validator::make($data = Input::all(), Event360VendorProfileAdBanner::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $ad_title = Input::get('ad_title');
        $organization_id = Input::get('organization_id');
        $from_datetime = Input::get('from_datetime');
        $to_datetime = Input::get('to_datetime');
        $ad_type = Input::get('ad_type');
        $video_url = Input::get('video_url');
        $ad_url_type = Input::get('ad_url_type');
        $ad_url = Input::get('ad_url');

        $advertiser_own_ad = 1;
        if(!Input::has('advertiser_own_ad')){
            $advertiser_own_ad = 0;
        }

        $event360_vendor_profile_id = Event360VendorProfile::where('organization_id', $organization_id)
            ->pluck('id');

        $data = array(

            'event360_vendor_profile_id' => $event360_vendor_profile_id ,
            'ad_title' => $ad_title,
            'from_datetime' =>  $from_datetime,
            'to_datetime' => $to_datetime,
            'advertiser_own_ad' => 1,
            'ad_type' => $ad_type,
            'video_url' => $video_url,
            'ad_url_type' => $ad_url_type,
            'ad_url' => $ad_url,
            'status' => 1,
        );

        $event360_vendor_profile_ad_banner = Event360VendorProfileAdBanner::create($data);

        if (Input::hasFile('image_file')) {
            $image_file = Input::file('image_file');

            // generate file name
            $file_name = 'event360_vendor_testimonial_image_' . $event360_vendor_profile_ad_banner->id . '_' . $event360_vendor_profile_ad_banner->event360_vendor_profile_id . '.' . $image_file->getClientOriginalExtension();

            $file_save_data = GCSFileHandler::saveFile($image_file, $file_name);

            $update_data = array('gcs_file_url' => $file_save_data['gcs_file_url'], 'image_url' => $file_save_data['image_url']);

            $event360_vendor_profile_ad_banner->update($update_data);
        }



        $audit_action = array(
            'action' => 'create',
            'model-id' => $event360_vendor_profile_ad_banner->id,
            'data' => $data
        );
        AuditTrail::addAuditEntry("Event360VendorProfileAdBanner", json_encode($audit_action));

        return Redirect::route('event360_vendor_profile_ad_banners.index');
    }

    public function update($id){


        $event360_vendor_profile_ad_banner = Event360VendorProfileAdBanner::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Event360VendorProfileAdBanner::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $ad_title = Input::get('ad_title');
        $organization_id = Input::get('organization_id');
        $from_datetime = Input::get('from_datetime');
        $to_datetime = Input::get('to_datetime');
        $ad_type = Input::get('ad_type');
        $video_url = Input::get('video_url');
        $ad_url_type = Input::get('ad_url_type');
        $ad_url = Input::get('ad_url');

        $advertiser_own_ad = 1;
        if(!Input::has('advertiser_own_ad')){
            $advertiser_own_ad = 0;
        }

        $event360_vendor_profile_id = Event360VendorProfile::where('organization_id', $organization_id)
            ->pluck('id');

        $data = array(

            'event360_vendor_profile_id' => $event360_vendor_profile_id ,
            'ad_title' => $ad_title,
            'from_datetime' =>  $from_datetime,
            'to_datetime' => $to_datetime,
            'advertiser_own_ad' => $advertiser_own_ad,
            'ad_type' => $ad_type,
            'video_url' => $video_url,
            'ad_url_type' => $ad_url_type,
            'ad_url' => $ad_url,
        );


        $event360_vendor_profile_ad_banner->update($data);


        syslog(LOG_INFO,'Input::hasFile(image_file) -- '.Input::hasFile('image_file'));

        if (Input::hasFile('image_file')) {
            $image_file = Input::file('image_file');

            // generate file name
            $file_name = 'event360_vendor_testimonial_image_' . $event360_vendor_profile_ad_banner->id . '_' . $event360_vendor_profile_ad_banner->event360_vendor_profile_id . '.' . $image_file->getClientOriginalExtension();

            $file_save_data = GCSFileHandler::saveFile($image_file, $file_name);

            $update_data = array('gcs_file_url' => $file_save_data['gcs_file_url'], 'image_url' => $file_save_data['image_url']);

            $event360_vendor_profile_ad_banner->update($update_data);
        }




        $audit_action = array(
            'action' => 'create',
            'model-id' => $event360_vendor_profile_ad_banner->id,
            'data' => $data
        );

        AuditTrail::addAuditEntry("Event360VendorProfileAdBanner", json_encode($audit_action));


        return Redirect::route('event360_vendor_profile_ad_banners.index');
    }

    /**
     * Change the status of a event360_vendor_profile_ad_banner to disable.
     *
     * @param  int $id
     * @return Response
     */
    public function disable($id)
    {
        $event360_vendor_profile_ad_banner = Event360VendorProfileAdBanner::findOrFail($id);
        $data = array('status' => 0);
        $event360_vendor_profile_ad_banner->update($data);

        $audit_action = array(
            'action' => 'disable',
            'model-id' => $event360_vendor_profile_ad_banner->id,
            'data' => $event360_vendor_profile_ad_banner
        );

        AuditTrail::addAuditEntry("Event360VendorProfileAdBanner", json_encode($audit_action));

        return Redirect::route('event360_vendor_profile_ad_banners.index');
    }

    /**
     * Change the status of a event360_vendor_profile_ad_banner to enable.
     *
     * @param  int $id
     * @return Response
     */
    public function enable($id)
    {
        $event360_vendor_profile_ad_banner = Event360VendorProfileAdBanner::findOrFail($id);
        $data = array('status' => 1);
        $event360_vendor_profile_ad_banner->update($data);


        $audit_action = array(
            'action' => 'enable',
            'model-id' => $event360_vendor_profile_ad_banner->id,
            'data' => $event360_vendor_profile_ad_banner
        );

        AuditTrail::addAuditEntry("Event360VendorProfileAdBanner", json_encode($audit_action));


        return Redirect::route('event360_vendor_profile_ad_banners.index');
    }
}