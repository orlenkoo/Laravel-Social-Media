<?php

class OrganizationConfigurationMapping extends \Eloquent
{

    protected $fillable = [
        'organization_configuration_id',
        'organization_id',
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function organizationConfiguration()
    {
        return $this->belongsTo('OrganizationConfiguration', 'organization_configuration_id');
    }

}