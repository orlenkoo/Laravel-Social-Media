<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 06/05/2018
 * Time: 08:48
 */
class Web360OrganizationInvoiceMasterRecord extends \Eloquent
{

    protected $table = "web360_organization_invoice_master_records";

    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'invoice_number',
        'invoice_date',
        'title',
        'gross_total',
        'taxes',
        'discount',
        'net_total',
        'status',
    ];

    public static $status = array(
        'Pending' => 'Pending',
        'Paid' => 'Paid',
        'Failed' => 'Failed',
        'Overdue' => 'Overdue',
        'Cancelled' => 'Cancelled',
    );

    public function organization() {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function web360OrganizationInvoiceDetailRecords() {
        return $this->hasMany('Web360OrganizationInvoiceDetailRecord');
    }

}