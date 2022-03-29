<?php

class CustomerTimeLineItem extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = "customer_time_line_items";

    // Don't forget to fill this array
    protected $fillable = [
        'customer_id',
        'time_line_item_source',
        'time_line_item_source_id',
        'datetime'
    ];

    public static $time_line_item_source = array(
        'Meeting' => 'Meeting',
        'Call' => 'Call',
        'Email' => 'Email',
        'Quotation' => 'Quotation',
    );

    public static $customer_time_line_classes = array(
        'Meeting' => 'activity-meeting',
        'Call' => 'activity-call',
        'Email' => 'activity-email',
        'Quotation' => 'activity-quotation',
    );

    // relationships

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function call()
    {
        return $this->belongsTo('Call', 'time_line_item_source_id');
    }

    public function email()
    {
        return $this->belongsTo('Email', 'time_line_item_source_id');
    }

    public function meeting()
    {
        return $this->belongsTo('Meeting', 'time_line_item_source_id');
    }

    public function quotation()
    {
        return $this->belongsTo('Quotation', 'time_line_item_source_id');
    }





}