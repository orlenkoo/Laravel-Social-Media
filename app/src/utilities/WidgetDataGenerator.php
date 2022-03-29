<?php

/**
 * Created by PhpStorm.
 * User: DeAlwis
 * Date: 12/29/2016
 * Time: 2:13 PM
 */
class WidgetDataGenerator
{
    /* Common Functions */
    public function getJSONAPI($call_url,$request_data = array()){

        $url = $call_url;
        $data = $request_data;
        $headers = "accept: */*\r\n" .
            "Content-Type: application/json\r\n" .
            "Custom-Header: custom-value\r\n" .
            "Custom-Header-Two: custom-value-2\r\n";

        $context = [
            'http' => [
                'method' => 'GET',
                'header' => $headers,
                'content' => http_build_query($data),
            ]
        ];

        $context = stream_context_create($context);
        $result = file_get_contents($url, false, $context);

        return json_decode($result);
    }


    /*   Event360 Pixel Report Widgets */

    // this function get the data for the Event360 Engagement Metric Widget
    public function getEvent360EngagementMetricWidgetData($start_date,$end_date,$organization_id){

        $webtics_pixel_microsite_property = WebticsPixelProperty::getEvent360WebticsPixelPropertyIdForOrganization($organization_id);

        $url = Config::get('project_vars.webtics_pixel_reporting_api_url') . '/get-engagement-metric-report?start_date=' . $start_date . '&end_date=' . $end_date . '&webtics_pixel_property=' . $webtics_pixel_microsite_property.'&microsite=1';

        $result = $this->getJSONAPI($url);

        $microsite_engagement = $result->microsite_engagements;

        syslog(LOG_INFO, '$microsite_engagement -- '.json_encode($microsite_engagement));

        $result_details = array(
            'home_page_featured_logo_clicks' => 0,
            'ad_banner_impressions' => 0,
            'ad_banner_clicks' => 0,
            'clicks_on_shortlist' => 0,
            'clicks_on_get_a_quote' => 0,
            'clicks_on_message' => 0,
            'impression_on_event_services_result_page' => 0,
            'clicks_on_logo_in_event_services_result_page' => 0,
            'clicks_to_view_phone_number' => 0,
            'clicks_to_profile' => 0,
        );

        if(is_object($microsite_engagement)){

            $result_details['home_page_featured_logo_clicks'] =  $microsite_engagement->event_tracking_code_1->count;
            $result_details['ad_banner_impressions'] =$microsite_engagement->event_tracking_code_2->count;
            $result_details['ad_banner_clicks'] = $microsite_engagement->event_tracking_code_3->count;
            $result_details['clicks_on_shortlist'] =  $microsite_engagement->event_tracking_code_4->count;
            $result_details['clicks_on_get_a_quote'] = $microsite_engagement->event_tracking_code_5->count;
            $result_details['clicks_on_message'] = $microsite_engagement->event_tracking_code_6->count;
            $result_details['impression_on_event_services_result_page'] = $microsite_engagement->event_tracking_code_7->count;
            $result_details['clicks_on_logo_in_event_services_result_page'] = $microsite_engagement->event_tracking_code_8->count;
            $result_details['clicks_to_view_phone_number'] = $microsite_engagement->event_tracking_code_9->count;
            $result_details['clicks_to_profile'] = $microsite_engagement->event_tracking_code_1->count + $microsite_engagement->event_tracking_code_3->count + $microsite_engagement->event_tracking_code_8->count + $microsite_engagement->event_tracking_code_10->count;

        }



        $engagement_metric_report_details = array(
            'home_page_featured_logo_clicks' => $result_details['home_page_featured_logo_clicks'],
            'ad_banner_impressions' => $result_details['ad_banner_impressions']  ,
            'ad_banner_clicks' =>  $result_details['ad_banner_clicks'],
            'clicks_on_shortlist' => $result_details['clicks_on_shortlist'],
            'clicks_on_get_a_quote' => $result_details['clicks_on_get_a_quote'] ,
            'clicks_on_message' => $result_details['clicks_on_message'],
            'impression_on_event_services_result_page' => $result_details['impression_on_event_services_result_page'],
            'clicks_on_logo_in_event_services_result_page' => $result_details['clicks_on_logo_in_event_services_result_page'] ,
            'clicks_to_view_phone_number' => $result_details['clicks_to_view_phone_number'],
            'clicks_to_profile' => $result_details['clicks_to_profile'],
            'total_visits' => $result->total_visits,
            'unique_visitor' => $result->unique_visitor,
            'bounce_rate' =>  $result->bounce_rate,
            'time_spent' => $result->time_spent
        );

        return $engagement_metric_report_details;

    }

