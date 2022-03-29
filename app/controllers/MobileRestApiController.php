<?php

/**
 * Created by PhpStorm.
 * Event 360 Mobile Api
 * User: Roshane De Silva
 * Date: 07/09/2016
 * Time: 07:10
 */
class MobileRestApiController extends BaseController
{

    // create json response
    public function createResponse($status, $data, $message)
    {
        $json_response = json_encode(array('status' => $status, 'data' => $data, 'message' => $message));

        syslog(LOG_INFO, 'MobileRestApiController -- $json_response -- ' . $json_response);

        return $json_response;
    }

    // login to the mobile app using username, password
    public function login()
    {

        $email = Input::get('email');
        $password = Input::get('password');

        $employee = Employee::where('email', '=', $email)->where('status', 1)->first();
        if (!is_object($employee)) {
            return $this->createResponse('false', null, 'User cannot be found.');
        }

        // get lead ratings
        $lead_ratings = array();

        foreach(Lead::$lead_ratings as $key=>$lead_rating) {
            $lead_ratings[] = $lead_rating;
        }

        // get webtics pixel properties

        $webtics_pixel_properties = WebticsPixelProperty::where('organization_id', $employee->organization_id)->where('microsite', 0)->orderby('property_name')->get();

        $webtics_pixel_properties_array = array();

        foreach($webtics_pixel_properties as $webtics_pixel_property) {
            $webtics_pixel_properties_array[] = array("id" => $webtics_pixel_property->id, "property_name" => $webtics_pixel_property->property_name );
        }

        // get project ids list
        $project_ids = Organization::getProjectIds($employee->organization_id);

        if (Hash::check($password, $employee['password'])) {
            $data = array(
                'employee_id' => $employee->id,
                'given_name' => $employee->given_name,
                'surname' => $employee->surname,
                'email' => $employee->email,
                'contact_no' => $employee->contact_no,
                'organization_id' => $employee->organization_id,
                'organization_name' => $employee->organization->organization,
                'lead_ratings' => $lead_ratings,
                'project_ids' => $project_ids,
                'webtics_pixel_properties' => $webtics_pixel_properties_array

            );

            return $this->createResponse('true', $data, 'Successfully logged in.');
        } else {
            return $this->createResponse('false', null, 'Wrong password.');
        }

    }

    // get website leads list
    public function getWebSiteLeadsList()
    {

        $organization_id = Input::get('organization_id');

        $leads_paginate = Lead::where('organization_id', $organization_id)
            ->where('lead_source', 'web360_enquiries')
            ->orderby('datetime', 'DESC')
            ->paginate(10);

        $leads = array();
        foreach($leads_paginate as $lead) {

            $web360_enquiry = $lead->web360Enquiry;

            $form_fields_array = [];

            $enquiry_details = json_decode($web360_enquiry->enquiry_details);
            if (is_object($enquiry_details)) {

                foreach($enquiry_details as $key => $value) {

                        $values = is_array($value)? implode(",", $value) : $value;
                        $form_fields_array[] = $key . ": " . $values;


                }
            }



            $leads[] = array(
                'lead' => $lead,
                'form_fields_array' => $form_fields_array
            );
        }

        if (count($leads) > 0) {
            return $this->createResponse('true', $leads, count($leads) . ' leads found.');
        } else {
            return $this->createResponse('false', null, 'No website leads found.');
        }

    }

    // get Lead Rating options
    public function getLeadRatingOptions()
    {


        return $this->createResponse('true', Lead::$lead_ratings, 'Lead ratings');
    }


    // update lead rating
    public function updateLeadRating()
    {
        date_default_timezone_set("Asia/Singapore");
        $lead_id = Input::get('lead_id');
        $lead_rating = Input::get('lead_rating');
        $employee_id = Input::get('employee_id');

        $lead = Lead::find($lead_id);
        if (is_object($lead)) {

            Lead::updateLeadRating($lead, $lead_rating, $employee_id);

            return $this->createResponse('true', null, 'Lead rating updated successfully.');
        } else {
            return $this->createResponse('false', null, 'Lead not found.');
        }

    }

