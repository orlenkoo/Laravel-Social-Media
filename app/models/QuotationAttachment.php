<?php


class QuotationAttachment extends \Eloquent
{

    protected $fillable = [
        'quotation_id',
        'title',
        'description',
        'quotation_gcs_file_url',
        'quotation_file_url'
    ];

    // relationships

    public function quotation()
    {
        return $this->belongsTo('Quotation', 'quotation_id');
    }





}