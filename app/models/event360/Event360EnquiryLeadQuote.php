<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360EnquiryLeadQuote extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_enquiry_lead_quotes';

    // Don't forget to fill this array
    protected $fillable = [
        'lead_id',
        'quote_date_time',
        'quote_updated_by',

    ];

    public function lead()
    {
        return $this->belongsTo('Lead', 'lead_id');
    }

    public function quoteUpdatedBy()
    {
        return $this->belongsTo('Employee', 'quote_updated_by');
    }

    public function event360EnquiryLeadQuoteItems()
    {
        return $this->hasMany('Event360EnquiryLeadQuoteItem');
    }

    public function event360EnquiryLeadQuoteAttachments()
    {
        return $this->hasMany('Event360EnquiryLeadQuoteAttachment');
    }

    public static function saveLeadQuotation($lead_id, $employee_id, $event360_enquiry_required_sub_service_ids, $quote_amounts, $quote_remarks_notes, $quote_attachment_titles = null, $attachment_files = null) {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $data_event360_enquiry_lead_quote = array(
            'lead_id' => $lead_id,
            'quote_date_time' => $datetime,
            'quote_updated_by' => $employee_id,
        );

        $event360_enquiry_lead_quote = Event360EnquiryLeadQuote::create($data_event360_enquiry_lead_quote);

        syslog(LOG_INFO, '$event360_enquiry_required_sub_service_ids count -- '.count($event360_enquiry_required_sub_service_ids));

        $i = 0;
        foreach($event360_enquiry_required_sub_service_ids as $event360_enquiry_required_sub_service_id) {
            $data_event360_enquiry_lead_quote_item = array(
                'event360_enquiry_lead_quote_id' => $event360_enquiry_lead_quote->id,
                'event360_enquiry_required_sub_service_id' => $event360_enquiry_required_sub_service_id,
                'quote_amount' => $quote_amounts[$i],
                'quote_remarks_notes' => $quote_remarks_notes[$i],

            );

            Event360EnquiryLeadQuoteItem::create($data_event360_enquiry_lead_quote_item);

            $i++;
        }

        // update lead status if its still in Pending mode
        $lead = Lead::find($lead_id);
        $lead->update(array('status' => 'Accepted'));

        $vendor_name = $lead->organization->organization;

        if($attachment_files != null) {

            $i = 0;
            foreach($attachment_files as $attachment_file) {
                if($attachment_file != "") {
                    $attachment_title = $quote_attachment_titles[$i];

                    $file_name = 'event360_enquiry_lead_quote_attachment_' . $event360_enquiry_lead_quote->id . '_' . str_replace(" ", "_", $attachment_title) . '.' . $attachment_file->getClientOriginalExtension();

                    $file_save_data = GCSFileHandler::saveFile($attachment_file, $file_name);

                    $data_event360_enquiry_lead_quote_attachment = array(
                        'event360_enquiry_lead_quote_id' => $event360_enquiry_lead_quote->id,
                        'datetime' => $datetime,
                        'title' => $quote_attachment_titles[$i],
                        'gcs_file_url' => $file_save_data['gcs_file_url'],
                        'image_url' => $file_save_data['image_url'],
                        'status' => 1,
                    );
                    Event360EnquiryLeadQuoteAttachment::create($data_event360_enquiry_lead_quote_attachment);
                }

                $i++;

            }
        }

        // check if this is a new quotation if yes send the quotation alert if not send revised quote alert
        $no_of_quotes = Event360EnquiryLeadQuote::where('lead_id', $lead_id)->count();

        $event360_enquiry = $lead->event360Enquiry;

        if($no_of_quotes >1) {
            // revised quote alert

        } else {
            // new quote alert
            $subject = 'Event360 | You Have Received A Quotation | Request ID: '. $event360_enquiry->id;

            $mailBody = '';

            $email_sender = new EmailsController();

            $mailBody .= View::make('webtics_product.event360.email_templates.event_planner_email_alert_new_quotation_received', compact('event360_enquiry', 'vendor_name'))->render();

            $email_sender->sendEmail($subject, $mailBody, $event360_enquiry->event360EventPlannerProfile->email, "Event360 Team <noreply@event360.asia>");
        }
        

        return true;
    }

}