    // add lead note
    public function addLeadNote()
    {
        date_default_timezone_set("Asia/Singapore");
        $lead_id = Input::get('lead_id');
        $note = Input::get('note');
        $employee_id = Input::get('employee_id');

        $lead = Lead::find($lead_id);
        if (is_object($lead)) {
            $lead_note = LeadNote::createLeadNote($lead->id, $note, $employee_id);
            return $this->createResponse('true', null, 'Lead note added successfully.');
        } else {
            return $this->createResponse('false', null, 'Lead not found.');
        }
    }

    // get lead notes list
    public function getLeadNotesList()
    {
        $lead_id = Input::get('lead_id');
        $lead_notes_paginate = LeadNote::where('lead_id', $lead_id)->paginate(10);

        $lead_notes = array();
        foreach($lead_notes_paginate as $lead_note) {
            $lead_notes[] = $lead_note;
        }

        if (count($lead_notes) > 0) {
            return $this->createResponse('true', $lead_notes, 'Lead notes list.');
        } else {
            return $this->createResponse('false', null, 'Lead notes not found.');
        }
    }

    // share lead
    public function shareLead()
    {

        $lead_id = Input::get('lead_id');
        $to_email = Input::get('to_email');
        $message = Input::get('message');
        $employee_id = Input::get('employee_id');

        $lead = Lead::find($lead_id);

        if (is_object($lead)) {

            $lead_forward = Lead::forwardLead($lead, $to_email, $message, $employee_id);
            return $this->createResponse('true', null, 'Lead shared successfully.');
        } else {
            return $this->createResponse('false', null, 'Lead not found.');
        }
    }

    // get share leads list
    public function getShareLeadsList()
    {
        $lead_id = Input::get('lead_id');
        $lead_forwards_paginate = LeadForward::where('lead_id', $lead_id)->paginate(10);

        $lead_forwards = array();
        foreach($lead_forwards_paginate as $lead_forward) {
            $lead_forwards[] = $lead_forward;
        }

        if (count($lead_forwards) > 0) {
            return $this->createResponse('true', $lead_forwards, 'Lead forwards list.');
        } else {
            return $this->createResponse('false', null, 'Lead forwards not found.');
        }
    }


    // get event 360 enquiry leads list -- writing a new method because needing lots of custom data
    public function getEvent360EnquiryLeadsList()
    {
        $organization_id = Input::get("organization_id");

        $leads_paginate = Lead::where('organization_id', $organization_id)
            ->where('lead_source', 'event360_enquiries')
            ->orderby('datetime', 'DESC')
            ->paginate(10);

        $leads = array();
        foreach($leads_paginate as $lead) {

            $event360_enquiry = $lead->event360Enquiry;

            $event360_event_planner_profile = $event360_enquiry->event360EventPlannerProfile;





            $leads[] = array(
                'lead_id' => $lead->id,
                'datetime' => $lead->datetime,
                'lead_status' => $lead->status,
                'lead_rating' => $lead->lead_rating,
                'event_type' => $event360_enquiry->event360EventType->event_type,
                'given_name' => $event360_event_planner_profile->given_name,
                'surname' => $event360_event_planner_profile->surname,
            );
        }

        if (count($leads) > 0) {
            return $this->createResponse('true', $leads, count($leads) . ' leads found.');
        } else {
            return $this->createResponse('false', null, 'No Event360 leads found.');
        }
    }


