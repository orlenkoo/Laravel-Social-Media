<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360VendorProfile extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_vendor_profiles';

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'primary_service_category_id',
        'about_us',
        'featured_website_home_page',
        'featured_website_results_page',
        'logo_gcs_file_url',
        'logo_image_url',
        'facebook_url',
        'map_url',
        'event_services_provider',
        'event_venue_owner',
        'venue_capacity',
        'event360_venue_type_id',
        'advertiser',
    ];

    public static $vendor_image_service_category_mapping = array(
        'Audio Visual & Lightings' => 'av',
        'Display Design, Print & Build' => 'display-design-and-print-build',
        'Entertainment - Activities' => 'entertainment-activities',
        'Entertainment - Costume Rental' => 'entertainment-costume-rental',
        'Entertainment - Talents & Performance' => 'entertainment-talents-and-performance',
        'Event Furniture & Fittings' => 'event-furniture-and-fittings',
        'Event Planning & Management' => 'event-planning-and-management',
        'Food & Beverage' => 'food-and-beverage',
        'Gifts & Accessories' => 'gift-and-accessories',
        'Other Event Services' => 'other-event-services',
        'Outdoor Equipment' => 'outdoor-equipment',
        'Photography & Videography' => 'photography-and-videography',
        'Event Venue' => 'event-venue',
    );

    public static $image_upload_file_size_limit = array(
        'value' => 500, //value in KB
        'message' => 'Image size limit is 500 KB, please resize and upload your images.'
    );

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function event360ServiceCategory()
    {
        return $this->belongsTo('Event360ServiceCategory', 'primary_service_category_id');
    }

    public function event360VenueType()
    {
        return $this->belongsTo('Event360VenueType', 'event360_venue_type_id');
    }

    public function event360VendorProfilePrimarySubServiceCategories()
    {
        return $this->hasMany('Event360VendorProfilePrimarySubServiceCategory');
    }

    public function event360VendorProfileSecondaryServiceCategories()
    {
        return $this->hasMany('Event360VendorProfileSecondaryServiceCategory');
    }

    public function event360VendorProfileSecondarySubServiceCategories()
    {
        return $this->hasMany('Event360VendorProfileSecondarySubServiceCategory');
    }

    public function event360MessengerThreads()
    {
        return $this->hasMany('Event360MessengerThread');
    }

    public function event360VendorImages()
    {
        return $this->hasMany('Event360VendorImage');
    }

    public function event360VendorServices()
    {
        return $this->hasMany('Event360VendorService');
    }

    public function event360VendorTestimonials()
    {
        return $this->hasMany('Event360VendorTestimonial');
    }

    public function event360VendorProfileChanges()
    {
        return $this->hasMany('Event360VendorProfileChanges');
    }

    public function event360VendorProfileEventTypes()
    {
        return $this->hasMany('Event360VendorProfileEventType');
    }

    public function event360VendorProfileVenueHighlights()
    {
        return $this->hasMany('Event360VendorProfileVenueHighlight');
    }

    public function event360VendorFoodAndBeverageRequirements()
    {
        return $this->hasMany('Event360VendorFoodAndBeverageRequirement');
    }

    public static function getServiceCategoriesForProfile($id)
    {

        $event360_vendor_profile = Event360VendorProfile::find($id);

        if(is_object($event360_vendor_profile->event360ServiceCategory)) {
            $array_service_category_list[] = $event360_vendor_profile->event360ServiceCategory->service_category;
            $secondary_service_categories = $event360_vendor_profile->event360VendorProfileSecondaryServiceCategories;
            foreach($secondary_service_categories as $secondary_service_category) {
                $array_service_category_list[] = $secondary_service_category->event360ServiceCategory->service_category;
            }

            return implode(', ', $array_service_category_list);
        }

    }

    public static function getAddressByOrganization($id) {
        $organization = Organization::find($id);
        $address = array();
        if($organization->address_line_1 != '') {
            $address[] = $organization->address_line_1;
        }

        if($organization->address_line_2 != '') {
            $address[] = $organization->address_line_2;
        }

        if($organization->city != '') {
            //$address[] = $organization->city;
        }

        if($organization->state != '') {
            //$address[] = $organization->state;
        }

        if($organization->country != '') {
            $address[] = $organization->country;
        }

        if($organization->postal_code != '') {
            $address[] = $organization->postal_code;
        }

        return implode(', ', $address);

    }

    public static function checkIfAdvertizer($organization_id)
    {
        $advertizer = Event360VendorProfile::where('organization_id',$organization_id)->pluck('advertiser');
        if($advertizer == 1) return true;
        return false;

    }

    public static function getSubServicesListProvidedByVendor($event360_vendor_profile_id)
    {

        $vendor_primary_sub_service_categories = Event360VendorProfilePrimarySubServiceCategory::where('event360_vendor_profile_id', $event360_vendor_profile_id)->lists('event360_sub_service_category_id');

        $vendor_secondary_sub_service_categories = Event360VendorProfileSecondarySubServiceCategory::where('event360_vendor_profile_id', $event360_vendor_profile_id)->lists('event360_sub_service_category_id');

        $vendor_sub_service_categories = array_merge($vendor_primary_sub_service_categories, $vendor_secondary_sub_service_categories);

        return $vendor_sub_service_categories;

    }

}