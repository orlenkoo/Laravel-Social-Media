<?php
use google\appengine\api\cloud_storage\CloudStorageTools;

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 18/05/2016
 * Time: 10:23
 */
class Event360VendorProfileController extends BaseController
{

    /*
     * update overview for a vendor profile
     */
    public function editOverview($id)
    {

        $organization = Organization::findOrFail($id);
        $organization_before_update = clone $organization;

        $validator = Validator::make($data = Input::all(), Organization::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // set location id
        $postal_code = Input::get('postal_code');
        $postal_sector = Event360LocationPostalSector::where('postal_code', '=', substr($postal_code, 0, 2))->first();
        if (is_object($postal_sector)) {
            $data['location_id'] = $postal_sector->event360_location_id;
        }

        $organization->update($data);
        $organization_after_update = clone $organization;

        if(is_object($organization->event360VendorProfile)) {
            // add change to event360_vendor_profile_changes
            $changed_data = array(
                'event360_vendor_profile_id' => $organization->event360VendorProfile->id,
                'changed_model' => 'Organization',
                'changed_model_id' => $organization->id,
                'before_snapshot' => json_encode($organization_before_update),
                'after_snapshot' => json_encode($organization_after_update),
                'changed_by' => Session::get('user-id'),
                'changed_on' => date('Y-m-d H:i:s'),
                'status' => 'Pending',
                'event360_remarks' => '',
            );

            Event360VendorProfileChanges::create($changed_data);

            return Redirect::route('event360.vendor_profile.index', array('organization_id' => $organization->id));
        }


    }

    /*
     * add new profile for vendor profile
     */
    public function addProfile()
    {

        $validator = Validator::make($data = Input::all(), Event360VendorProfile::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }


        $event360_vendor_profile = Event360VendorProfile::create($data);

        if (Input::hasFile('image_file')) {
            $image_file = Input::file('image_file');

            // generate file name
            $file_name = 'event360_vendor_logo_' . $event360_vendor_profile->id . '.' . $image_file->getClientOriginalExtension();

            $file_save_data = GCSFileHandler::saveFile($image_file, $file_name);

            $update_data = array('logo_gcs_file_url' => $file_save_data['gcs_file_url'], 'logo_image_url' => str_replace("http://", "https://", $file_save_data['image_url']));

            $event360_vendor_profile->update($update_data);
        }

        $audit_action = array(
            'action' => 'create',
            'model-id' => $event360_vendor_profile->id,
            'data' => $event360_vendor_profile
        );

        AuditTrail::addAuditEntry("Event360VendorProfile", json_encode($audit_action));

        //AuditTrail::addAuditEntry("Customer","Created -- ".json_encode($data));

        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $event360_vendor_profile->organization_id));
    }

    /*
     * update profile tab for a vendor profile
     */
    public function editProfile($id)
    {
        $event360_vendor_profile = Event360VendorProfile::findOrFail($id);
        $event360_vendor_profile_before_update = clone $event360_vendor_profile;

        $validator = Validator::make($data = Input::all(), Event360VendorProfile::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        if (Input::hasFile('image_file')) {
            $image_file = Input::file('image_file');

            // generate file name
            $file_name = 'event360_vendor_logo_' . $event360_vendor_profile->id . '.' . $image_file->getClientOriginalExtension();

            $file_save_data = GCSFileHandler::saveFile($image_file, $file_name);

            $data = array('logo_gcs_file_url' => $file_save_data['gcs_file_url'], 'logo_image_url' => str_replace("http://", "https://", $file_save_data['image_url']));


        }

        $event360_vendor_profile->update($data);
        $event360_vendor_profile_after_update = clone $event360_vendor_profile;

        // add change to event360_vendor_profile_changes
        $changed_data = array(
            'event360_vendor_profile_id' => $event360_vendor_profile->id,
            'changed_model' => 'Event360VendorProfile',
            'changed_model_id' => $event360_vendor_profile->id,
            'before_snapshot' => json_encode($event360_vendor_profile_before_update),
            'after_snapshot' => json_encode($event360_vendor_profile_after_update),
            'changed_by' => Session::get('user-id'),
            'changed_on' => date('Y-m-d H:i:s'),
            'status' => 'Pending',
            'event360_remarks' => '',
        );

        Event360VendorProfileChanges::create($changed_data);

        $audit_action = array(
            'action' => 'update',
            'model-id' => $event360_vendor_profile->id,
            'data' => $event360_vendor_profile
        );

        AuditTrail::addAuditEntry("Event360VendorProfile", json_encode($audit_action));



        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $event360_vendor_profile->organization_id));
    }

    /*
     * upload images
     */
    public function uploadImage()
    {
        $event360_vendor_profile_id = Input::get('event360_vendor_profile_id');
        $event360_vendor_service_id = Input::has('event360_vendor_service_id') ? Input::get('event360_vendor_service_id') : 0;
        $image_type = Input::get('image_type');

        $data = array(
            'event360_vendor_profile_id' => $event360_vendor_profile_id,
            'event360_vendor_service_id' => $event360_vendor_service_id,
            'image_type' => $image_type,
        );

        if ($image_type == 'Profile') {
            $file_name = 'event360_vendor_profile';
        } else if ($image_type == 'Gallery') {
            $file_name = 'event360_vendor_gallery';
        } else if ($image_type == 'Service') {
            $file_name = 'event360_vendor_service';
        }


        $vendor_profile_image = Event360VendorImage::create($data);

        if (Input::hasFile('file')) {
            $image_file = Input::file('file');

            // generate file name
            $file_name .= '_image_' . $vendor_profile_image->id . '_' . $vendor_profile_image->event360_vendor_profile_id . '.' . $image_file->getClientOriginalExtension();

            $file_save_data = GCSFileHandler::saveFile($image_file, $file_name);

            $update_data = array('gcs_file_url' => $file_save_data['gcs_file_url'], 'image_url' => str_replace("http://", "https://", $file_save_data['image_url']));

            $vendor_profile_image->update($update_data);
        }


        $audit_action = array(
            'action' => 'create',
            'model-id' => $vendor_profile_image->id,
            'data' => $vendor_profile_image
        );

        AuditTrail::addAuditEntry("Event360VendorImage", json_encode($audit_action));

        return 'Uploaded Successfully.';
    }

    /*
     * Delete vendor profile image
     */
    public function deleteImage()
    {
        $id = Input::get('id');
        $vendor_profile_image = Event360VendorImage::findOrFail($id);
        $vendor_profile_image->delete();

        $audit_action = array(
            'action' => 'delete',
            'model-id' => null,
            'data' => ''
        );

        AuditTrail::addAuditEntry("Event360VendorImage", json_encode($audit_action));

        return "Successfully Deleted.";
    }

    /*
    * add new testimonial
    */
    public function addTestimonial()
    {


        $validator = Validator::make($data = Input::all(), Event360VendorTestimonial::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $event360_vendor_testimonial = Event360VendorTestimonial::create($data);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $event360_vendor_testimonial->id,
            'data' => $event360_vendor_testimonial
        );

        AuditTrail::addAuditEntry("Event360VendorTestimonial", json_encode($audit_action));

        if (Input::hasFile('image_file')) {
            $image_file = Input::file('image_file');

            // generate file name
            $file_name = 'event360_vendor_testimonial_image_' . $event360_vendor_testimonial->id . '_' . $event360_vendor_testimonial->event360_vendor_profile_id . '.' . $image_file->getClientOriginalExtension();

            $file_save_data = GCSFileHandler::saveFile($image_file, $file_name);

            $update_data = array('gcs_file_url' => $file_save_data['gcs_file_url'], 'image_url' => str_replace("http://", "https://", $file_save_data['image_url']));

            $event360_vendor_testimonial->update($update_data);
        }


        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $event360_vendor_testimonial->event360VendorProfile->organization_id));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function updateTestimonial($id)
    {
        $testimonial = Event360VendorTestimonial::findOrFail($id);
        $testimonial_before_update = clone $testimonial;

        $validator = Validator::make($data = Input::all(), Event360VendorTestimonial::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $testimonial->update($data);
        $testimonial_after_update = clone $testimonial;

        if (Input::hasFile('image_file')) {
            $image_file = Input::file('image_file');

            // generate file name
            $file_name = 'event360_vendor_testimonial_image_' . $testimonial->id . '_' . $testimonial->event360_vendor_profile_id . '.' . $image_file->getClientOriginalExtension();

            $file_save_data = GCSFileHandler::saveFile($image_file, $file_name);

            $update_data = array('gcs_file_url' => $file_save_data['gcs_file_url'], 'image_url' => str_replace("http://", "https://", $file_save_data['image_url']));

            $testimonial->update($update_data);
        }
        // add change to event360_vendor_profile_changes
        $changed_data = array(
            'event360_vendor_profile_id' => $testimonial->event360_vendor_profile_id,
            'changed_model' => 'Event360VendorTestimonial',
            'changed_model_id' => $testimonial->id,
            'before_snapshot' => json_encode($testimonial_before_update),
            'after_snapshot' => json_encode($testimonial_after_update),
            'changed_by' => Session::get('user-id'),
            'changed_on' => date('Y-m-d H:i:s'),
            'status' => 'Pending',
            'event360_remarks' => '',
        );

        Event360VendorProfileChanges::create($changed_data);

        $audit_action = array(
            'action' => 'update',
            'model-id' =>  $testimonial->id,
            'data' => $testimonial
        );

        AuditTrail::addAuditEntry("Event360VendorTestimonial", json_encode($audit_action));

        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $testimonial->event360VendorProfile->organization_id));
    }

    /*
     * add new service
     */
    public function addService()
    {
        $validator = Validator::make($data = Input::all(), Event360VendorService::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $event360_vendor_service = Event360VendorService::create($data);

        $audit_action = array(
            'action' => 'create',
            'model-id' =>  $event360_vendor_service->id,
            'data' => $event360_vendor_service
        );

        AuditTrail::addAuditEntry("Event360VendorService", json_encode($audit_action));

        $image_type = 'Service';
        $event360_vendor_profile_id = Input::get('event360_vendor_profile_id');
        $file_name = 'event360_vendor_service';


        $data = array(
            'event360_vendor_profile_id' => $event360_vendor_profile_id,
            'event360_vendor_service_id' => $event360_vendor_service->id,
            'image_type' => $image_type,
        );

        $vendor_profile_image = Event360VendorImage::create($data);

        if (Input::hasFile('image_file')) {
            $image_file = Input::file('image_file');

            // generate file name
            $file_name .= '_image_' . $vendor_profile_image->id . '_' . $vendor_profile_image->event360_vendor_profile_id . '.' . $image_file->getClientOriginalExtension();

            $file_save_data = GCSFileHandler::saveFile($image_file, $file_name);

            $update_data = array('gcs_file_url' => $file_save_data['gcs_file_url'], 'image_url' => str_replace("http://", "https://", $file_save_data['image_url']));

            $vendor_profile_image->update($update_data);
        }

        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $event360_vendor_service->organization_id));
    }


    /**
     * Update the specified Service in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function updateService($id)
    {
        $services = Event360VendorService::findOrFail($id);
        $services_before_update = clone $services;

        $validator = Validator::make($data = Input::all(), Event360VendorService::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $services->update($data);
        $services_after_update = clone $services;

        // add change to event360_vendor_profile_changes
        $changed_data = array(
            'event360_vendor_profile_id' => $services->event360_vendor_profile_id,
            'changed_model' => 'Event360VendorService',
            'changed_model_id' => $services->id,
            'before_snapshot' => json_encode($services_before_update),
            'after_snapshot' => json_encode($services_after_update),
            'changed_by' => Session::get('user-id'),
            'changed_on' => date('Y-m-d H:i:s'),
            'status' => 'Pending',
            'event360_remarks' => '',
        );

        Event360VendorProfileChanges::create($changed_data);

        $audit_action = array(
            'action' => 'update',
            'model-id' =>  $id,
            'data' => $data
        );

        AuditTrail::addAuditEntry("Event360VendorService", json_encode($audit_action));

        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $services->event360VendorProfile->organization_id));
    }

    /*
     * add new Ad Banner
     */
    public function addAdBanner()
    {
        $validator = Validator::make($data = Input::all(), Event360VendorProfileAdBanner::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $ad_title = Input::get('ad_title');
        $from_datetime = Input::get('from_datetime');
        $to_datetime = Input::get('to_datetime');
        $ad_type = Input::get('ad_type');
        $video_url = Input::get('video_url');
        $ad_url_type = Input::get('ad_url_type');
        $ad_url = Input::get('ad_url');



        $event360_vendor_profile_id = Input::get('event360_vendor_profile_id');

        $event360_vendor_profile = Event360VendorProfile::find($event360_vendor_profile_id);

        $data = array(

            'event360_vendor_profile_id' => $event360_vendor_profile->id,
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

        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $event360_vendor_profile->organization->id));
    }



    /**
     * Update the specified ad banner in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function updateAdBanner($id)
    {
        $event360_vendor_profile_ad_banner = Event360VendorProfileAdBanner::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Event360VendorProfileAdBanner::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $ad_title = Input::get('ad_title');
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

        $event360_vendor_profile_id = Input::get('event360_vendor_profile_id');

        $event360_vendor_profile = Event360VendorProfile::find($event360_vendor_profile_id);

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

        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $event360_vendor_profile->organization->id));
    }

    /**
     * Change the status of a event360_vendor_profile_ad_banner to disable.
     *
     * @param  int $id
     * @return Response
     */
    public function disableAdBanner($id)
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

        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $event360_vendor_profile_ad_banner->event360_vendor_profile->organization->id));
    }

    /**
     * Change the status of a event360_vendor_profile_ad_banner to enable.
     *
     * @param  int $id
     * @return Response
     */
    public function enableAdBanner($id)
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


        return Redirect::route('event360.vendor_profile.index', array('organization_id' => $event360_vendor_profile_ad_banner->event360_vendor_profile->organization->id));
    }

    public static function checkEventTypeAssignments($event360_vendor_profile_id, $event360_event_type_id)
    {
        $event360_vendor_profile_event_types_count = Event360VendorProfileEventType::where('event360_vendor_profile_id', $event360_vendor_profile_id)
            ->where('event360_event_type_id', $event360_event_type_id)
            ->count();
        if ($event360_vendor_profile_event_types_count > 0) return true;
        return false;
    }

}