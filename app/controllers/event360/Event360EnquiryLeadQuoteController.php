<?php
use google\appengine\api\cloud_storage\CloudStorageTools;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/11/2016
 * Time: 5:23 AM
 */
class Event360EnquiryLeadQuoteController extends BaseController
{
    public function saveQuotation()
    {
        $lead_id = Input::get('lead_id');
        $event360_enquiry_required_sub_service_ids = Input::get('event360_enquiry_required_sub_service_ids');
        $quote_amounts = Input::get('quote_amounts');
        $quote_remarks_notes = Input::get('quote_remarks_notes');
        $employee_id = Session::get('user-id');

        if(Input::has('quote_attachment_titles')) {
            $quote_attachment_titles = Input::get('quote_attachment_titles');
        } else {
            $quote_attachment_titles = null;
        }

        if(Input::hasFile('attachment_files')) {
            $attachment_files = Input::file('attachment_files');
        } else {
            $attachment_files = null;
        }

        syslog(LOG_INFO, 'attachment files -- '. json_encode($attachment_files));

        Event360EnquiryLeadQuote::saveLeadQuotation($lead_id, $employee_id, $event360_enquiry_required_sub_service_ids, $quote_amounts, $quote_remarks_notes, $quote_attachment_titles, $attachment_files);

        $message = "Quotation updated successfully. Please see the previous quotations tab to view.";
        return json_encode(array('status' => 'success', 'message' => $message, 'layout' => '', 'data' => ''));
    }

    public function loadPreviousQuotations()
    {
        $lead_id = Input::get('lead_id');
        $event360_enquiry_lead_quotes = Event360EnquiryLeadQuote::where('lead_id', $lead_id)->paginate(10);

        return View::make('leads._ajax_partials.event360_enquiry_lead_quotes_list', compact('event360_enquiry_lead_quotes', 'lead_id'))->render();
    }
}