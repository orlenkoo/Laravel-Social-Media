<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 10:23
 */
class LeadsController extends BaseController
{

    public function index()
    {
        return View::make('leads.index')->render();
    }

    public function ajaxLoadLeadsList()
    {
        $organization_id = Session::get('user-organization-id');


        $user_id = Session::get('user-id');
        $user_level = Session::get('user-level');

        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $leads_list_filter_lead_source = Input::get('leads_list_filter_lead_source');
        $leads_list_filter_lead_rating = Input::get('leads_list_filter_lead_rating');
        $leads_list_search_text = Input::get('leads_list_search_text');
        $leads_list_filter_assigned_to = Input::get('leads_list_filter_assigned_to');
        $leads_list_filter_campaign = Input::get('leads_list_filter_campaign');
        $leads_list_filter_tag = Input::get('leads_list_filter_tag');

        syslog(LOG_INFO, '$filter_date_range -- '.$filter_date_range);
        syslog(LOG_INFO, '$filter_from_date -- '.$filter_from_date);
        syslog(LOG_INFO, '$filter_to_date -- '.$filter_to_date);
        syslog(LOG_INFO, '$leads_list_filter_lead_source -- '.$leads_list_filter_lead_source);
        syslog(LOG_INFO, '$leads_list_filter_lead_rating -- '.$leads_list_filter_lead_rating);

        $build_query = Lead::where('organization_id', $organization_id);

        if($leads_list_filter_lead_source != '') {
            $build_query->where('lead_source', $leads_list_filter_lead_source);
        }


        if($leads_list_search_text != '') {
            $customer_ids = Customer::where('customer_name', 'LIKE', '%'. $leads_list_search_text .'%')->lists('id');

            $build_query->whereIn('customer_id', $customer_ids);
        }

        if($leads_list_filter_lead_rating != '') {
            $build_query->where('lead_rating', $leads_list_filter_lead_rating);
        }


        if($leads_list_filter_campaign != 'null' ){
            $leads_list_filter_campaign = explode(",",$leads_list_filter_campaign);
            $build_query->whereIn('auto_tagged_campaign', $leads_list_filter_campaign);
        }

        if($leads_list_filter_tag != 'null' ){
            $leads_list_filter_tag = explode(",",$leads_list_filter_tag);
            $lead_ids = LeadAutoCategoryTag::whereIn('id',$leads_list_filter_tag)->lists('lead_id');
            $build_query->whereIn('id', $lead_ids);
        }


        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        // if sales only load own assigned leads
        if($user_level == 'sales') {
            $build_query->where('assigned_to', $user_id);
        } else {
            if($leads_list_filter_assigned_to != 'null' ){
                $leads_list_filter_assigned_to = explode(",",$leads_list_filter_assigned_to);
                $build_query->whereIn('assigned_to', $leads_list_filter_assigned_to);
            }
        }

        $build_query->whereBetween('datetime' , array($from_date, $to_date));

        if($user_level == 'sales') {
            $build_query->orderBy('last_assignment_datetime', 'desc');
        } else {
            $build_query->orderBy('datetime', 'desc');
        }


        $leads = $build_query->paginate(10);

        return View::make('leads._ajax_partials.leads_list', compact('leads'))->render();
    }

