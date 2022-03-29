<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 10:23
 */
class RestApiController extends BaseController
{
    public function saveWeb360EnquiriesEmailReplaceForm(){

        header('Access-Control-Allow-Origin: *');
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $webtics_pixel_property_id = Input::has('wpx_property_id')? Input::get('wpx_property_id') : "";
        $wpx_mail_form_send_to_email = Input::has('wpx_mail_form_send_to_email')? Input::get('wpx_mail_form_send_to_email') : "";
        $wpx_mail_form_name = Input::has('wpx_mail_form_name')? Input::get('wpx_mail_form_name'): "";
        $wpx_mail_form_company = Input::has('wpx_mail_form_company')? Input::get('wpx_mail_form_company') : "";
        $wpx_mail_form_phone = Input::has('wpx_mail_form_phone')? Input::get('wpx_mail_form_phone') : "";
        $wpx_mail_form_message = Input::has('wpx_mail_form_message')? Input::get('wpx_mail_form_message') : "";
        $wpx_mail_form_email = Input::has('wpx_mail_form_email')? Input::get('wpx_mail_form_email') : "";
        $utm_source = Input::has('utm_source')? Input::get('utm_source') : "";
        $utm_medium = Input::has('utm_medium')? Input::get('utm_medium') : "";
        $utm_term = Input::has('utm_term')? Input::get('utm_term') : "";
        $utm_campaign = Input::has('utm_campaign')? Input::get('utm_campaign') : "";
        $utm_content = Input::has('utm_content')? Input::get('utm_content') : "";
        $gclid = Input::has('gclid')? Input::get('gclid') : "";
        $fbclid = Input::has('fbclid')? Input::get('fbclid') : "";
        $source_url = Input::has('source_url')? Input::get('source_url') : "";
        $session_id = Input::has('session_id')? Input::get('session_id') : "";
        $webtics_pixel_property = WebticsPixelProperty::findOrFail($webtics_pixel_property_id);
        $organization_id = $webtics_pixel_property->organization->id;
        //$campaign_id = Campaign::getCampaign($utm_campaign,$utm_content,$utm_source,$organization_id);
        $campaign_id = Campaign::getCampaignIDForLeadTagging($utm_campaign,$utm_content,$organization_id);

        $enquiry_details = array(
            'name' => $wpx_mail_form_name,
            'company' => $wpx_mail_form_company,
            'email' => $wpx_mail_form_email,
            'phone' => $wpx_mail_form_phone,
            'message' => preg_replace('/[^A-Za-z0-9@.,_\s\-]/', ' ', $wpx_mail_form_message),
        );


        $enquiry_data = array(
            'webtics_pixel_property_id' => $webtics_pixel_property_id,
            'datetime' => $datetime,
            'enquiry_details' => json_encode ($enquiry_details),
        );


        $web360_enquiry = Web360Enquiry::create($enquiry_data);

        $data_lead = array(
            'organization_id' => $organization_id,
            'campaign_id' => $campaign_id,
            'datetime' => $datetime,
            'lead_source' => 'web360_enquiries',
            'lead_source_id' => $web360_enquiry->id,
            'status' => 'Pending',
            'lead_rating' => 'Raw Lead',
            'utm_source' => $utm_source,
            'utm_medium' => $utm_medium,
            'utm_term' => $utm_term,
            'utm_campaign' => $utm_campaign,
            'utm_content' => $utm_content,
            'gclid' => $gclid,
            'fbclid' => $fbclid,
            'source_url' => $source_url,
            'webtics_pixel_session_id' => $session_id,
            'webtics_pixel_property_id' => $webtics_pixel_property_id,
        );

        Lead::createLead($data_lead);


        // sending the email
        $email_sender = new EmailsController();

        $subject = $webtics_pixel_property->organization->organization.": Website email submission (Web360) On ".$datetime;
        $mailBody = View::make('emails.web360_enquiries_email_forward', compact('enquiry_details'))->render();

        $email_sender->sendEmail($subject,$mailBody,$wpx_mail_form_send_to_email);


    }

    public function saveWeb360EnquiriesWebsiteFormSubmission(){

        header('Access-Control-Allow-Origin: *');
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $webtics_pixel_property_id = Input::has('wpx_property_id')? Input::get('wpx_property_id') : "";
        $utm_source = Input::has('utm_source')? urldecode(Input::get('utm_source')) : "";
        $utm_medium = Input::has('utm_medium')? urldecode(Input::get('utm_medium')) : "";
        $utm_term = Input::has('utm_term')? urldecode(Input::get('utm_term')) : "";
        $utm_campaign = Input::has('utm_campaign')? Input::get('utm_campaign') : "";
        $utm_content = Input::has('utm_content')? Input::get('utm_content') : "";
        $gclid = Input::has('gclid')? Input::get('gclid') : "";
        $fbclid = Input::has('fbclid')? Input::get('fbclid') : "";
        $source_url = Input::has('source_url')? Input::get('source_url') : "";
        $session_id = Input::has('session_id')? Input::get('session_id') : "";
        $enquiry_details = Input::has('wpx_enquiry_details')? Input::get('wpx_enquiry_details') : "";
        $webtics_pixel_property = WebticsPixelProperty::findOrFail($webtics_pixel_property_id);
        $organization_id = is_object($webtics_pixel_property)? $webtics_pixel_property->organization->id: null;
        //$campaign_id = Campaign::getCampaign($utm_campaign,$utm_content,$utm_source,$organization_id);
        $campaign_id = Campaign::getCampaignIDForLeadTagging($utm_campaign,$utm_content,$organization_id);

        $enquiry_data = array(
            'webtics_pixel_property_id' => $webtics_pixel_property_id,
            'datetime' => $datetime,
            'enquiry_details' => str_replace('wpx_', '', $enquiry_details),
        );

        $web360_enquiry = Web360Enquiry::create($enquiry_data);

        $data_lead = array(
            'organization_id' => $organization_id,
            'campaign_id' => $campaign_id,
            'datetime' => $datetime,
            'lead_rating' => 'Raw Lead',
            'lead_source' => 'web360_enquiries',
            'lead_source_id' => $web360_enquiry->id,
            'status' => 'Pending',
            'utm_source' => $utm_source,
            'utm_medium' => $utm_medium,
            'utm_term' => $utm_term,
            'utm_campaign' => $utm_campaign,
            'utm_content' => $utm_content,
            'gclid' => $gclid,
            'fbclid' => $fbclid,
            'source_url' => $source_url,
            'webtics_pixel_session_id' => $session_id,
            'webtics_pixel_property_id' => $webtics_pixel_property_id,
        );

        $lead = Lead::createLead($data_lead);


        /** Email Notification **/
        $notification_employee_id = Notification::where('type_of_alert', 'new_lead_is_received')
            ->where('status', 1)
            ->where('email', 1)
            ->where('organization_id', $organization_id)
            ->lists('employee_id');

        $employees_in_organization_to_notify = Employee::where('organization_id',$organization_id)->whereIn('user_level',array(
            'marketing',
            'management'
        ))->where('status',1)
            ->whereIn('id', $notification_employee_id)
        ->where('status',1)
            ->get();

        Notification::newLeadReceivedEmailNotification($lead,$employees_in_organization_to_notify);

    }

}