<?php

class OrganizationPreference extends \Eloquent
{

    protected $fillable = [
        'organization_id',
        'payment_terms',
        'terms_and_conditions',
        'tax_percentage',
        'logo_gcs_file_url',
        'logo_image_url',
        'send_quotation_for_approval',
        'send_quotation_follow_up_email_reminder'
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

}