<?php

class AuditTrail extends \Eloquent
{
    protected $table = 'audit_trails_web360_user';

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['employee_id', 'organization_id', 'page', 'action', 'datetime', 'type'];

    public static $pages = array(
        '' => 'Select',
        'SystemLogin' => 'SystemLogin',
        'Call' => 'Call',
        'Campaign' => 'Campaign',
        'CampaignUrl' => 'CampaignUrl',
        'CampaignMediaChannel' => 'CampaignMediaChannel',
        'Contact' => 'Contact',
        'Customer' => 'Customer',
        'Lead' => 'Lead',
        'LeadAssignedTo' => 'LeadAssignedTo',
        'Meeting' => 'Meeting',
        'Quotation' => 'Quotation',
        'QuotationItem' => 'QuotationItem',
        'Quoted' => 'Quoted',
        'QuotationAttachment' => 'QuotationAttachment',
        'MediaChannel' => 'MediaChannel',
        'OrganizationPaymentMethod' => 'OrganizationPaymentMethod',
        'OrganizationPreference' => 'OrganizationPreference',
        'Email' => 'Email',
        'EmailAttachment' => 'EmailAttachment',
        'LeadForward' => 'LeadForward',
        'LeadNote' => 'LeadNote',
        'Event360MessengerThreadMessage' => 'Event360MessengerThreadMessage',
        'Employee' => 'Employee',
        'Event360EnquiryLeadQuoteItem' => 'Event360EnquiryLeadQuoteItem',
        'Event360VendorProfile' => 'Event360VendorProfile',
        'Event360VendorImage' => 'Event360VendorImage',
        'Event360VendorTestimonial' => 'Event360VendorTestimonial',
        'Event360VendorService' => 'Event360VendorService',
        'Event360VendorProfileEventType' => 'Event360VendorProfileEventType',
    );

    public static function addAuditEntry($page, $action, $user_id = null)
    {
        date_default_timezone_set("Asia/Singapore");

        syslog(LOG_INFO, 'action audit -- ' . $action);

        $date = date("Y-m-d H:i:s");

        if($user_id == null) {
            $user_id = Session::get('user-id');
        }

        $organization_id = DB::table('employees')->where('id', $user_id)->pluck('organization_id');

        $data = array(
            'organization_id' => $organization_id,
            'employee_id' => $user_id,
            'page' => $page,
            'action' => $action,
            'datetime' => $date,
            'type' => 'webtics_product'
        );

        AuditTrail::create($data);

        /*DB::table('audit_trail')->insert(
            array('employee_id' => Session::get('user-id'), 'page' => $page, 'action' => $action, 'datetime' => $date)
        );*/
    }

    public static function getTotalTimeAccess($employee_id,$from_date,$to_date){

        syslog(LOG_INFO, "organization_id - ".Session::get('user-organization-id'));
        syslog(LOG_INFO, "employee_id - ".$employee_id);

        $total_times_access = AuditTrail::where('organization_id', Session::get('user-organization-id'))
            ->where('type','webtics_product')
            ->where('page','SystemLogin')
            ->whereBetween('datetime' , array($from_date, $to_date))
            ->where('employee_id',$employee_id)->count();

        return $total_times_access;
    }

    public static function getLastAccessDateTime($employee_id,$from_date,$to_date){

        syslog(LOG_INFO, "organization_id - ".Session::get('user-organization-id'));
        syslog(LOG_INFO, "employee_id - ".$employee_id);

        $last_login_audit = AuditTrail::orderBy('id','desc')->where('organization_id', Session::get('user-organization-id'))
            ->where('type','webtics_product')
            ->where('page','SystemLogin')
            ->whereBetween('datetime' , array($from_date, $to_date))
            ->where('employee_id',$employee_id)->first();

        return (is_object($last_login_audit) ? $last_login_audit->datetime : '');

    }

    public function employee()
    {
        return $this->belongsTo('Employee', 'employee_id');
    }

}