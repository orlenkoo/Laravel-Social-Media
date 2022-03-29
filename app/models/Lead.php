<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class Lead extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [];

    protected $table = 'leads';

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'customer_id',
        'campaign_id',
        'assigned_to',
        'last_assignment_datetime',
        'datetime',
        'lead_source',
        'lead_source_id',
        'lead_capture_method',
        'status',
        'status_updated_datetime',
        'status_updated_by',
        'lead_rating',
        'lead_rating_updated_datetime',
        'lead_rating_updated_by',
        'auto_tagged_campaign',
        'utm_source',
        'utm_medium',
        'utm_term',
        'utm_campaign',
        'utm_content',
        'gclid',
        'fbclid',
        'source_url',
        'webtics_pixel_session_id',
        'webtics_pixel_property_id',
    ];

    public static $lead_status = array(
        'Pending' => 'Pending',
        'Accepted' => 'Accepted'
    );

    // to load customized lead status for event360
    public static $event360_lead_status = array(
        'Pending' => 'Pending',
        'Accepted' => 'Quoted'
    );


    public static $lead_sources = array(
        'event360_enquiries' => 'Event360 Enquiry',
        'event360_messenger_threads' => 'Message',
        'event360_calls' => 'Call',
        'web360_enquiries' => 'Website',
        'direct' => 'Direct',
        'edm' => 'Email',
        'novocall_leads' => 'Novocall Lead',
    );

    public static $lead_ratings = array(
        '' => 'Select',
        'Raw Lead' => 'Raw Lead', // This is the status of a lead when a lead comes in automatically
        'Lead' => 'Lead', // This is a qualified Lead
        'Junk' => 'Junk', // This is a lead that has been trashed due to either incomplete information or
        'Duplicate' => 'Duplicate',
        'Quoted' => 'Quoted',
        'Quoted - Negotiation' => 'Quoted - Negotiation',
        'Quoted - Won' => 'Quoted - Won',
        'Quoted - Lost' => 'Quoted - Lost',
    );

    public static $editable_lead_ratings = array(
        '' => 'Select',
        'Raw Lead' => 'Raw Lead', // This is the status of a lead when a lead comes in automatically
        'Lead' => 'Lead', // This is a qualified Lead
        'Junk' => 'Junk', // This is a lead that has been trashed due to either incomplete information or
        'Duplicate' => 'Duplicate',
    );

    public static $sales_lead_ratings = array(
        '' => 'Select',
        'Lead' => 'Lead',
        'Junk' => 'Junk',
    );
    public static $lead_ratings_classes = array(
        '' => 'Select',
        'Raw Lead' => 'rating-raw-lead', // This is the status of a lead when a lead comes in automatically
        'Lead' => 'rating-lead', // This is a qualified Lead
        'Junk' => 'rating-junk', // This is a lead that has been trashed due to either incomplete information or
        'Duplicate' => 'rating-duplicate',
        'Quoted' => 'rating-quoted',
        'Quoted - Negotiation' => 'rating-quoted-negotiation',
        'Quoted - Won' => 'rating-quoted-won',
        'Quoted - Lost' => 'rating-quoted-lost',
    );

    public static $lead_meta_data_types = [
        'Company Name' => 'Company Name',
        'First Name' => 'First Name',
        'Last Name' => 'Last Name',
        'Email' => 'Email',
        'Phone Number' => 'Phone Number',
        'Website' => 'Website',
        'Note' => 'Note'
    ];

    public static function boot()
    {
        parent::boot();

        // Setup event bindings...

        // Lead::creating(function ($lead) {
        //     // send sms alerts
        //     Lead::sendLeadSMSAlert($lead);
        // });
    }

    /**
     * @param $data_lead
     * @param $lead
     */
    public static function updateCampaignAutoTagForLead($data_lead, $lead)
    {
        syslog(LOG_INFO, 'utm_source -- ' . $lead->utm_source);
        syslog(LOG_INFO, 'utm_medium -- ' . $lead->utm_medium);
        syslog(LOG_INFO, 'gclid -- ' . $lead->gclid);
        syslog(LOG_INFO, 'lead_source -- ' . $lead->lead_source);
        syslog(LOG_INFO, 'Direct -- ' . strpos(trim(strtoupper($lead->utm_source)), 'DIRECT'));
        syslog(LOG_INFO, 'INSTAGRAM -- ' . strpos(trim(strtoupper($lead->utm_source)), 'INSTAGRAM'));


        $data_lead['auto_tagged_campaign'] = '';

        // logic to tag the lead campaign
        if (strpos(trim(strtoupper($lead->utm_source)), 'DIRECT') !== false && ($lead->utm_medium == '' || strpos(trim(strtoupper($lead->utm_medium)), 'NONE') !== false)) {
            $data_lead['auto_tagged_campaign'] = 'Direct Website';
        } else if (strpos(trim(strtoupper($lead->utm_source)), 'GOOGLE') !== false && strpos(trim(strtoupper($lead->utm_medium)), 'REFERRAL') !== false) {
            $data_lead['auto_tagged_campaign'] = 'Google Organic Campaign';
        } else if ($lead->gclid != '' || (strpos(trim(strtoupper($lead->utm_source)), 'GOOGLE') !== false && in_array(trim(strtoupper($lead->utm_medium)), ['CPC', 'CPM']))) {
            $data_lead['auto_tagged_campaign'] = 'Google Ad Campaign';
        } else if ((strpos(trim(strtoupper($lead->utm_source)), 'FACEBOOK') !== false || (strpos(trim(strtoupper($lead->utm_source)), 'INSTAGRAM') !== false)) && in_array(trim(strtoupper($lead->utm_medium)), ['CPC', 'CPM'])) {
            $data_lead['auto_tagged_campaign'] = 'Facebook Ad Campaign';
        } else if (strpos(trim(strtoupper($lead->utm_source)), 'FACEBOOK') !== false && trim(strtoupper($lead->utm_medium)) == 'REFERRAL') {
            $data_lead['auto_tagged_campaign'] = 'Facebook Referral';
        } else if (trim(strtoupper($lead->utm_source)) == 'NEWSLETTER') {
            $data_lead['auto_tagged_campaign'] = 'Newsletter Campaign';
        } else if (trim(strtoupper($lead->utm_source)) == 'EDM') {
            $data_lead['auto_tagged_campaign'] = 'EDM Campaign';
        } else if (strpos(trim(strtoupper($lead->utm_source)), 'SMS') !== false) {
            $data_lead['auto_tagged_campaign'] = 'SMS Campaign';
        } else if (strpos(trim(strtoupper($lead->utm_source)), 'EVENT') !== false) {
            $data_lead['auto_tagged_campaign'] = 'Event';
        } else if (strpos(trim(strtoupper($lead->utm_medium)), 'REFERRAL') !== false) {
            $data_lead['auto_tagged_campaign'] = 'Referral';
        } else if ($lead->lead_source == 'event360_calls') {
            $data_lead['auto_tagged_campaign'] = Campaign::getCampaignForLeadAutoTagging($lead->organization_id, "call_tracking_number", is_object($lead->event360Call) ? $lead->event360Call->number1300 : "");
        } else {
            $data_lead['auto_tagged_campaign'] = 'Others';
        }

        syslog(LOG_INFO, 'auto_tagged_campaign -- ' . $data_lead['auto_tagged_campaign']);

        $lead->update($data_lead);
    }

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function campaign()
    {
        return $this->belongsTo('Campaign', 'campaign_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo('Employee', 'assigned_to');
    }

    public function event360Enquiry()
    {
        return $this->belongsTo('Event360Enquiry', 'lead_source_id');
    }

    public function event360Call()
    {
        return $this->belongsTo('Event360Call', 'lead_source_id');
    }

    public function web360Enquiry()
    {
        return $this->belongsTo('Web360Enquiry', 'lead_source_id');
    }

    public function event360MessengerThread()
    {
        return $this->belongsTo('Event360MessengerThread', 'lead_source_id');
    }

    public function novocallLead()
    {
        return $this->belongsTo('NovocallLead', 'lead_source_id');
    }

    public function webticsPixelProperty()
    {
        return $this->belongsTo('WebticsPixelProperty', 'webtics_pixel_property_id');
    }

    public function statusUpdatedBy()
    {
        return $this->belongsTo('Employee', 'status_updated_by');
    }

    public function leadRatingUpdatedBy()
    {
        return $this->belongsTo('Employee', 'lead_rating_updated_by');
    }

    public function leadViews()
    {
        return $this->hasMany('LeadView');
    }

    public function leadRatingUpdates()
    {
        return $this->hasMany('LeadRatingUpdate');
    }

    public function leadNotes()
    {
        return $this->hasMany('LeadNote');
    }

    public function leadForwards()
    {
        return $this->hasMany('LeadForward');
    }

    public function event360EnquiryLeadQuotes()
    {
        return $this->hasMany('Event360EnquiryLeadQuote');
    }

    public static function createLead($data_lead)
    {

        $lead = Lead::create($data_lead);

        self::updateCampaignAutoTagForLead($data_lead, $lead);

        // TODO: Add notifications

        return $lead;
    }

    public static function getPixelReportFilter($filter)
    {

        $organization_id = Session::get('user-organization-id');

        $filter_data = DB::table('leads')
            ->select(DB::raw('DISTINCT(' . $filter . ') as filter'))
            ->where($filter, '!=', '')
            ->where('organization_id', '=', $organization_id)
            ->lists('filter', 'filter');

        $filter_data =  ['' => 'Please select'] + $filter_data;
        return $filter_data;
    }

    public static function forwardLead($lead, $to_email, $message, $employee_id)
    {
        date_default_timezone_set("Asia/Singapore");
        $lead_forward = LeadForward::create(
            array(
                'lead_id' => $lead->id,
                'email' => $to_email,
                'message' => $message,
                'lead_forwarded_on' => date('Y-m-d H:i:s'),
                'lead_forwarded_by' => $employee_id,
            )
        );

        $email_sender = new EmailsController();

        $subject = "Sharing Event360 Lead Details";
        $mailBody = View::make('webtics_product.event360.email_templates.forward_lead_details_by_vendor', compact('lead', 'lead_forward'))->render();


        $email_sender->sendEmail($subject, $mailBody, $to_email);

        return $lead_forward;
    }

    public static function updateLeadRating($lead, $lead_rating, $employee_id)
    {
        date_default_timezone_set("Asia/Singapore");
        $lead->update(
            array(
                'lead_rating' => $lead_rating,
                'lead_rating_updated_datetime' => date('Y-m-d H:i:s'),
                'lead_rating_updated_by' => $employee_id,
            )
        );

        $lead_rating_object = LeadRatingUpdate::create(
            array(
                'lead_id' => $lead->id,
                'lead_rating' => $lead_rating,
                'lead_rating_updated_on' => date('Y-m-d H:i:s'),
                'lead_rating_updated_by' => $employee_id,
            )
        );

        return $lead_rating_object;
    }

    public static function sendLeadSMSAlert($lead)
    {

        try {
            // get employees for this lead that has lead alert enabled
            $employees = Employee::where('organization_id', $lead->organization_id)
                ->where('receive_sms_alert', 1)
                ->where('status', 1)
                ->get();

            // get lead details based on lead type to generate the message
            $sms_message = "[LEAD360] : $lead->id : You have received a new lead at $lead->datetime \n ";
            if ($lead->lead_source == 'web360_enquiries') {
                $web360_enquiry = Web360Enquiry::find($lead->lead_source_id);
                if (is_object($web360_enquiry)) {
                    $enquiry_details = json_decode($web360_enquiry->enquiry_details);
                    if (is_object($enquiry_details)) {
                        foreach ($enquiry_details as $key => $value) {
                            $sms_message .= ucwords(str_replace('_', ' ', json_encode($key))) . ": " . json_encode($value) . " \n ";
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else if ($lead->lead_source == 'direct') {
                $sms_message .= "Customer Name: " . is_object($lead->customer) ? $lead->customer->customer : "Not Set" . "\n ";
            }

            foreach ($employees as $employee) {

                $country_code = isset($employee->country_code) ? $employee->country_code : '+65';

                SMSGatewayHandler::sendSMS($country_code . '' . $employee->contact_no, $sms_message);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, 'Caught exception: ' .  $e->getMessage());
        }
    }

    public function getLeadDetailsCommaSeparated($lead)
    {

        if ($lead->lead_source == 'event360_enquiries') {
        } else if ($lead->lead_source == 'event360_messenger_threads') {
        } else if ($lead->lead_source == 'event360_calls') {
        } else if ($lead->lead_source == 'web360_enquiries') {
        } else if ($lead->lead_source == 'direct') {
        } else if ($lead->lead_source == 'edm') {
        }
    }

    public static function autoCategoryTag($lead)
    {

        $lead_content = '';

        if ($lead->lead_source == 'web360_enquiries') {
            $web360_enquiry = Web360Enquiry::find($lead->lead_source_id);
            if (is_object($web360_enquiry)) {
                $enquiry_details = json_decode($web360_enquiry->enquiry_details);
                if (is_object($enquiry_details)) {
                    foreach ($enquiry_details as $key => $value) {
                        $lead_content .= ucwords(str_replace('_', ' ', json_encode($key))) . ": " . json_encode($value) . " \n ";
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        $categories = GoogleCloudNaturalLanguageUtilities::getContentClassification($lead_content);

        if (count($categories)) {
            foreach ($categories as $category) {

                if ($category['confidence'] >= 0.5) {
                    LeadAutoCategoryTag::create([
                        'lead_id' => $lead->id,
                        'category_tag' => $category['name']
                    ]);
                }
            }
        }
    }
}