    // this function get the data for the Event360 ROI Metric Widget
    public function getEvent360ROIMetricReport($start_date,$end_date,$organization_id){

        date_default_timezone_set("Asia/Singapore");
        $today = date('Y-m-d');

        $webtics_pixel_microsite_property = WebticsPixelProperty::getEvent360WebticsPixelPropertyIdForOrganization($organization_id);

        $start_date_this_week = date('Y-m-d', strtotime('Monday this week ' . $today ));
        $end_date_this_week = $today;

        $start_date_this_month = date('Y-m-d', strtotime('first day of this month' . $today ));
        $end_date_this_month = $today;

        // get total leads during the period

        $total_leads = DB::table('leads')
            ->where('organization_id', $organization_id)
            ->whereIn('lead_source', array('event360_enquiries', 'event360_calls', 'event360_messenger_threads'))
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->count();

        syslog(LOG_INFO, '$total_leads -- '. $total_leads);

        $queries = DB::getQueryLog();
        $last_query = end($queries);
        syslog(LOG_INFO, $last_query['query']);

        //get Week-to-date Leads
        $week_to_date_leads = DB::table('leads')
            ->where('organization_id', $organization_id)
            ->whereIn('lead_source', array('event360_enquiries', 'event360_calls', 'event360_messenger_threads'))
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date_this_week.'" AND "'.$end_date_this_week.'" ')
            ->count();


