<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 06/05/2018
 * Time: 08:48
 */
class Web360OrganizationInvoiceDetailRecord extends \Eloquent
{

    protected $table = "web360_organization_invoice_detail_records";

    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'web360_organization_invoice_master_record_id',
        'item',
        'description',
        'amount',
    ];

    public function web360OrganizationInvoiceMasterRecord() {
        return $this->belongsTo('Web360OrganizationInvoiceMasterRecord', 'web360_organization_invoice_master_record_id');
    }

}