<?php

/**
 * Created by PhpStorm.
 * User: Kasun
 * Date: 18/05/2016
 * Time: 07:48
 */
class WebticsPixelProperty extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'webtics_pixel_properties';

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'property_name',
        'website_url',
        'microsite',
        'webtics_project_id',
        'status'
    ];


    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function webticsProject()
    {
        return $this->belongsTo('WebticsProject', 'webtics_project_id');
    }

    public function web360Enquiries()
    {
        return $this->hasMany('Web360Enquiry');
    }

    public function leads()
    {
        return $this->hasMany('Lead');
    }

    // get webtics pixel property ids for a vendor other than microsite (own website codes)
    public static function getWebticsPixelPropertyIdsByOrganization($organization_id){


            $webtics_pixel_microsite_property_ids = DB::table('webtics_pixel_properties')
                ->where('organization_id', '=', $organization_id)
                ->where('microsite', '=', 0)
                ->lists('id');


        return $webtics_pixel_microsite_property_ids;
    }

    public static function getEvent360WebticsPixelPropertyIdForOrganization($organization_id)
    {
        $event360_webtics_pixel_property = WebticsPixelProperty::where('organization_id', $organization_id)
            ->where('microsite', 1)
            ->where('webtics_project_id', Config::get('project_vars.event360_project_id'))
            ->first();

        if(is_object($event360_webtics_pixel_property)) {
            return $event360_webtics_pixel_property->id;
        }

        return '';
    }

    public static function getWebticsPixelPropertyNames(){

        $organization_id = Session::get('user-organization-id');

        $webtics_pixel_microsite_property = DB::table('webtics_pixel_properties')
            ->where('organization_id', '=', $organization_id)
            ->where('microsite', '=', 0)
            ->lists('property_name','id');

        return $webtics_pixel_microsite_property;
    }

}