    // get event360 enquiry lead details
    public function getEvent360EnquiryLeadDetails()
    {
        $lead_id = Input::get('lead_id');


        $lead = Lead::find($lead_id);


        if(is_object($lead)) {

            // get vendors sub service categories to show only his sub categories
            $event360_vendor_profile_id = $lead->organization->event360VendorProfile->id;
            $vendor_sub_service_categories = Event360VendorProfile::getSubServicesListProvidedByVendor($event360_vendor_profile_id);


            $event360_enquiry = $lead->event360Enquiry;
            $event360_event_planner_profile = $event360_enquiry->event360EventPlannerProfile;

            // lead details
            $lead_details = array(
                'generated_datetime' => $lead->datetime,
            );

            // event planner profile information
            if(is_object($event360_event_planner_profile)) {
                $event_planner_details = array(
                    'company_name' => $event360_event_planner_profile->company_name,
                    'surname' => $event360_event_planner_profile->surname,
                    'given_name' => $event360_event_planner_profile->given_name,
                    'job_title' => $event360_event_planner_profile->job_title,
                );
            } else {
                $event_planner_details = array(
                    'company_name' => '',
                    'surname' => '',
                    'given_name' => '',
                    'job_title' => '',
                );
            }

            // event enquiry details
            if(is_object($event360_enquiry)) {
                $enquiry_details = array(
                    'event_type' => is_object($event360_enquiry->event360EventType)? $event360_enquiry->event360EventType->event_type : "NA",
                    'event_pax' => $event360_enquiry->pax_min . '-' . $event360_enquiry->pax_max,
                    'event_date' => $event360_enquiry->event_date,
                    'event_start_stop_time' => $event360_enquiry->event_start_time . ' ' . $event360_enquiry->event_end_time,
                );
            } else {
                $enquiry_details = array(
                    'event_type' => '',
                    'event_pax' => '',
                    'event_date' => '',
                    'event_start_stop_time' => '',
                );
            }

            // event requirements
            $event_requirements = array();
            foreach($event360_enquiry->event360EnquiryRequiredServices as $required_service) {
                $required_service_array = array();
                $required_sub_services = $required_service->event360EnquiryRequiredSubServices;
                $required_sub_services_array = array();
                foreach($required_sub_services as $required_sub_service) {
                    if(in_array($required_sub_service->event360SubServiceCategory->id, $vendor_sub_service_categories)) {
                        $required_sub_services_array[] = array(
                            'event360_enquiry_required_sub_service_id' =>   $required_sub_service->id,
                            'event360_enquiry_required_sub_service_category' =>   $required_sub_service->event360SubServiceCategory->service_category,
                            'event360_enquiry_required_sub_service_category_event360_remarks' =>   $required_sub_service->event360SubServiceCategory->event360_remarks,
                        );
                    }

                }
                $required_service_array["required_service_category"] = $required_service->event360ServiceCategory->service_category;
                $required_service_array["required_sub_services"] = $required_sub_services_array;
                $event_requirements[] = $required_service_array;
            }

            // previous quotations
            $previous_quotations = array();

            foreach($lead->event360EnquiryLeadQuotes as $event360_enquiry_lead_quote) {
                $lead_quotation_details = array();

                $lead_quotation_details['quoted_on'] = $event360_enquiry_lead_quote->quote_date_time;
                $lead_quotation_details['quoted_by'] = is_object($event360_enquiry_lead_quote->quoteUpdatedBy)? $event360_enquiry_lead_quote->quoteUpdatedBy->getEmployeeFullName(): "NA";

                $event360_enquiry_lead_quote_items = Event360EnquiryLeadQuoteItem::where('event360_enquiry_lead_quote_id', $event360_enquiry_lead_quote->id)->orderBy('id', 'desc')->get();
                $event360_enquiry_lead_quote_items_array = array();
                foreach($event360_enquiry_lead_quote_items as $event360_enquiry_lead_quote_item) {
                    $event360_enquiry_lead_quote_items_array[] = array(
                        'required_sub_service_category' => $event360_enquiry_lead_quote_item->event360EnquiryRequiredSubService->event360SubServiceCategory->service_category,
                        'required_sub_service_category_event360_remarks' => $event360_enquiry_lead_quote_item->event360EnquiryRequiredSubService->event360SubServiceCategory->event360_remarks,
                        'lead_quote_item_quote_amount' => $event360_enquiry_lead_quote_item->quote_amount,
                        'lead_quote_item_quote_remarks_notes' => $event360_enquiry_lead_quote_item->quote_remarks_notes
                    );
                }

                $lead_quotation_details['quote_items'] = $event360_enquiry_lead_quote_items_array;

                $previous_quotations[] = $lead_quotation_details;

            }

            $enquiry_lead_details = array(
                'lead_details' => $lead_details,
                'event_planner_details' => $event_planner_details,
                'enquiry_details' => $enquiry_details,
                'event_requirements' => $event_requirements,
                'previous_quotations' => $previous_quotations,


            );

            return $this->createResponse('true', $enquiry_lead_details, 'Enquiry lead details found.');
        } else {
            return $this->createResponse('false', null, 'Enquiry lead details not found.');
        }

    }

