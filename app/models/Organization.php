<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/5/2016
 * Time: 3:31 PM
 */
class Organization extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'organization',
        'business_registration_number',
        'domain_name',
        'google_apps_available',
        'language',
        'logo',
        'primary_color',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'postal_code',
        'location_id',
        'phone_number_country_code',
        'phone_number_area_code',
        'phone_number',
        'fax_number_country_code',
        'fax_number_area_code',
        'fax_number',
        'email',
        'website_url',
        'use_google_industry_list',
        'delacon_call_tracking_customer_name',
        'delacon_call_tracking_number',
        'status'
    ];

    public function customers()
    {
        return $this->hasMany('Customer');
    }

    public function employees()
    {
        return $this->hasMany('Employee');
    }

    public function leads()
    {
        return $this->hasMany('Lead');
    }

    public function userLevels()
    {
        return $this->hasMany('UserLevel');
    }

    public function industries()
    {
        return $this->hasMany('Industry');
    }

    public function designations()
    {
        return $this->hasMany('Designation');
    }

    public function employeeScreenPermissions()
    {
        return $this->hasMany('EmployeeScreenPermission');
    }

    public function userLevelActionPermissions()
    {
        return $this->hasMany('UserLevelActionPermission');
    }

    public function attachmentTypes()
    {
        return $this->hasMany('AttachmentType');
    }

    public function teams()
    {
        return $this->hasMany('Team');
    }

    public function campaigns()
    {
        return $this->hasMany('Campaign');
    }

    public function mediaChannels()
    {
        return $this->hasMany('MediaChannel');
    }

    public function event360VendorProfile()
    {
        return $this->hasOne('Event360VendorProfile');
    }

    public function event360Location()
    {
        return $this->belongsTo('Event360Location', 'location_id');
    }

    public function delaconProperties()
    {
        return $this->hasMany('DelaconProperty');
    }

    public function organizationPreferences()
    {
        return $this->hasOne('OrganizationPreference');
    }

    public function organizationConfigurationMappings() {
        return $this->hasMany('OrganizationConfigurationMapping');
    }

    public function customerTags()
    {
        return $this->hasMany('CustomerTag');
    }

    public function web360ModuleOrganizationAssignments() {
        $this->hasMany('Web360ModuleOrganizationAssignment');
    }

    public function web360OrganizationInvoiceMasterRecords() {
        $this->hasMany('Web360OrganizationInvoiceMasterRecord');
    }

    public function organizationPaymentMethods() {
        $this->hasMany('OrganizationPaymentMethod');
    }

    public static function getProjectIds($organization_id) {
        $webtics_project_ids = DB::table('webtics_project_organization_mappings')->where('organization_id', $organization_id)->where('status', 1)->lists('webtics_project_id');
        return $webtics_project_ids;
    }

    public static function getOrganizationContactPersons($organization_id)
    {
        $organization_contact_persons = Employee::where('organization_id', $organization_id)
            ->where('project_contact_person', 1)
            ->get();
        return $organization_contact_persons;
    }

    public function getAddress()
    {
        $organization_address = array();

        if($this->address_line_1 != '') {
            $organization_address[] = $this->address_line_1;
        }

        if($this->address_line_2 != '') {
            $organization_address[] = $this->address_line_2;
        }

        if($this->city != '') {
            $organization_address[] = $this->city;
        }

        if($this->postal_code != '') {
            $organization_address[] = $this->postal_code;
        }

        if($this->state != '') {
            $organization_address[] = $this->state;
        }

        if($this->country_id != '' && $this->country_id != null) {
            $organization_address[] = $this->country->country;
        }

        return implode(", ", $organization_address);
    }

}