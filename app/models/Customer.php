<?php

class Customer extends \Eloquent {

    // Add your validation rules here
	public static $rules = [
		// 'title' => 'required'


	];

	// Don't forget to fill this array
	protected $fillable = [
        'organization_id',
        'account_owner_id',
        'customer_name',
        'industry_id', // to be removed
        'address_line_1',
        'address_line_2',
        'city',
        'postal_code',
        'state',
        'country_id',
        'phone_number',
        'fax_number',
        'website',
        'business_registration_number',
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function accountOwner() {
        return $this->belongsTo('Employee', 'account_owner_id');
    }

    public function industry() {
        return $this->belongsTo('Industry', 'industry_id');
    }

    public function country() {
        return $this->belongsTo('Country', 'country_id');
    }

    public function contacts()
    {
        return $this->hasMany('Contact');
    }

    public function quotations()
    {
        return $this->hasMany('Quotation');
    }

    public function leads()
    {
        return $this->hasMany('Lead');
    }

    public function customerTagAssignments()
    {
        return $this->hasMany('CustomerTagAssignment');
    }

    public function calls()
    {
        return $this->hasMany('Call');
    }

    public function meetings()
    {
        return $this->hasMany('Meeting');
    }

    public function emails()
    {
        return $this->hasMany('Email');
    }


    public function getAddress()
    {
        $customer_address = array();

        if($this->address_line_1 != '') {
            $customer_address[] = $this->address_line_1;
        }

        if($this->address_line_2 != '') {
            $customer_address[] = $this->address_line_2;
        }

        if($this->city != '') {
            $customer_address[] = $this->city;
        }

        if($this->postal_code != '') {
            $customer_address[] = $this->postal_code;
        }

        if($this->state != '') {
            $customer_address[] = $this->state;
        }

        if($this->country_id != '' && $this->country_id != null) {
            $customer_address[] = $this->country->country;
        }

        return implode(", ", $customer_address);
    }

    public static function getCustomerTagsCommaSeparated($customer_id){

        $customer_tag_ids = CustomerTagAssignment::where('customer_id',$customer_id)->lists('customer_tag_id');
        $customer_tags = CustomerTag::whereIn('id',$customer_tag_ids)->lists('tag');
        return implode(', ', $customer_tags);
    }

}