    // save lead quotation
    public function saveLeadQuotation()
    {
        $lead_id = Input::get('lead_id');
        $employee_id = Input::get('employee_id');
        $event360_enquiry_required_sub_service_ids = json_decode(Input::get('event360_enquiry_required_sub_service_ids'));
        $quote_amounts = json_decode(Input::get('quote_amounts'));
        $quote_remarks_notes = json_decode(Input::get('quote_remarks_notes'));

        Event360EnquiryLeadQuote::saveLeadQuotation($lead_id, $employee_id, $event360_enquiry_required_sub_service_ids, $quote_amounts, $quote_remarks_notes);

        return $this->createResponse('true', null, 'Quotation submitted successfully.');

    }

    // get call leads
    public function getCallLeads()
    {
        $organization_id = Input::get("organization_id");

        $leads_paginate = Lead::where('organization_id', $organization_id)
            ->where('lead_source', 'event360_calls')
            ->orderby('datetime', 'DESC')
            ->paginate(10);

        $leads = array();
        foreach($leads_paginate as $lead) {

            $event360_call = $lead->event360Call;

            $leads[] = array(
                'lead_id' => $lead->id,
                'datetime' => $lead->datetime,
                'lead_rating' => $lead->lead_rating,
                'incoming_call_number' => $event360_call->incoming_call_number,
                'tracking_number' => $event360_call->number1300,
                'termination_number' => $event360_call->transferred_number,
                'result' => $event360_call->result,
                'call_duration' => $event360_call->duration,
                'total_duration' => $event360_call->durationof1300,
                'recording_url' => $event360_call->recording_url,
            );
        }

        if (count($leads) > 0) {
            return $this->createResponse('true', $leads, count($leads) . ' leads found.');
        } else {
            return $this->createResponse('false', null, 'No call leads found.');
        }
    }

    // get message leads
    public function getMessageLeads()
    {
        $organization_id = Input::get("organization_id");

        $leads_paginate = Lead::where('organization_id', $organization_id)
            ->where('lead_source', 'event360_messenger_threads')
            ->orderby('datetime', 'DESC')
            ->paginate(10);

        $leads = array();
        foreach($leads_paginate as $lead) {

            $event360_messenger_thread = $lead->event360MessengerThread;

            $messages = array();
            foreach($event360_messenger_thread->event360MessengerThreadMessages as $message) {
                $messages[] = array(
                    'message' => $message->message,
                    'sent_by' => $message->sent_by,
                    'timestamp' => $message->timestamp,
                );
            }


            $leads[] = array(
                'lead_id' => $lead->id,
                'datetime' => $lead->datetime,
                'event360_messenger_thread_id' => $event360_messenger_thread->id,
                'customer_name' => $event360_messenger_thread->event360EventPlannerProfile->getEventPlannerName(),
                'subject' => $event360_messenger_thread->subject,
                'customer_company_name' => $event360_messenger_thread->event360EventPlannerProfile->company_name,
                'messages' => $messages,
            );
        }

        if (count($leads) > 0) {
            return $this->createResponse('true', $leads, count($leads) . ' leads found.');
        } else {
            return $this->createResponse('false', null, 'No message leads found.');
        }
    }
    