    public function ajaxSaveNewLead()
    {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $organization_id = Session::get('user-organization-id');
        $user_id = Session::get('user-id');

        $customer_id = Input::get('customer_id');
        $customer_name = Input::get('customer_name');
        $given_name = Input::get('given_name');
        $surname = Input::get('surname');
        $designation = Input::get('designation');
        $phone_number = Input::get('phone_number');
        $email = Input::get('email');
        $company_website = Input::get('company_website');

        $primary_contact = 0;

        // check if new customer
        if($customer_id == null) {
            // create a customer
            $data_customer = array(
                'organization_id' => $organization_id,
                'account_owner_id' => $user_id,
                'customer_name' => $customer_name,
                'industry_id' => null, // to be removed
                'address_line_1' => '',
                'address_line_2' => '',
                'city' => '',
                'postal_code' => '',
                'state' => '',
                'country_id' => '',
                'phone_number' => '',
                'fax_number' => '',
                'website' => $company_website,
                'business_registration_number' => '',
            );

            $customer = Customer::create($data_customer);
            $audit_action = array(
                'action' => 'create',
                'model-id' => $customer->id,
                'data' => $data_customer
            );
            AuditTrail::addAuditEntry("Customer", json_encode($audit_action));

            $customer_id = $customer->id;

            $primary_contact = 1;
        }

        // create contact - check to prevent blank contacts
        if($given_name != "" || $surname != "" || $designation != "" || $phone_number != "" || $email != "") {
            $data_contact = array(
                'customer_id' => $customer_id,
                'salutation' => '',
                'given_name' => $given_name,
                'surname' => $surname,
                'designation' => $designation,
                'phone_number' => $phone_number,
                'other_phone_number' => '',
                'mobile_number' => '',
                'email' => $email,
                'contact_status' => 'Activated',
                'primary_contact' => $primary_contact
            );

            $contact = Contact::create($data_contact);
            $audit_action = array(
                'action' => 'create',
                'model-id' => $contact->id,
                'data' => $data_contact
            );
            AuditTrail::addAuditEntry("Contact", json_encode($audit_action));
        }



        // create the lead
        $data_lead = array(
            'organization_id' => $organization_id,
            'customer_id' => $customer_id,
            'campaign_id' => null,
            'assigned_to' => $user_id,
            'last_assignment_datetime' => $datetime,
            'datetime' => $datetime,
            'lead_source' => 'direct',
            'lead_source_id' => null,
            'lead_capture_method' => "Manual",
            'status' => 'Pending',
            'status_updated_datetime' => $datetime,
            'status_updated_by' => null,
            'lead_rating' => 'Raw Lead',
            'lead_rating_updated_datetime' => $datetime,
            'lead_rating_updated_by' => $user_id,
            'utm_source' => '',
            'utm_medium' => '',
            'utm_term' => '',
            'utm_campaign' => '',
            'utm_content' => '',
            'source_url' => '',
            'webtics_pixel_session_id' => '',
            'webtics_pixel_property_id' => '',
        );

        $lead = Lead::createLead($data_lead);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $lead->id,
            'data' => $data_lead
        );
        AuditTrail::addAuditEntry("Lead", json_encode($audit_action));


        if(Input::has('lead_note')) {

            $lead_note = Input::get('lead_note');

            // add lead note
            $data_lead_note = array(
                'lead_id' => $lead->id,
                'note' => $lead_note,
                'datetime' => $datetime,
                'created_by' => $user_id,
            );

            $lead_note = LeadNote::create($data_lead_note);

            $audit_action = array(
                'action' => 'create',
                'model-id' => $lead_note->id,
                'data' => $data_lead_note
            );
            AuditTrail::addAuditEntry("LeadNote", json_encode($audit_action));
        }



