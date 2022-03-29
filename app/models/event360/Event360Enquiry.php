<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360Enquiry extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_enquiries';

    // Don't forget to fill this array
    protected $fillable = [
        'display_enquiry_request_id',
        'completed_enquiry_step',
        'event360_event_planner_profile_id',
        'given_name',
        'surname',
        'company_name',
        'job_title',
        'email',
        'phone_number',
        'json_shortlisted_vendors',
        'event_date',
        'event_venue_rental_required',
        'food_and_beverage_required',
        'event360_event_type_id',
        'quote_submission_deadline',
        'event_start_time',
        'event_end_time',
        'pax_min',
        'pax_max',
        'type',
        'webtics_pixel_property_id',
        'webtics_pixel_session_id',
        'source',
        'medium',
    ];

    public static $type = array(
        'direct_to_event360' => 'Direct to Event360',
        'single_vendor_selected' => 'Single Vendor Selected',
        'multiple_vendor_selected' => 'Multiple Vendor Selected'
    );


    public function event360EnquiryRequiredServices()
    {
        return $this->hasMany('Event360EnquiryRequiredService');
    }

    public function event360EventPlannerProfile()
    {
        return $this->belongsTo('Event360EventPlannerProfile', 'event360_event_planner_profile_id');
    }

    public function event360EventType()
    {
        return $this->belongsTo('Event360EventType', 'event360_event_type_id');
    }

    public function event360EnquiryFoodAndBeverageRequirements()
    {
        return $this->hasMany('Event360EnquiryFoodAndBeverageRequirement');
    }

    public function event360EnquiryVenueTypes()
    {
        return $this->hasMany('Event360EnquiryVenueType');
    }

    public function event360EnquiryVenueLocations()
    {
        return $this->hasMany('Event360EnquiryVenueLocation');
    }


    public function leads()
    {
        return $this->hasMany('Lead');
    }


    public static function getRequiredServicesForTheEnquiry($enquiry_id)
    {

        $event360_enquiry_required_services_list = array();
        $event360_enquiry_required_services = Event360EnquiryRequiredService::where('event360_enquiry_id', $enquiry_id)->get();
            if($event360_enquiry_required_services->count() > 0) {
                foreach ($event360_enquiry_required_services as $event360_enquiry_required_service) {
                    if (is_object($event360_enquiry_required_service->event360ServiceCategory)) {
                        $event360_enquiry_required_services_list[] = $event360_enquiry_required_service->event360ServiceCategory->service_category;
                    }
                }
            }else{
                $event360_enquiry_required_services_list[] = 'NA';
            }
        return implode(', ', $event360_enquiry_required_services_list);
    }

    public static function checkIfVendorSubServiceSelected($event360_enquiry_required_sub_services, $vendor_sub_service_categories)
    {
        foreach($event360_enquiry_required_sub_services as $event360_enquiry_required_sub_service) {
            if(in_array($event360_enquiry_required_sub_service->event360SubServiceCategory->id, $vendor_sub_service_categories)) {
                return true;
            }
        }

        return false;
    }

    public static function checkIfVenueOwnerConditionsAreMet($lead) {
        // 1st check if same venue type
        $event360_vendor_profile = $lead->organization->event360VendorProfile;
        $vendor_venue_type = $event360_vendor_profile->event360_venue_type_id;

        syslog(LOG_INFO, '$vendor_venue_type -- ' . $vendor_venue_type);

        $event360_enquiry = $lead->event360Enquiry;
        foreach($event360_enquiry->event360EnquiryVenueTypes as $event360_enquiry_venue_type) {
            if($event360_enquiry_venue_type->id == $vendor_venue_type){
                return true;
            }
        }

        // next check venue location
        $vendor_venue_location = $lead->organization->location_id;
        syslog(LOG_INFO, '$vendor_venue_location -- ' . $vendor_venue_location);
        foreach($event360_enquiry->event360EnquiryVenueLocations as $event360_enquiry_venue_location) {
            if($event360_enquiry_venue_location->event360_location_id == $vendor_venue_location) {
                return true;
            }
        }

        return false;

    }

}