    // send message 
    public function sendMessage()
    {
        $event360_messenger_thread_id = Input::get('event360_messenger_thread_id');
        $message = Input::get('message');
        $user_id = Input::get('employee_id');
        $sent_by = 'Vendor';

        $event360_messenger_thread_message = Event360MessengerThreadMessage::saveMessage($event360_messenger_thread_id, $message, $sent_by, $user_id);

        return $this->createResponse('true', null, 'Message Sent Successfully.');
    }

    // get widget your website leads
    public function getWidgetYourWebsiteLeads()
    {
        $organization_id = Input::get("organization_id");

        $widget_data_generator = new WidgetDataGenerator();

        $weekly_website_leads = json_decode($widget_data_generator->getHomePageWidgetLeadsByWeekChart('web360_enquiries', $organization_id));

        if (count($weekly_website_leads) > 0) {
            return $this->createResponse('true', $weekly_website_leads, 'Data found.');
        } else {
            return $this->createResponse('false', null, 'Data not available.');
        }
    }

    // get widget your event360 leads
    public function getWidgetYourEvent360Leads()
    {
        $organization_id = Input::get("organization_id");

        $widget_data_generator = new WidgetDataGenerator();

        $weekly_event360_leads = json_decode($widget_data_generator->getHomePageWidgetLeadsByWeekChart('event360_enquiries', $organization_id));

        if (count($weekly_event360_leads) > 0) {
            return $this->createResponse('true', $weekly_event360_leads, 'Data found.');
        } else {
            return $this->createResponse('false', null, 'Data not available.');
        }
    }

    // get widget your event360 messages
    public function getWidgetYourEvent360Messages()
    {
        $organization_id = Input::get("organization_id");

        $widget_data_generator = new WidgetDataGenerator();

        $weekly_event360_messenger_leads = json_decode($widget_data_generator->getHomePageWidgetLeadsByWeekChart('event360_messenger_threads', $organization_id));

        if (count($weekly_event360_messenger_leads) > 0) {
            return $this->createResponse('true', $weekly_event360_messenger_leads, 'Data found.');
        } else {
            return $this->createResponse('false', null, 'Data not available.');
        }
    }
    
    // get widget web360 engagement report
    public function getWidgetWeb360EngagementReport()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $webtics_pixel_property = Input::get('webtics_pixel_property');

        $widget_data_generator = new WidgetDataGenerator();

        $engagement_metric_report_details = $widget_data_generator->getWeb360EngagementMetricReport($start_date,$end_date,$webtics_pixel_property);

