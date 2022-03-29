<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 06/05/2018
 * Time: 08:48
 */
class OrganizationPaymentMethod extends \Eloquent
{

    protected $table = "organization_payment_methods";

    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'credit_card_type',
        'credit_card_number',
        'expiry_month',
        'expiry_year',
        'cvv',
        'card_owner_name',
        'card_owner_address',
        'primary_card',
        'status',
    ];

    public static $status = array(
        'Pending' => 'Pending',
        'Expired' => 'Expired',
        'Valid' => 'Valid',
        'Invalid' => 'Invalid',
        'Failed' => 'Failed',
    );

    public static $credit_card_types = array(
        'Visa' => 'Visa',
        'Mastercard' => 'Mastercard',
        'American Express' => 'American Express',
    );

    public function organization() {
        $this->belongsTo('Organization', 'organization_id');
    }

    public function getDisplayCardNumber() {
        $cc_number = $this->credit_card_number;
        $display_number = "XXXX XXXX XXXX ";
        return $display_number.  substr($cc_number, -4);
    }



}