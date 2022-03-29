<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/1/2016
 * Time: 9:00 PM
 */
class CountriesController extends BaseController
{

    public function ajaxLoadCountries()
    {
        
        $countries = Country::select(DB::raw('country ,id'))->orderBy('country', 'asc')->get();
        $countries_json = json_encode($countries);
        return Response::make($countries_json);
    }
    
}