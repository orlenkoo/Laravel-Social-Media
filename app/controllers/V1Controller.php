<?php
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

class V1Controller extends BaseController
{

    public function homeQuickAccess()
    {
        $organization = session::get('user-organization-id');

        if(Request::url() != "http://elegant-cipher-95902.appspot.com") {
            date_default_timezone_set("Asia/Singapore");
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d', strtotime("+30 days"));

                return View::make('v1.home_page.quick_access', compact('from_date', 'to_date','organization'));

        } else {
            return Redirect::to('http://webtics.biz');
        }
    }

    /**
     * Display Screens
     *
     * @return Response
     */
    public function index()
    {
//        $designations = DB::table('designations')->where('organization_id', Session::get('user-organization-id'))->orderBy('designation', 'asc')->lists('designation', 'id');
        $organization_id = Session::get('user-organization-id');
        $organization = Organization::findOrFail($organization_id);
        $event360_vendor_profile = $organization->event360VendorProfile;
        $event360_venue_types = DB::table('event360_venue_types')->orderBy('venue_type', 'asc')->lists('venue_type', 'id');
        $event360_event_types = Event360EventType::all();
        if(is_object($event360_vendor_profile)) {
            $event360_vendor_profile_venue_highlights = Event360VendorProfileVenueHighlight::where('event360_vendor_profile_id', $event360_vendor_profile->id)->get();
        } else {
            $event360_vendor_profile_venue_highlights = array();
        }

        return View::make('v1.my_account.index', compact('organization', 'event360_vendor_profile',  'event360_venue_types', 'event360_event_types', 'event360_vendor_profile_venue_highlights'));
    }

    /*
     * retrieve Vendor Profile Images
     */
    public function retrieveImages()
    {
        $image_type = Input::get('image_type');
        $allowed_number_of_images = Input::get('allowed_number_of_images');
        $event360_vendor_profile_id = Input::get('event360_vendor_profile_id');
        $event360_vendor_service_id = Input::has('event360_vendor_service_id') ? Input::get('event360_vendor_service_id') : 0;

        $images = Event360VendorImage::getVendorImagesByType($event360_vendor_profile_id, $image_type, $event360_vendor_service_id);

        if ($image_type == 'Gallery') {
            return View::make('v1.my_account._ajax_partials.gallery_images_grid', compact('image_type', 'allowed_number_of_images', 'event360_vendor_profile_id', 'images'))->render();
        } else if ($image_type == 'Profile') {
            return View::make('v1.my_account._ajax_partials.profile_images_grid', compact('image_type', 'allowed_number_of_images', 'event360_vendor_profile_id', 'images'))->render();
        } else if ($image_type == 'Service') {
            return View::make('v1.my_account._ajax_partials.service_images_grid', compact('image_type', 'allowed_number_of_images', 'event360_vendor_profile_id', 'images'))->render();
        }
    }

    public function getAjaxTestimonials()
    {
        $event360_vendor_profile_id = Input::get('event360_vendor_profile_id');
        $search_query = Input::get('search_query');
        if ($search_query == '') {
            $search_query = '%';
        }
        $testimonials = Event360VendorTestimonial::where('event360_vendor_profile_id', $event360_vendor_profile_id)->where('title', 'LIKE', '%' . $search_query . '%')->orderby('id', 'DESC')->paginate(10);

        return View::make('v1.my_account._ajax_partials.testimonials_list', compact('testimonials'))->render();
    }

    public function getAjaxServices()
    {
        $event360_vendor_profile_id = Input::get('event360_vendor_profile_id');
        syslog(LOG_INFO, '$event360_vendor_profile_id -- ' . $event360_vendor_profile_id);
        $search_query = Input::get('search_query');
        if ($search_query == '') {
            $search_query = '%';
        }
        $services = Event360VendorService::where('event360_vendor_profile_id', $event360_vendor_profile_id)->where('service', 'LIKE', '%' . $search_query . '%')->orderby('service')->paginate(10);

        return View::make('v1.my_account._ajax_partials.services_list', compact('services', 'event360_vendor_profile_id'))->render();
    }