        return $this->createResponse('true', $engagement_metric_report_details, 'Data found.');

    }

    // get widget web360 roi metric report
    public function getWidgetWeb360ROIMetricReport()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $webtics_pixel_property = Input::get('webtics_pixel_property');
        $organization_id = Input::get('organization_id');

        $widget_data_generator = new WidgetDataGenerator();

        $roi_metrics_details = $widget_data_generator->getWeb360ROIMetricReport($start_date,$end_date,$webtics_pixel_property,$organization_id);

        return $this->createResponse('true', $roi_metrics_details, 'Data found.');

    }

    // get widget web360 visits break down by medium
    public function getWidgetWeb360VisitsBreakDownByMedium()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $webtics_pixel_property = Input::get('webtics_pixel_property');

        $widget_data_generator = new WidgetDataGenerator();

        $result = json_decode($widget_data_generator->getWeb360VisitsBreakDownByMedium($start_date,$end_date,$webtics_pixel_property), true);

        return $this->createResponse('true', $result, 'Data found.');

    }

    // get widget web360 leads break down by medium
    public function getWidgetWeb360LeadsBreakdownByMedium()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $webtics_pixel_property = Input::get('webtics_pixel_property');
        $organization_id = Input::get('organization_id');

        $widget_data_generator = new WidgetDataGenerator();

        $web360_leads_breakdown_by_medium_detail_array = json_decode($widget_data_generator->getWeb360LeadsBreakdownByMedium($start_date,$end_date,$webtics_pixel_property,$organization_id), true);

        $return_array = array();

        foreach($web360_leads_breakdown_by_medium_detail_array as $key => $value) {
            $return_array[] = array(
                'key' => $key,
                'value' => $value
            );
        }

        return $this->createResponse('true', $return_array, 'Data found.');

    }

    // get widget web360 lead conversion rate by medium
    public function getWidgetWeb360LeadConversionRateByMedium()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $webtics_pixel_property = Input::get('webtics_pixel_property');
        $organization_id = Input::get('organization_id');

        $widget_data_generator = new WidgetDataGenerator();

        $result = $widget_data_generator->getWeb360LeadConversionRateByMedium($start_date,$end_date,$webtics_pixel_property,$organization_id);

        return $this->createResponse('true', $result, 'Data found.');

    }

    // get widget web360 latency by medium
    public function getWidgetWeb360LatencyByMedium()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $webtics_pixel_property = Input::get('webtics_pixel_property');
        $organization_id = Input::get('organization_id');

        $widget_data_generator = new WidgetDataGenerator();

        $result = $widget_data_generator->getWeb360LatencyByMedium($start_date,$end_date,$webtics_pixel_property,$organization_id);

        return $this->createResponse('true', $result, 'Data found.');

    }

    // get widget event360 engagement metric data
    public function getWidgetEvent360EngagementMetricData()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $organization_id = Input::get('organization_id');

        $widget_data_generator = new WidgetDataGenerator();

        $engagement_metric_report_details = $widget_data_generator->getEvent360EngagementMetricWidgetData($start_date,$end_date,$organization_id);

        return $this->createResponse('true', $engagement_metric_report_details, 'Data found.');

    }

    // get widget event360 roi metric data
    public function getWidgetEvent360ROIMetricReport()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $organization_id = Input::get('organization_id');

        $widget_data_generator = new WidgetDataGenerator();

        $roi_metrics_details = $widget_data_generator->getEvent360ROIMetricReport($start_date,$end_date,$organization_id);

        return $this->createResponse('true', $roi_metrics_details, 'Data found.');

    }

    // get widget event360 lead volume comparison data
    public function getWidgetEvent360LeadVolumeComparisonData()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $organization_id = Input::get('organization_id');

        $widget_data_generator = new WidgetDataGenerator();

        $lead_volume_comparison = $widget_data_generator->getEvent360LeadVolumeComparison($start_date,$end_date,$organization_id);

        if($lead_volume_comparison == -1) {
            return $this->createResponse('false', null, 'No Leads found.');
        }

        return $this->createResponse('true', $lead_volume_comparison, 'Data found.');

    }

    // get widget event360 lead breakdown by type
    public function getWidgetEvent360LeadBreakdownByType()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $organization_id = Input::get('organization_id');

        $widget_data_generator = new WidgetDataGenerator();

        $event_360_leads_breakdown_by_types = $widget_data_generator->getEvent360LeadBreakdownByType($start_date,$end_date,$organization_id);

        return $this->createResponse('true', $event_360_leads_breakdown_by_types, 'Data found.');

    }

    // get widget event360 leads breakdown by lead rating
    public function getWidgetEvent360LeadsBreakdownByLeadRating()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $organization_id = Input::get('organization_id');

        $widget_data_generator = new WidgetDataGenerator();

        $event360_lead_count = $widget_data_generator->getEvent360LeadsBreakdownByLeadRating($start_date,$end_date,$organization_id);

        return $this->createResponse('true', $event360_lead_count, 'Data found.');

    }






}