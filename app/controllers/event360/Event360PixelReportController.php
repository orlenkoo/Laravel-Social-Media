<?php

/**
 * Created by PhpStorm.
 * User: DeAlwis
 * Date: 9/1/2016
 * Time: 1:07 PM
 */
class Event360PixelReportController extends BaseController
{
     /**
      * Widgets Functions
      **/

    // Lead Convertion Rate Comparison Report (removed on Julie's request)
    public function getLeadsConversionRateComparison(){

        header('Access-Control-Allow-Origin: *');

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date')){
            $start_date = Input::get('start_date');
        }

        if(Input::has('end_date')){
            $end_date = Input::get('end_date');
        }

        $widget_data_generator = new WidgetDataGenerator();
        $leads_conversion_rate_comparison = $widget_data_generator->getEvent360LeadsConversionRateComparison($start_date,$end_date,$organization_id);

        return $leads_conversion_rate_comparison;
    }

    // Lead Volume Comparison Report
    public function getLeadVolumeComparison(){

        header('Access-Control-Allow-Origin: *');

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date')){
            $start_date = Input::get('start_date');
        }
        if(Input::has('end_date')){
            $end_date = Input::get('end_date');
        }

        $widget_data_generator = new WidgetDataGenerator();
        $lead_volume_comparison = $widget_data_generator->getEvent360LeadVolumeComparison($start_date,$end_date,$organization_id);

        return $lead_volume_comparison;
    }

    // Lead Breakdown By Type Report
    public function getLeadBreakdownByType(){

        header('Access-Control-Allow-Origin: *');

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date')){
            $start_date = Input::get('start_date');
        }
        if(Input::has('end_date')){
            $end_date = Input::get('end_date');
        }

        $widget_data_generator = new WidgetDataGenerator();
        $event_360_leads_breakdown_by_types = $widget_data_generator->getEvent360LeadBreakdownByType($start_date,$end_date,$organization_id);

        return $event_360_leads_breakdown_by_types;
    }

    // Lead Breakdown By Lead Rating Report
    public function getLeadsBreakdownByLeadRating(){

        header('Access-Control-Allow-Origin: *');

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }

        $start_date = '';
        $end_date = '';

        if(Input::has('start_date')){
            $start_date = Input::get('start_date');
        }
        if(Input::has('end_date')){
            $end_date = Input::get('end_date');
        }

        $widget_data_generator = new WidgetDataGenerator();
        $event360_lead_count = $widget_data_generator->getEvent360LeadsBreakdownByLeadRating($start_date,$end_date,$organization_id);

        return $event360_lead_count;

    }




}