    public function getAjaxAdBanners()
    {
        $event360_vendor_profile_id = Input::get('event360_vendor_profile_id');
        syslog(LOG_INFO, '$event360_vendor_profile_id -- ' . $event360_vendor_profile_id);
        $search_query = Input::get('search_query');
        if ($search_query == '') {
            $search_query = '%';
        }
        $event360_vendor_profile_ad_banners = Event360VendorProfileAdBanner::where('event360_vendor_profile_id', $event360_vendor_profile_id)->where('ad_title', 'LIKE', '%' . $search_query . '%')->orderby('ad_title')->paginate(10);

        return View::make('v1.my_account._ajax_partials.event360_vendor_profile_ad_banners_list', compact('event360_vendor_profile_ad_banners', 'event360_vendor_profile_id'))->render();
    }

    public function web360PixelReport()
    {
        date_default_timezone_set("Asia/Singapore");
        $from_date = date('Y-m-d',strtotime("-1 month"));
        $to_date = date('Y-m-d');
        return View::make('v1.web360.website_report.index',compact('from_date','to_date'))->render();
    }

    // Engagement Metric Report
    public function getEngagementMetricReport(){

        header('Access-Control-Allow-Origin: *');

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date'))
        {
            $start_date = Input::get('start_date');
        }
        if(Input::has('end_date'))
        {
            $end_date = Input::get('end_date');
        }

        $webtics_pixel_property = Input::get('webtics_pixel_property');

        $widget_data_generator = new WidgetDataGenerator();
        $engagement_metric_report_details = $widget_data_generator->getWeb360EngagementMetricReport($start_date,$end_date,$webtics_pixel_property);

        return View::make('v1.web360.website_report._ajax_partials.engagement_metric_table', compact('engagement_metric_report_details'))->render();
    }

    // ROI metric Report
    public function getROIMetricReport(){

        header('Access-Control-Allow-Origin: *');

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date'))
        {
            $start_date = Input::get('start_date');
        }
        if(Input::has('end_date'))
        {
            $end_date = Input::get('end_date');
        }

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $webtics_pixel_property = Input::get('webtics_pixel_property');

        $widget_data_generator = new WidgetDataGenerator();
        $roi_metrics_details = $widget_data_generator->getWeb360ROIMetricReport($start_date,$end_date,$webtics_pixel_property,$organization_id);

        return View::make('v1.web360.website_report._ajax_partials.roi_metric_table',compact('roi_metrics_details'))->render();
    }

    public function leadsManagement()
    {
        $organization_id = Session::get('user-organization-id');
        $organization = Organization::find($organization_id);
        $event360_vendor_profile = $organization->event360VendorProfile;
        $from_date = date('Y-m-d',strtotime("-1 month"));
        $to_date = date('Y-m-d');
        return View::make('v1.web360.lead_management.index', compact('event360_vendor_profile','from_date','to_date'));
    }