        //get Month-to-date Leads
        $month_to_date_leads = DB::table('leads')
            ->where('organization_id', $organization_id)
            ->whereIn('lead_source', array('event360_enquiries', 'event360_calls', 'event360_messenger_threads'))
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date_this_month.'" AND "'.$end_date_this_month.'" ')
            ->count();




        //all-time-leads
        $all_time_leads = DB::table('leads')
            ->where('organization_id', $organization_id)
            ->whereIn('lead_source', array('event360_enquiries', 'event360_calls', 'event360_messenger_threads'))
            ->count();







        $url = Config::get('project_vars.webtics_pixel_reporting_api_url') . '/get-roi-metrics-report?lead_count='.$total_leads.'&start_date=' . $start_date . '&end_date=' . $end_date . '&webtics_pixel_property=' . $webtics_pixel_microsite_property;

        $results = $this->getJSONAPI($url);

        $roi_metrics_details = array(
            'leads' => $total_leads,
            'lead_conversion_rate' => $results->lead_conversion_rate,
            'week_to_date_leads' => $week_to_date_leads,
            'month_to_date_leads' => $month_to_date_leads,
            'all_time_leads' => $all_time_leads,
            'latency' => $results->latency
        );

        return $roi_metrics_details;
    }

    // this function get the data for the Event360 Leads Conversion Rate Comparison Chart
    public function getEvent360LeadsConversionRateComparison($start_date,$end_date,$organization_id){

        $webtics_pixel_microsite_property = WebticsPixelProperty::getEvent360WebticsPixelPropertyIdForOrganization($organization_id);

        $event360_lead_count = DB::table('leads')
            ->select(DB::raw('count(id) as lead_count'))
            ->where('webtics_pixel_property_id', $webtics_pixel_microsite_property)
            ->whereIn('lead_source', array('event360_enquiries', 'event360_calls', 'event360_messenger_threads'))
            ->where('organization_id', $organization_id)
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->get();

        $web360_lead_count = DB::table('leads')
            ->select(DB::raw('count(id) as lead_count'))
            ->where('webtics_pixel_property_id', $webtics_pixel_microsite_property)
            ->whereIn('lead_source', array('web360_enquiries'))
            ->where('organization_id', $organization_id)
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->get();

        $url = Config::get('project_vars.webtics_pixel_reporting_api_url').'/get-leads-conversion-rate-comparison?web360_lead_count='.$web360_lead_count[0]->lead_count.'&event360_lead_count='.$event360_lead_count[0]->lead_count.'&start_date='.$start_date.'&end_date='.$end_date.'&webtics_pixel_property='.$webtics_pixel_microsite_property;
        $result = $this->getJSONAPI($url);

        $leads_conversion_rate_comparison = array(
            'event360_lead_count' => $result->event360_lead_count,
            'web360_lead_count' => $result->web360_lead_count
        );
        return $leads_conversion_rate_comparison;
    }

    // this function get the data for the Event360 Lead Volume Comparison Chart
    public function getEvent360LeadVolumeComparison($start_date,$end_date,$organization_id){


        // because this is to compare between vendors own websites
        $webtics_pixel_property_ids = WebticsPixelProperty::getWebticsPixelPropertyIdsByOrganization($organization_id);

        $event360_lead_count = DB::table('leads')
            ->select(DB::raw('count(id) as lead_count'))
            ->whereIn('lead_source', array('event360_enquiries', 'event360_calls', 'event360_messenger_threads'))
            ->where('organization_id', $organization_id)
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->get();

        $web360_lead_count = DB::table('leads')
            ->select(DB::raw('count(id) as lead_count'))
            ->whereIn('webtics_pixel_property_id', $webtics_pixel_property_ids)
            ->whereIn('lead_source', array('web360_enquiries'))
            ->where('organization_id', $organization_id)
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->get();

        $total_leads = $event360_lead_count[0]->lead_count + $web360_lead_count[0]->lead_count;

        if ($total_leads > 0) {

            $event360_lead_count_per = $event360_lead_count[0]->lead_count / $total_leads * 100;
            $web360_lead_count_per = $web360_lead_count[0]->lead_count / $total_leads * 100;

            $lead_volume_comparison = array(
                'event360_lead_count' => round($event360_lead_count_per),
                'web360_lead_count' => round($web360_lead_count_per)
            );

            return $lead_volume_comparison;

        } else {
            return -1;
        }

    }

    // this function get the data for the Event360 Lead Breakdown By Type Chart
    public function getEvent360LeadBreakdownByType($start_date,$end_date,$organization_id){


        $event_360_leads_breakdown_by_types = DB::table('leads')
            ->select(DB::raw('count(id) as lead_count'), 'lead_source')
            ->whereIn('lead_source', array('event360_enquiries', 'event360_calls', 'event360_messenger_threads'))
            ->where('organization_id', $organization_id)
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->groupBy('lead_source')
            ->get();

        foreach($event_360_leads_breakdown_by_types as $event_360_leads_breakdown_by_type){
            if($event_360_leads_breakdown_by_type->lead_source == 'event360_enquiries')
            {
                $event_360_leads_breakdown_by_type->lead_source = 'Online Leads';
            }
            elseif($event_360_leads_breakdown_by_type->lead_source == 'event360_calls')
            {
                $event_360_leads_breakdown_by_type->lead_source= 'Calls';
            }
            elseif($event_360_leads_breakdown_by_type->lead_source == 'event360_messenger_threads')
            {
                $event_360_leads_breakdown_by_type->lead_source = 'Messages';
            }
        }

        return $event_360_leads_breakdown_by_types;

    }

    // this function get the data for the Event360 Leads Breakdown By Lead Rating Chart
    public function getEvent360LeadsBreakdownByLeadRating($start_date,$end_date,$organization_id){


        $event360_lead_count = DB::table('leads')
            ->select(DB::raw('count(id) as lead_count,
                                   CASE  WHEN lead_rating = "" THEN "Not Rated"
                                               WHEN lead_rating IS NULL THEN "Not Rated"
                                               ELSE lead_rating END AS lead_rating'))
            ->whereIn('lead_source', array('event360_enquiries', 'event360_calls', 'event360_messenger_threads'))
            ->where('organization_id', $organization_id)
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->groupBy(DB::raw('CASE WHEN lead_rating = "" THEN "Not Rated"
                                        WHEN lead_rating IS NULL THEN "Not Rated"
                                        ELSE lead_rating END'))
            ->get();

        return $event360_lead_count;
    }


    /*   Web360 Pixel Report Widgets */


    // this function get the data for the Web360 Engagement Metric Widget
    public function getWeb360EngagementMetricReport($start_date,$end_date,$webtics_pixel_property){

        $url =  Config::get('project_vars.webtics_pixel_reporting_api_url').'/get-engagement-metric-report?start_date='.$start_date.'&end_date='.$end_date.'&webtics_pixel_property='.$webtics_pixel_property.'&microsite=0';
        $result = $this->getJSONAPI($url);

        $engagement_metric_report_details = array(
            'total_visits' => $result->total_visits,
            'unique_visitor' => $result->unique_visitor,
            'bounce_rate' =>  $result->bounce_rate,
            'time_spent' => $result->time_spent
        );

        return $engagement_metric_report_details;
    }

    // this function get the data for the Web360 ROI Metric Report Widget
    public function getWeb360ROIMetricReport($start_date,$end_date,$webtics_pixel_property,$organization_id){

        //get total leads
        $total_leads = DB::table('leads')
            ->select(DB::raw('count(id) as lead_count'))
            ->where('webtics_pixel_property_id', $webtics_pixel_property)
            ->where('organization_id', $organization_id)
            ->whereIn('lead_source', array('web360_enquiries'))
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->get();

        $lead_count = $total_leads[0]->lead_count;

        $url = Config::get('project_vars.webtics_pixel_reporting_api_url') . '/get-roi-metrics-report?lead_count='.$lead_count.'&start_date=' . $start_date . '&end_date=' . $end_date . '&webtics_pixel_property=' . $webtics_pixel_property;
        $results = $this->getJSONAPI($url);

        $roi_metrics_details = array(
            'leads' => $results->leads,
            'lead_conversion_rate' => $results->lead_conversion_rate,
            'latency' => $results->latency
        );

        return $roi_metrics_details;
    }

    // this function get the data for the Web360 Visits BreakDown By Medium Chart
    public function getWeb360VisitsBreakDownByMedium($start_date,$end_date,$webtics_pixel_property){


        $url = Config::get('project_vars.webtics_pixel_reporting_api_url').'/get-web360-visit-breakdown-by-medium?start_date='.$start_date.'&end_date='.$end_date.'&webtics_pixel_property='.$webtics_pixel_property;
        $result = $this->getJSONAPI($url);

        return json_encode($result);

    }

    // this function get the data for the Web360 Leads Breakdown by Medium Chart
    public function getWeb360LeadsBreakdownByMedium($start_date,$end_date,$webtics_pixel_property,$organization_id){

        $web360_leads_breakdown_by_medium_details = DB::table('leads')
            ->select(DB::raw('COUNT(leads.id) as lead_count,
                                CASE leads.`utm_medium`
                                  WHEN "" THEN "Organic"
                                  WHEN "null" THEN "Organic"
                                  ELSE leads.`utm_medium`
                                END as `medium`'))
            ->where('webtics_pixel_property_id', $webtics_pixel_property)
            ->whereIn('lead_source', array('web360_enquiries'))
            ->where('organization_id', $organization_id )
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->groupBy('utm_medium')
            ->get();

        $web360_leads_breakdown_by_medium_detail_array = array();

        foreach ($web360_leads_breakdown_by_medium_details as $web360_leads_breakdown_by_medium_detail) {
            $web360_leads_breakdown_by_medium_detail_array[$web360_leads_breakdown_by_medium_detail->medium] = $web360_leads_breakdown_by_medium_detail->lead_count;
        }

        return json_encode($web360_leads_breakdown_by_medium_detail_array);
    }

    // this function get the data for the Web360 Lead Conversion Rate By Medium Chart
    public function getWeb360LeadConversionRateByMedium($start_date,$end_date,$webtics_pixel_property,$organization_id){

        $url =  Config::get('project_vars.webtics_pixel_reporting_api_url').'/get-web360-lead-conversion-rate-by-medium?start_date='.$start_date.'&end_date='.$end_date.'&webtics_pixel_property='.$webtics_pixel_property;
        $visits_by_mediums = $this->getJSONAPI($url);

        $result = array();
        foreach($visits_by_mediums as $visits_by_medium) {

            $leads_per_medium = DB::table('leads')
                ->where('webtics_pixel_property_id', $webtics_pixel_property)
                ->where('organization_id', $organization_id)
                ->where('utm_medium', $visits_by_medium->medium)
                ->whereIn('lead_source', array('web360_enquiries'))
                ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
                ->count();

            $queries = DB::getQueryLog();
            $last_query = end($queries);

            $total_visits = $visits_by_medium->total_visits;
            if($total_visits != 0) {
                $lead_conversion_rate = $leads_per_medium / $total_visits * 100;
            } else {
                $lead_conversion_rate = 0;
            }

            $result[] = array('medium' => $visits_by_medium->medium, 'lead_conversion_rate' => $lead_conversion_rate);
        }

        return $result;
    }

    // this function get the data for the Web360 Latency By Medium Chart
    public function getWeb360LatencyByMedium($start_date,$end_date,$webtics_pixel_property, $organization_id){

        //get total leads session list
        $web360_lead_session_ids = DB::table('leads')
            ->where('webtics_pixel_property_id', $webtics_pixel_property)
            ->where('organization_id', $organization_id)
            ->whereIn('lead_source', array('web360_enquiries'))
            ->whereRaw('DATE(datetime) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ')
            ->lists('webtics_pixel_session_id');

        $web360_lead_session_ids = implode(',',$web360_lead_session_ids);

        $url =  Config::get('project_vars.webtics_pixel_reporting_api_url').'/get-web360-latency-by-medium?get-web360-lead-conversion-rate-by-medium?start_date='.$start_date.'&end_date='.$end_date.'&webtics_pixel_property='.$webtics_pixel_property.'&web360_lead_session_ids='.$web360_lead_session_ids;
        $latency_by_mediums = $this->getJSONAPI($url);

        return $latency_by_mediums;
    }



    /*   Dashboard Widgets */


    // this function get the data for the Home Page Widget Leads By Week Chart
    public function getHomePageWidgetLeadsByWeekChart($lead_sources,$organization_id){

        date_default_timezone_set('Asia/Singapore');
        $date_five_weeks_ago = date('Y-m-d',strtotime('-5 weeks'));

        // send multiple sources through this
        $lead_sources = explode('|', $lead_sources);

        $leads_count_by_weekly = DB::table('leads')
            ->select(DB::raw("str_to_date(concat(yearweek(datetime), ' monday'), '%X%V %W') as `date`, count(*) as leads"))
            ->whereIn('lead_source', $lead_sources)
            ->where('organization_id', $organization_id)
            ->where('datetime','>=', $date_five_weeks_ago)
            ->orderBy('datetime', 'desc')
            ->groupBy(DB::raw('yearweek(datetime)'))
            ->take(5)
            ->get();

        $queries = DB::getQueryLog();
        $last_query = end($queries);

        syslog(LOG_INFO, $last_query['query']);

        $leads_count_by_weekly =  array_reverse($leads_count_by_weekly);

        return json_encode($leads_count_by_weekly);

    }

    // this function get the data for the Non Advertising Event360 Leads Widget
    public function getAjaxNonAdvertisingEvent360LeadsWidget($organization_id){
        $leads = Lead::where('organization_id', $organization_id)
            ->where('lead_source', 'event360_enquiries')
            ->orderBy('datetime', 'desc')
            ->take(5)
            ->get();

        return $leads;
    }

}