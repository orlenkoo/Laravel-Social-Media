<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28/6/17
 * Time: 9:54 AM
 */
class QuotationItem extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = "quotation_items";

    // Don't forget to fill this array
    protected $fillable = [
        'quotation_id',
        'description',
        'unit_of_measure',
        'no_of_units',
        'unit_cost',
        'taxable',
        'tax',
        'cost'
    ];

    // relationships

    public function quotation()
    {
        return $this->belongsTo('Quotation', 'quotation_id');
    }





}