        return "Successfully Created Lead.";

    }

    public function ajaxLoadDashboardLeadsList()
    {
        date_default_timezone_set("Asia/Singapore");
        $organization_id = Session::get('user-organization-id');
        $user_id = Session::get('user-id');

        $user_level = Session::get('user-level');

        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $leads_list_filter_lead_source = Input::get('leads_list_filter_lead_source');
        $leads_list_filter_lead_rating = Input::get('leads_list_filter_lead_rating');
        $leads_list_search_text = Input::get('leads_list_search_text');
        $leads_list_filter_assigned_to = Input::get('leads_list_filter_assigned_to');
        $leads_list_filter_campaign = Input::get('leads_list_filter_campaign');

        syslog(LOG_INFO, '$filter_date_range -- '.$filter_date_range);
        syslog(LOG_INFO, '$filter_from_date -- '.$filter_from_date);
        syslog(LOG_INFO, '$filter_to_date -- '.$filter_to_date);
        syslog(LOG_INFO, '$leads_list_filter_lead_source -- '.$leads_list_filter_lead_source);
        syslog(LOG_INFO, '$leads_list_filter_lead_rating -- '.$leads_list_filter_lead_rating);

        $build_query = Lead::where('organization_id', $organization_id);

        if($leads_list_filter_lead_source != '') {
            $build_query->where('lead_source', $leads_list_filter_lead_source);
        }


        if($leads_list_search_text != '') {
            $customer_ids = Customer::where('customer_name', 'LIKE', '%'. $leads_list_search_text .'%')->lists('id');

            $build_query->whereIn('customer_id', $customer_ids);
        }

        if($leads_list_filter_lead_rating != '') {
            $build_query->where('lead_rating', $leads_list_filter_lead_rating);
        }

        if($leads_list_filter_campaign != 'null' ){
            $leads_list_filter_campaign = explode(",",$leads_list_filter_campaign);
            $build_query->whereIn('auto_tagged_campaign', $leads_list_filter_campaign);
        }



        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        // if sales only load own assigned leads
        if($user_level == 'sales') {
            $build_query->where('assigned_to', $user_id);
        } else {
            if($leads_list_filter_assigned_to != 'null' ){
                $leads_list_filter_assigned_to=explode(",",$leads_list_filter_assigned_to);
                $build_query->whereIn('assigned_to', $leads_list_filter_assigned_to);
            }
        }

        $build_query->whereBetween('datetime' , array($from_date, $to_date));

        if($user_level == 'sales') {
            $build_query->orderBy('last_assignment_datetime', 'desc');
        } else {
            $build_query->orderBy('datetime', 'desc');
        }


        $leads = $build_query->paginate(10);

        if($user_level == "sales") {
            return View::make('dashboard.sales._ajax_partials.leads_list', compact('leads'))->render();
        } else if ($user_level == "marketing") {
            return View::make('dashboard.marketing._ajax_partials.leads_list', compact('leads'))->render();
        } else if (in_array($user_level, array("management", "super_user"))) {
            return View::make('dashboard.management._ajax_partials.leads_list', compact('leads'))->render();
        }


    }

    public function ajaxLoadDashboardLeadDetails()
    {

        $user_level = Session::get('user-level');
        $lead_id = Input::get('lead_id');

        $lead = Lead::find($lead_id);

        if($user_level == 'sales') {
            return View::make('dashboard.sales._ajax_partials.lead_details', compact('lead'))->render();
        } else if($user_level == 'marketing') {
            return View::make('dashboard.marketing._ajax_partials.lead_details', compact('lead'))->render();
        } else if($user_level == 'management') {
            return View::make('dashboard.management._ajax_partials.lead_details', compact('lead'))->render();
        }


    }

    public function ajaxLoadDashboardLeadNotesList()
    {
        $lead_id = Input::get('lead_id');

        $lead_notes = LeadNote::where('lead_id', $lead_id)->orderBy('datetime', 'desc')->paginate(10);

        return View::make('dashboard.common._ajax_partials.lead_notes_list', compact('lead_notes'))->render();
    }

    public function ajaxSaveLeadNote()
    {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $lead_id = Input::get('lead_id');
        $lead_note = Input::get('lead_note');

        $data_lead_note = array(
            'lead_id' => $lead_id,
            'note' => $lead_note,
            'datetime' => $datetime,
            'created_by' => Session::get('user-id'),
        );


        $lead_note = LeadNote::create($data_lead_note);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $lead_note->id,
            'data' => $data_lead_note
        );
        AuditTrail::addAuditEntry("LeadNote", json_encode($audit_action));

        return "Successfully Updated";
    }

    public function ajaxCreateNewCustomerForLead()
    {
        $organization_id = Session::get('user-organization-id');
        $employee_id = Session::get('user-id');
        $customer_name = Input::get('customer_name');
        $lead_id = Input::get('lead_id');

        $data_customer = array(
            'organization_id' => $organization_id,
            'account_owner_id' => $employee_id,
            'customer_name' => $customer_name,
        );

        $customer = Customer::create($data_customer);

        // update the lead with customer id
        $data_lead = array(
            'customer_id' => $customer->id,
        );

        DB::table('leads')
            ->where('id', $lead_id)
            ->update($data_lead);

        return "Customer Created Successfully.";
    }

    public function ajaxAssignCustomerForLead()
    {
        $customer_id = Input::get('customer_id');
        $lead_id = Input::get('lead_id');


        DB::table('leads')
            ->where('id', $lead_id)
            ->update(array('customer_id' => $customer_id));

        return "Customer Assigned Successfully.";
    }

    public function ajaxUpdateLeadAssignedTo() {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $lead_id = Input::get('lead_id');
        $assigned_to = Input::get('assigned_to');

        $lead = Lead::find($lead_id);

        if(is_object($lead)) {
            // update lead assigned to
            $lead->update(array(
                    'assigned_to' => $assigned_to,
                    'last_assignment_datetime' => $datetime
                ));

            // update lead assignments table
            $data_lead_assignment = array(
                'lead_id' => $lead_id,
                'assigned_to' => $assigned_to,
                'assigned_datetime' => $datetime,
            );
            $lead_assignment = LeadAssignment::create($data_lead_assignment);

            $audit_action = array(
                'action' => 'update',
                'model-id' => $lead_id,
                'data' => array(
                    'assigned_to' => $assigned_to,
                    'last_assignment_datetime' => $datetime
                )
            );
            AuditTrail::addAuditEntry("LeadAssignedTo", json_encode($audit_action));


            //Notify the Lead assignment change

            $employees_in_organzation_to_notify_to_me_email = Employee::where('id',$assigned_to)->get();

            Notification::leadAssignmentToMeEmailNotification($lead,$lead_assignment,$employees_in_organzation_to_notify_to_me_email);

            $notification_employee_id = Notification::where('type_of_alert', 'lead_is_assigned')
                ->where('status', 1)
                ->where('email', 1)
                ->where('organization_id', $lead->organization_id)
                ->lists('employee_id');

            syslog(LOG_INFO,'$notification_employee_id -- '.implode('|',$notification_employee_id));

            $employees_in_organzation_to_notify = Employee::where('organization_id',$lead->organization_id)->whereIn('user_level',array(
                'marketing',
                'management'
            ))->where('status',1)
                ->whereIn('id', $notification_employee_id)
                ->where('status',1)
                ->get();

            Notification::leadAssignedEmailNotification($lead,$lead_assignment,$employees_in_organzation_to_notify);

            return "Lead Assigned Successfully.";
        }

    }

    function ajaxLoadDashboardLeadTimeLine() {
        $customer_id = Input::get('customer_id');

        if($customer_id != ''){
            $customer_time_line_items = CustomerTimeLineItem::where('customer_id', $customer_id)
                ->orderby('datetime', 'desc')
                ->paginate(5);

            if(count($customer_time_line_items) > 0) {
                return View::make('leads._ajax_partials.time_line', compact('customer_time_line_items'))->render();
            } else {
                return "No Activity For This Lead.";
            }
        }else {
            return "No Activity For This Lead.";
        }
    }

    public function ajaxUpdateLeadCampaign() {

        $lead_id = Input::get('lead_id');
        $campaign = Input::get('campaign');

        // update lead assigned to
        DB::table('leads')
            ->where('id', $lead_id)
            ->update(array(
                'campaign_id' => $campaign
            ));

        return "Lead Campaign updated Successfully.";

    }

    public function ajaxLoadSalesDashboardSalesPipelineChart(){


        $leads_rating_count = array();

        date_default_timezone_set("Asia/Singapore");
        $organization_id = Session::get('user-organization-id');
        $user_id = Session::get('user-id');

        $user_level = Session::get('user-level');


        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        syslog(LOG_INFO, '$from_date -- '.$from_date);
        syslog(LOG_INFO, '$to_date -- '.$to_date);
        syslog(LOG_INFO, '$user_id -- '.$user_id);
        syslog(LOG_INFO, '$organization_id -- '.$organization_id);
        syslog(LOG_INFO, '$user_level -- '.$user_level);

        $build_query = DB::table('leads')
            ->where('organization_id', $organization_id)
            ->whereBetween('lead_rating_updated_datetime' , array($from_date, $to_date));

        // if sales only load own assigned leads
        if($user_level == 'sales') {
            $build_query->where('assigned_to', $user_id);
        }


        $build_query1 = clone $build_query;

        $lead_count = $build_query1->where('lead_rating', 'Lead')->count();

        $leads_rating_count[] = array('lead_rating' => 'Lead', 'number_of_leads' => $lead_count);

        $build_query2 = clone $build_query;

        $quoted_count = $build_query2->where('lead_rating', 'Quoted')->count();

        $queries = DB::getQueryLog();
        $last_query = end($queries);

        syslog(LOG_INFO, '$last_query -- ' . json_encode($last_query));

        $leads_rating_count[] = array('lead_rating' => 'Quoted', 'number_of_leads' => $quoted_count);

        $build_query3 = clone $build_query;

        $quoted_negotiation_count = $build_query3->where('lead_rating', 'Quoted - Negotiation')->count();

        $leads_rating_count[] = array('lead_rating' => 'Quoted - Negotiation', 'number_of_leads' => $quoted_negotiation_count);

        $build_query4 = clone $build_query;

        $quoted_call_count = $build_query4->where('lead_rating', 'Quoted - Call')->count();

        $leads_rating_count[] = array('lead_rating' => 'Quoted - Call', 'number_of_leads' => $quoted_call_count);

        return json_encode($leads_rating_count);
    }

    public function ajaxLoadDashboardLeadStatistics(){

        $organization_id = Session::get('user-organization-id');

        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $leads_junk_count = DB::table('leads')
            ->select(DB::raw('count(*) as lead_junk_count'))
            ->where('lead_rating','Junk')
            ->where('organization_id',$organization_id)
            ->whereBetween('datetime' , array($from_date, $to_date))
            ->pluck('lead_junk_count');

        $leads_assigned_count = DB::table('leads')
            ->select(DB::raw('count(*) as leads_assigned_count'))
            ->where('assigned_to','!=','')
            ->where('organization_id',$organization_id)
            ->whereBetween('datetime' , array($from_date, $to_date))
            ->pluck('leads_assigned_count');

        $leads_unassigned_count = DB::table('leads')
            ->select(DB::raw('count(*) as leads_unassigned_count'))
            ->where('assigned_to','')
            ->where('organization_id',$organization_id)
            ->whereBetween('datetime' , array($from_date, $to_date))
            ->pluck('leads_unassigned_count');

        $leads_closed_count = DB::table('leads')
            ->select(DB::raw('count(*) as leads_closed_count'))
            ->where('organization_id',$organization_id)
            ->whereBetween('datetime' , array($from_date, $to_date))
            ->where('lead_rating','Accepted')
            ->pluck('leads_closed_count');

        $leads_contacted_count = 0;
        $leads = Lead::where('organization_id',$organization_id)
            ->whereBetween('datetime' , array($from_date, $to_date))
            ->get();
        foreach($leads as $lead){
            $customer_id = $lead->customer_id;
            $customer_time_line_items_count = DB::table('customer_time_line_items')
                ->select(DB::raw('count(*) as customer_time_line_items_count'))
                ->where('customer_id',$customer_id)
                ->pluck('customer_time_line_items_count');
            $leads_contacted_count += $customer_time_line_items_count;
        }



        $leads_data = array(
            'leads_unassigned_count' => $leads_unassigned_count,
            'leads_assigned_count' => $leads_assigned_count,
            'leads_contacted_count' => $leads_contacted_count,
            'leads_closed_count' => $leads_closed_count,
            'leads_junk_count' => $leads_junk_count,
        );


        return View::make('dashboard.marketing._ajax_partials.lead_statistics_table', compact('leads_data'))->render();

    }

    public function ajaxLoadDashboardCampaignPerformance(){

        $organization_id = Session::get('user-organization-id');

        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $campaigns = Campaign::where('organization_id',$organization_id)
            ->whereBetween('created_at' , array($from_date, $to_date))
            ->get();

        $campaign_performance_data = array();
        foreach($campaigns as $campaign){

            $lead_count = count($campaign->leads);

            $quotes_count = 0;
            $contract_count = 0;
            $contract_amount = 0;
            foreach($campaign->leads as $lead){
                $customer_id = $lead->customer_id;
                $quotations_count = DB::table('quotations')
                    ->select(DB::raw('count(*) as quotations_count'))
                    ->where('customer_id',$customer_id)
                    ->pluck('quotes_count');
                $quotes_count += $quotations_count;

                $quotations_contact_count = DB::table('quotations')
                    ->select(DB::raw('count(*) as quotations_contact_count'))
                    ->where('customer_id',$customer_id)
                    ->where('quotation_status','Accepted')
                    ->pluck('quotations_contact_count');
                $contract_count += $quotations_contact_count;

                $quotations_contact_amount = DB::table('quotations')
                    ->select(DB::raw('sum(sub_total) as quotations_contact_amount'))
                    ->where('customer_id',$customer_id)
                    ->where('quotation_status','Accepted')
                    ->pluck('quotations_contact_amount');
                $contract_amount += $quotations_contact_amount;
            }

            $roi = 0;
            if($campaign->cost != 0){
                $roi = ($contract_amount/$campaign->cost);
            }
            $roi = round($roi, 2);


            array_push($campaign_performance_data,array(
                's.no' => $campaign->id,
                'campaign_name' => $campaign->campaign_name,
                'campaign_content' => $campaign->campaign_content,
                'cost' => $campaign->cost,
                'start_end_date' => $campaign->start_date.' / '.$campaign->end_date,
                'lead_count' => $lead_count,
                'quotes_count' => $quotes_count,
                'contract_count' => $contract_count,
                'contract_amount' => $contract_amount,
                'roi' => $roi,
            ));
        }

        return View::make('dashboard.marketing._ajax_partials.campaign_performance_table', compact('campaign_performance_data'))->render();

    }

    public static function getTagsForLead($lead_id)
    {
        //get all tags assigned for lead
        $lead_tag_assignments = LeadAutoCategoryTag::where('lead_id',$lead_id)
            ->lists('category_tag');

        return $lead_tag_assignments;
    }

    public function assignLeadTags(){
        
        $lead_id = Input::get('lead_id');
        $lead_tags = Input::get('lead_tags');//selected customer tags

        //remove all existing tags assigned for lead
        LeadAutoCategoryTag::where('lead_id',$lead_id)->delete();

        //check if no tags were selected
        if(empty($lead_tags)){
            return 'Lead updated successfully';
        }

        foreach($lead_tags as $key => $value){
            LeadAutoCategoryTag::create(array(
                'lead_id' => $lead_id,
                'category_tag' => $value,
            ));
        }

        return 'Lead updated successfully';
    }

    public function ajaxGetLeadMetaDetailsForDropDown()
    {
        try {
            $lead_id = Input::get('lead_id');

            $lead = Lead::find($lead_id);

            $lead_meta_list = [];

            if(is_object($lead)) {
                if($lead->lead_source == "web360_enquiries") {
                    $enquiry_details = is_object($lead->web360Enquiry)? json_decode($lead->web360Enquiry->enquiry_details): '';

                    foreach($enquiry_details as $key => $value) {
                        $lead_meta_list[] = ['key' => ucwords(str_replace('_', ' ', $key)), 'value' => is_array($value)? implode(",", $value) : $value];
                    }
                } else if($lead->lead_source == "novocall_leads") {
                    $enquiry_details = is_object($lead->novocallLead)? get_object_vars(json_decode($lead->novocallLead->lead_details)): '';

                    foreach($enquiry_details as $key => $value) {
                        $lead_meta_list[] = ['key' => ucwords(str_replace('_', ' ', $key)), 'value' => is_array($value)? implode(",", $value) : $value];
                    }
                }


                return json_encode($lead_meta_list);
            } else {
                return "Not a Lead";
            }
        } catch(Exception $e) {
            return "";
        }

    }

    public function ajaxLoadMyLeadsDashboardLeadOverviewVolumeChart(){

        $organization_id = Session::get('user-organization-id');
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $my_leads_widget_assigned_to = Input::get('my_leads_widget_assigned_to');


        $sales_filter_query = '';
        if($my_leads_widget_assigned_to != 'null') {
            $sales_filter_query = "AND leads.assigned_to IN (".$my_leads_widget_assigned_to.")";
        }

        $date_filter_query = "leads.datetime >= '$from_date' AND leads.datetime <= '$to_date'";
        if(Session::get('user-level') == 'sales') {
            $date_filter_query = "leads.last_assignment_datetime >= '$from_date' AND leads.last_assignment_datetime <= '$to_date'";
        }

        $query_lead_overview_volume = "SELECT
                                            SUM(lead_group.total_count) as total_count,
                                            SUM(lead_group.raw_lead_count) as raw_lead_count,
                                            SUM(lead_group.lead_count) as lead_count,
                                            SUM(lead_group.junk_count) as junk_count,
                                            SUM(lead_group.duplicate_count) as duplicate_count,
                                            SUM(lead_group.quoted_count) as quoted_count,
                                            SUM(lead_group.quoted_negotiation_count) as quoted_negotiation_count,
                                            SUM(lead_group.quoted_lost_count) as quoted_lost_count,
                                            SUM(lead_group.quoted_won_count) as quoted_won_count
                                        FROM(
                                                SELECT 
                                                    COUNT(*) as total_count,
                                                     CASE WHEN leads.lead_rating = 'Raw Lead' THEN COUNT(*)  ELSE 0 END AS raw_lead_count,
                                                     CASE WHEN leads.lead_rating = 'Lead' THEN COUNT(*)  ELSE 0 END AS lead_count,
                                                     CASE WHEN leads.lead_rating = 'Junk' THEN COUNT(*)  ELSE 0 END AS junk_count,
                                                     CASE WHEN leads.lead_rating = 'Duplicate' THEN COUNT(*)  ELSE 0 END AS duplicate_count,
                                                     CASE WHEN leads.lead_rating = 'Quoted' THEN COUNT(*)  ELSE 0 END AS quoted_count,
                                                     CASE WHEN leads.lead_rating = 'Quoted - Negotiation' THEN COUNT(*)  ELSE 0 END AS quoted_negotiation_count,
                                                     CASE WHEN leads.lead_rating = 'Quoted - Lost' THEN COUNT(*)  ELSE 0 END AS quoted_lost_count,
                                                     CASE WHEN leads.lead_rating = 'Quoted - Won' THEN COUNT(*)  ELSE 0 END AS quoted_won_count
                                        
                                                FROM leads 
                                                WHERE $date_filter_query
                                                         AND leads.organization_id = '".$organization_id."' $sales_filter_query
                                                GROUP BY leads.lead_rating
                                        ) lead_group";

        syslog(LOG_INFO, 'query_lead_overview_volume -- '. $query_lead_overview_volume);

        $lead_overview_volume = DB::select($query_lead_overview_volume);

        if(!$lead_overview_volume[0]->total_count){
            return json_encode([]);
        }

        return json_encode($lead_overview_volume);
    }

    public function ajaxLoadMyLeadsDashboardLeadOverviewValueChart(){

        $organization_id = Session::get('user-organization-id');
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $my_leads_widget_assigned_to = Input::get('my_leads_widget_assigned_to');

        $sales_filter_query = '';
        if($my_leads_widget_assigned_to != 'null') {
            $sales_filter_query = "AND leads.assigned_to IN (".$my_leads_widget_assigned_to.")";
        }

        $date_filter_query = "leads.datetime >= '$from_date' AND leads.datetime <= '$to_date'";
        if(Session::get('user-level') == 'sales') {
            $date_filter_query = "leads.last_assignment_datetime >= '$from_date' AND leads.last_assignment_datetime <= '$to_date'";
        }

        $query_lead_overview_value = "SELECT
                                            SUM(quotation_sales.total_value) as total_value,
                                            SUM(quotation_sales.quoted_value) as quoted_value,
                                            SUM(quotation_sales.quoted_negotiation_value) as quoted_negotiation_value,
                                            SUM(quotation_sales.quoted_lost_value) as quoted_lost_value,
                                            SUM(quotation_sales.quoted_won_value) as quoted_won_value
                                        FROM (
                                                SELECT
                                                    ( quotations.net_total - quotations.total_taxes ) as total_value,
                                                    CASE WHEN quotations.quotation_status = 'New' OR quotations.quotation_status = 'Pending' THEN ( quotations.net_total - quotations.total_taxes ) ELSE 0 END AS quoted_value,
                                                    CASE WHEN quotations.quotation_status = 'Negotiate' THEN ( quotations.net_total - quotations.total_taxes ) ELSE 0 END AS quoted_negotiation_value,
                                                    CASE WHEN quotations.quotation_status = 'Lost' THEN ( quotations.net_total - quotations.total_taxes ) ELSE 0 END AS quoted_lost_value,
                                                    CASE WHEN quotations.quotation_status = 'Closed' THEN ( quotations.net_total - quotations.total_taxes ) ELSE 0 END AS quoted_won_value
                                                FROM quotations
                                                LEFT JOIN leads ON leads.id = quotations.lead_id
                                                WHERE quotations.quotation_status != 'Updated'
                                                 AND $date_filter_query
                                                 AND leads.organization_id = '".$organization_id."' $sales_filter_query
                                         ) quotation_sales";

        syslog(LOG_INFO, 'query_lead_overview_value -- '. $query_lead_overview_value);

        $lead_overview_value = DB::select($query_lead_overview_value);

        if(!$lead_overview_value[0]->total_value){
            return json_encode([]);
        }

        return json_encode($lead_overview_value);
    }


    public function ajaxLoadMyLeadsDashboardLeadVolumeByCampaignChart(){

        $organization_id = Session::get('user-organization-id');
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $my_leads_widget_assigned_to = Input::get('my_leads_widget_assigned_to');

        $sales_filter_query = '';
        if($my_leads_widget_assigned_to != 'null') {
            $sales_filter_query = "AND leads.assigned_to IN (".$my_leads_widget_assigned_to.")";
        }

        $date_filter_query = "leads.datetime >= '$from_date' AND leads.datetime <= '$to_date'";
        if(Session::get('user-level') == 'sales') {
            $date_filter_query = "leads.last_assignment_datetime >= '$from_date' AND leads.last_assignment_datetime <= '$to_date'";
        }

        $query_lead_volume_by_campaigns = "SELECT
                                                SUM(campaign_count) as campaign_count ,
                                                CASE WHEN campaign = '' OR campaign IS NULL THEN 'Direct Leads' ELSE campaign END AS campaign
                                            FROM(
                                                SELECT 
                                                    COUNT(*) as campaign_count,
                                                    auto_tagged_campaign AS campaign
                                                FROM leads 
                                                WHERE $date_filter_query AND leads.organization_id = '".$organization_id."' $sales_filter_query
                                                GROUP BY leads.auto_tagged_campaign
                                            ) lead_group
                                            GROUP BY lead_group.campaign";

        syslog(LOG_INFO, 'query_lead_volume_by_campaigns -- '. $query_lead_volume_by_campaigns);

        $lead_volume_by_campaigns = DB::select($query_lead_volume_by_campaigns);

        return json_encode($lead_volume_by_campaigns);
    }

    public function ajaxLoadMyLeadsDashboardLeadValueByCampaignChart(){

        $organization_id = Session::get('user-organization-id');
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $my_leads_widget_assigned_to = Input::get('my_leads_widget_assigned_to');

        $sales_filter_query = '';
        if($my_leads_widget_assigned_to != 'null') {
            $sales_filter_query = "AND leads.assigned_to IN (".$my_leads_widget_assigned_to.")";
        }

        $date_filter_query = "leads.datetime >= '$from_date' AND leads.datetime <= '$to_date'";
        if(Session::get('user-level') == 'sales') {
            $date_filter_query = "leads.last_assignment_datetime >= '$from_date' AND leads.last_assignment_datetime <= '$to_date'";
        }


        $query_lead_value_by_campaigns = " SELECT
                                                   CASE WHEN SUM(campaign_value) = '' OR SUM(campaign_value) IS NULL THEN 0  ELSE SUM(campaign_value) END AS campaign_value,
                                                   CASE WHEN campaign = '' OR campaign IS NULL THEN 'Direct Leads' ELSE campaign END AS campaign
                                                FROM(
                                                   SELECT
                                                       SUM(quotations.net_total - quotations.total_taxes) as campaign_value,
                                                       auto_tagged_campaign AS campaign
                                                   FROM leads
                                                   LEFT JOIN quotations ON leads.id = quotations.lead_id
                                                   WHERE quotations.quotation_status != 'Updated' AND $date_filter_query AND leads.organization_id = '".$organization_id."' $sales_filter_query
                                                   GROUP BY leads.auto_tagged_campaign
                                                ) lead_group
                                                GROUP BY lead_group.campaign";

        syslog(LOG_INFO, 'query_lead_value_by_campaigns -- '. $query_lead_value_by_campaigns);

        $lead_value_by_campaigns = DB::select($query_lead_value_by_campaigns);

        return json_encode($lead_value_by_campaigns);
    }

    public function ajaxLoadLeadTags()
    {
        $organization_id = Session::get('user-organization-id');
        $lead_ids_for_organization = Lead::where('organization_id',$organization_id)->lists('id');
        $lead_tags = LeadAutoCategoryTag::select(DB::raw('category_tag as tag ,id'))->whereIn('lead_id',$lead_ids_for_organization)->orderBy('tag', 'asc')->get();
        $lead_tags_json = json_encode($lead_tags);
        return Response::make($lead_tags_json);
    }
}