<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360EnquiryLeadQuoteAttachment extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_enquiry_lead_quote_attachments';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_enquiry_lead_quote_id',
        'datetime',
        'title',
        'gcs_file_url',
        'image_url',
        'status',

    ];

    public function event360EnquiryLeadQuote()
    {
        return $this->belongsTo('Event360EnquiryLeadQuote', 'event360_enquiry_lead_quote_id');
    }

}