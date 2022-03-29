<?php

class Contact extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'customer_id',
        'salutation',
        'given_name',
        'surname',
        'designation',
        'phone_number',
        'other_phone_number',
        'mobile_number',
        'email',
        'contact_status', // Activated, Left, Transferred
        'primary_contact'
    ];

    public static $salutations = array(
        null => 'Select',
        'Mr.' => 'Mr.',
        'Ms.' => 'Ms.',
        'Mrs.' => 'Mrs.',
        'Dr.' => 'Dr.',
        'Prof.' => 'Prof.'
    );

    public static $contact_status = array('Activated' => 'Activated', 'Left' => 'Left', 'Transferred' => 'Transferred');

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function getContactFullName()
    {
        return $this->given_name . ' ' . $this->surname;
    }

    public static function getPrimaryContact($customer_id)
    {
        $primary_contact = Contact::where('primary_contact', 1)->where('customer_id', $customer_id)->first();

        return $primary_contact;
    }

    public static function getNumberOfContactsForGivenCustomer($customer){
        $contact_count = is_object($customer) ? sizeof($customer->contacts) : 0 ;
        return $contact_count;
    }

    public static function getCustomerContactsList($customer_id) {
        $contacts = DB::table('contacts')
            ->select(DB::raw('concat (given_name," ",surname, " <", email, ">") as full_name'))
            ->where('customer_id', $customer_id)
            ->lists('full_name');

        syslog(LOG_INFO, 'contacts -- ' . json_encode($contacts));

        if(count($contacts) > 0) {
            return implode(', ', $contacts);
        } else {
            return '';
        }


    }

}