<?php

/**
 * Created by PhpStorm.
 * User: DeAlwis
 * Date: 9/1/2016
 * Time: 1:07 PM
 */
class Web360PixelReportController extends BaseController
{

    /**
     * Widgets Functions
     **/

    // Visits BreakDown By Medium
    public function getVisitsBreakDownByMedium(){

        header('Access-Control-Allow-Origin: *');

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date'))
        {
            $start_date = Input::get('start_date');
        }
        if(Input::has('end_date'))
        {
            $end_date = Input::get('end_date');
        }

        $webtics_pixel_property = Input::get('webtics_pixel_property');

        $widget_data_generator = new WidgetDataGenerator();
        $result = $widget_data_generator->getWeb360VisitsBreakDownByMedium($start_date,$end_date,$webtics_pixel_property);

        return $result;
    }

    // Leads Breakdown by Medium
    public function getLeadsBreakdownbyMedium(){

        header('Access-Control-Allow-Origin: *');

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date'))
        {
            $start_date = Input::get('start_date');
        }
        if(Input::has('end_date'))
        {
            $end_date = Input::get('end_date');
        }

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $webtics_pixel_property = Input::get('webtics_pixel_property');

        $widget_data_generator = new WidgetDataGenerator();
        $web360_leads_breakdown_by_medium_detail_array = $widget_data_generator->getWeb360LeadsBreakdownByMedium($start_date,$end_date,$webtics_pixel_property,$organization_id);

        return $web360_leads_breakdown_by_medium_detail_array;
    }

    // Lead Conversion Rate By Medium
    public function getLeadConversionRateByMedium(){

        header('Access-Control-Allow-Origin: *');

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date'))
        {
            $start_date = Input::get('start_date');
        }

        if(Input::has('end_date'))
        {
            $end_date = Input::get('end_date');
        }

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $webtics_pixel_property = Input::get('webtics_pixel_property');

        $widget_data_generator = new WidgetDataGenerator();
        $result = $widget_data_generator->getWeb360LeadConversionRateByMedium($start_date,$end_date,$webtics_pixel_property,$organization_id);

        return $result;

    }

    // Latency By Medium
    public function getWeb360LatencyByMedium(){

        header('Access-Control-Allow-Origin: *');

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date'))
        {
            $start_date = Input::get('start_date');
        }

        if(Input::has('end_date'))
        {
            $end_date = Input::get('end_date');
        }

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $webtics_pixel_property = Input::get('webtics_pixel_property');

        $widget_data_generator = new WidgetDataGenerator();
        $result = $widget_data_generator->getWeb360LatencyByMedium($start_date,$end_date,$webtics_pixel_property,$organization_id);

        return $result;
    }

}