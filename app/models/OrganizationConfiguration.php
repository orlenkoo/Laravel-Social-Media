<?php

class OrganizationConfiguration extends \Eloquent
{

    // all configurations are negative, by default everyone gets everything
    protected $fillable = [
        'configuration',
        'status',
    ];

    protected $table = "organization_configurations";

    public function organizationConfigurationMappings() {
        return $this->hasMany('OrganizationConfigurationMapping');
    }

    public static function checkIfConfigurationDisabled($configuration_id) {

        if(!in_array($configuration_id, Session::get('organization-configurations'))) {
            return 1;
        }
        return 0;
    }

}