    public function getAjaxLeadsList()
    {
        $lead_source = Input::get('lead_source');

        $organization = Organization::find(Session::get('user-organization-id'));
        if(Employee::projectChecker('event360')) {
            if(is_object($organization->event360VendorProfile)) {
                $event360_vendor_profile_id = $organization->event360VendorProfile->id;
            } else {
                $event360_vendor_profile_id = 0;
            }

        } else {
            $event360_vendor_profile_id = 0;
        }


        if ($lead_source == 'event360_enquiries') {
            $filter_lead_status = Input::get('filter_event360_enquiries_lead_status');
            $filter_lead_rating = Input::get('filter_event360_enquiries_lead_rating');
            $filter_lead_from_date = Input::get('filter_event360_enquiries_lead_from_date');
            $filter_lead_to_date = Input::get('filter_event360_enquiries_lead_to_date');
        } elseif ($lead_source == 'web360_enquiries') {
            //$filter_lead_status = Input::get('filter_web360_enquiries_lead_status');
            $filter_lead_status = '';
            $filter_lead_rating = Input::get('filter_web360_enquiries_lead_rating');
            $filter_lead_from_date = Input::get('filter_web360_enquiries_lead_from_date');
            $filter_lead_to_date = Input::get('filter_web360_enquiries_lead_to_date');
        } elseif ($lead_source == 'event360_messenger_threads') {

            $event360_messenger_threads = Event360MessengerThread::where('event360_vendor_profile_id', $event360_vendor_profile_id)->orderby('id', 'desc')->paginate(10);

            return View::make('v1.web360.lead_management._ajax_partials.event360_messenger_threads_list', compact('event360_messenger_threads'))->render();
        }


        $build_query = Lead::where('organization_id', Session::get('user-organization-id'));

        if ($filter_lead_status != '') {
            $build_query->where('status', $filter_lead_status);
        }

        if ($filter_lead_rating != '') {
            $build_query->where('lead_rating', $filter_lead_rating);
        }

        syslog(LOG_INFO, '$filter_event360_enquiries_lead_from_date -- ' . $filter_lead_from_date);
        syslog(LOG_INFO, '$filter_event360_enquiries_lead_to_date -- ' . $filter_lead_to_date);

        if ($filter_lead_from_date != '' && $filter_lead_to_date != '') {
            $build_query->whereBetween('datetime', array($filter_lead_from_date . ' 00:00:00', $filter_lead_to_date . ' 23:59:59'));
        }

        $leads = $build_query->where('lead_source', $lead_source)->orderby('datetime', 'DESC')->paginate(10);
        $leads_id_list_for_excel = $build_query->where('lead_source', $lead_source)->orderby('datetime', 'DESC')->lists('id');


        // get sub services list provided by this vendor
        $vendor_sub_service_categories = Event360VendorProfile::getSubServicesListProvidedByVendor($event360_vendor_profile_id);


        if ($lead_source == 'web360_enquiries') {
            return View::make('v1.web360.lead_management._ajax_partials.web360_leads_list', compact('leads', 'leads_id_list_for_excel'))->render();

        } else {
            return View::make('v1.web360.lead_management._ajax_partials.leads_list', compact('leads', 'leads_id_list_for_excel', 'vendor_sub_service_categories'))->render();
        }

    }

    public function loadAjaxLeadForwardTable()
    {
        $lead_id = Input::get('lead_id');

        $lead = Lead::find($lead_id);

        $lead_forwards = $lead->leadForwards;

        return View::make('v1.web360.lead_management._partials.lead_forwards_table', compact('lead_forwards'))->render();
    }

    public function loadAjaxNotesTable()
    {
        $lead_id = Input::get('lead_id');

        $lead = Lead::find($lead_id);

        $lead_notes = $lead->leadNotes;

        return View::make('v1.web360.lead_management._partials.lead_notes_table', compact('lead_notes'))->render();
    }

    public function loadEvent360MessengerThreadMessages()
    {
        $event360_messenger_thread_id = Input::get('event360_messenger_thread_id');

        $messages = Event360MessengerThreadMessage::where('event360_messenger_thread_id', $event360_messenger_thread_id)->orderBy('id', 'asc')->get();


        return View::make('v1.web360.lead_management._partials.event360_messenger_messages_list', compact('messages', 'event360_messenger_thread_id'))->render();
    }

    public function loadMessagesTable()
    {
        $event360_messenger_thread_id = Input::get('event360_messenger_thread_id');

        $messages = Event360MessengerThreadMessage::where('event360_messenger_thread_id', $event360_messenger_thread_id)->get();


        return View::make('v1.web360.lead_management._partials.messages_table', compact('messages'))->render();
    }

