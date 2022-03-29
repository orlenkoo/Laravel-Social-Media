<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/5/2016
 * Time: 3:31 PM
 */
class WebticsModule extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'module',
        'status'
    ];

    public static $status = array(0 => 'Inactive', 1 => 'Active');

    public function organizations() {
        return $this->belongsToMany('Organization', 'organization_webtics_module_assignments');
    }

    public function webticsProject() {
        return $this->belongsToMany('WebticsProject', 'webtics_project_webtics_module_assignments');
    }

    public static function getOrganizationModuleAccess($organization_id, $webtics_project_id, $module_ids_array) {

        $organization_webtics_module_assignments = OrganizationWebticsModuleAssignment::whereIn('webtics_module_id', $module_ids_array)->where('organization_id', $organization_id)->get();

        if(count($organization_webtics_module_assignments) > 0) {
            return true;
        }

        $webtics_project_webtics_module_assignments = WebticsProjectWebticsModuleAssignment::whereIn('webtics_module_id', $module_ids_array)->where('webtics_project_id', $webtics_project_id)->get();

        if(count($webtics_project_webtics_module_assignments) > 0) {
            return true;
        }

        return false;
    }


}