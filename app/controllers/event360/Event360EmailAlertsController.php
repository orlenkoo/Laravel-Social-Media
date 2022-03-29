<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 31/1/2017
 * Time: 8:32 PM
 */
class Event360EmailAlertsController
{


    public static function newEvent360CallLeadAlert($lead)
    {
        $organization = $lead->organization;
        $organization_contact_persons = Organization::getOrganizationContactPersons($organization->id);
        $call = $lead->event360Call;
        $call_id = $call->id;


        $subject = "You Have A New Phone Enquiry From Event360 | Call ID: ". $call_id;

        $mailBody = '';

        $email_sender = new EmailsController();

        $mailBody .= View::make('webtics_product.event360.email_templates.alert_emails.vendors.new_event360_call_lead', compact('lead', 'organization', 'call', 'call_id'))->render();


        foreach($organization_contact_persons as $organization_contact_person) {
            $email_sender->sendEmail($subject, $mailBody, $organization_contact_person->email, null);
        }
    }





}