    public function getAjaxNonAdvertisingEvent360LeadsWidget()
    {


        $organization_id = session::get('user-organization-id');

        $widget_data_generator = new WidgetDataGenerator();
        $leads = $widget_data_generator->getAjaxNonAdvertisingEvent360LeadsWidget($organization_id);

        return View::make('v1.web360.lead_management.widgets.non_advertising_event360_leads', compact('leads'))->render();

    }

    // to get direct access to manage the lead
    public function getEvent360EnquiryLeadManagementScreen($id){

        $lead = Lead::find($id);

        if(is_object($lead)) {

            if ($lead->organization_id == Session::get('user-organization-id')) {

                $organization = Organization::find(Session::get('user-organization-id'));
                $event360_vendor_profile_id = $organization->event360VendorProfile->id;

                // get sub services list provided by this vendor
                $vendor_sub_service_categories = Event360VendorProfile::getSubServicesListProvidedByVendor($event360_vendor_profile_id);

                return View::make('v1.web360.lead_management._partials.event360_enquiry_leads_management_screen', compact('lead', 'vendor_sub_service_categories'))->render();

            } else {
                return "You don`t have permission to view this lead.";
            }

        }else{
            return "Lead not found.";
        }

    }

    public function getEvent360MessagesLeadManagementScreen($id){

        $organization = Organization::find(Session::get('user-organization-id'));
        $event360_vendor_profile_id = $organization->event360VendorProfile->id;

        $event360_messenger_thread = Event360MessengerThread::find($id);

        if(is_object($event360_messenger_thread)) {

            $event360_messenger_threads = array();
            $event360_messenger_threads[] = $event360_messenger_thread;

            if ($event360_vendor_profile_id == $event360_messenger_thread->event360_vendor_profile_id) {
                return View::make('v1.web360.lead_management._partials.event360_messages_lead_management_screen', compact('event360_messenger_threads'))->render();
            } else {
                return "You don`t have permission to view this lead.";
            }

        }else{
            return "Message not found.";
        }


    }

    public function event360Report()
    {
        date_default_timezone_set("Asia/Singapore");
        $from_date = date('Y-m-d',strtotime("-1 month"));
        $to_date = date('Y-m-d');
        $organization_id = Session::get('user-organization-id');
        $webtics_pixel_microsite_property = WebticsPixelProperty::getEvent360WebticsPixelPropertyIdForOrganization($organization_id);
        if($webtics_pixel_microsite_property != false){
            return View::make('v1.event360.event360_report.index',compact('from_date','to_date'))->render();
        }else{
            return View::make('v1.event360.event360_report.report_generating_error')->render();
        }
    }

    // Engagement Metric Report
    public function getEvent360EngagementMetricReport(){

        header('Access-Control-Allow-Origin: *');

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date'))
        {
            $start_date = Input::get('start_date');
        }
        if(Input::has('end_date'))
        {
            $end_date = Input::get('end_date');
        }

        $widget_data_generator = new WidgetDataGenerator();
        $engagement_metric_report_details = $widget_data_generator->getEvent360EngagementMetricWidgetData($start_date,$end_date,$organization_id);

        return View::make('v1.event360.event360_report._ajax_partials.engagement_metric_table', compact('engagement_metric_report_details'))->render();
    }

    // ROI metric Report
    public function getEvent360ROIMetricReport(){

        header('Access-Control-Allow-Origin: *');

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date'))
        {
            $start_date = Input::get('start_date');
        }
        if(Input::has('end_date'))
        {
            $end_date = Input::get('end_date');
        }

        $widget_data_generator = new WidgetDataGenerator();
        $roi_metrics_details = $widget_data_generator->getEvent360ROIMetricReport($start_date,$end_date,$organization_id);

        return View::make('v1.event360.event360_report._ajax_partials.roi_metric_table', compact('roi_metrics_details'))->render();
    }

}
