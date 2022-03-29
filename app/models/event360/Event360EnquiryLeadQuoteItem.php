<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360EnquiryLeadQuoteItem extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_enquiry_lead_quote_items';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_enquiry_lead_quote_id',
        'event360_enquiry_required_sub_service_id',
        'quote_amount',
        'quote_remarks_notes',

    ];

    public function event360EnquiryLeadQuote()
    {
        return $this->belongsTo('Event360EnquiryLeadQuote', 'event360_enquiry_lead_quote_id');
    }

    public function event360EnquiryRequiredSubService()
    {
        return $this->belongsTo('Event360EnquiryRequiredSubService', 'event360_enquiry_required_sub_service_id');
    }





}