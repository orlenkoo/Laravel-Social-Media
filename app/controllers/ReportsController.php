<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/3/2016
 * Time: 6:00 PM
 */
class ReportsController extends \BaseController
{
    public function index ()
    {
        date_default_timezone_set("Asia/Singapore");
        $from_date = date('Y-m-d', strtotime("-30 days"));
        $to_date = date('Y-m-d');

        return View::make('reports.index', compact('from_date', 'to_date'));
    }

    public function ajaxLoadReportCharts(){

        $organization_id = Session::get('user-organization-id');

        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $customer_ids_for_organization = Customer::where('organization_id',$organization_id)->lists('id');

        if(count($customer_ids_for_organization) > 0) {
            $customer_ids_for_organization = implode(", ", $customer_ids_for_organization);
        } else {
            $customer_ids_for_organization = "''";
        }

        $user_id = Session::get('user-id');
        $user_level = Session::get('user-level');

        // if sales only load own assigned leads
        $sales_filter_query = '';
        if($user_level == 'sales') {
            $sales_filter_query = "and employees.id = '".$user_id."'";
        }

        $query_call_count_per_person = "select CONCAT(employees.given_name,' ',employees.surname) as full_name, IFNULL(calls.call_count,0) as call_count
                                                from employees
                                                left join (
                                                            select calls.assigned_to, count(*) as call_count
                                                            from calls
                                                            where calls.call_date between '".$date_range['from_date']."' and '".$date_range['to_date']."'
                                                            and calls.customer_id in (".$customer_ids_for_organization.")
                                                            group by assigned_to

                                                ) calls on calls.assigned_to = employees.id
                                            where employees.organization_id = '".$organization_id."' $sales_filter_query
                                            order by full_name";

        syslog(LOG_INFO, '$query_call_count_per_person -- '. $query_call_count_per_person);

        $call_count_per_person = DB::select($query_call_count_per_person);

        $query_meeting_count_per_person = "select CONCAT(employees.given_name,' ',employees.surname) as full_name, IFNULL(meetings.meeting_count,0) as meeting_count
                                                from employees
                                                left join (
                                                            select meetings.assigned_to, count(*) as meeting_count
                                                            from meetings
                                                            where meetings.meeting_date between '".$date_range['from_date']."' and '".$date_range['to_date']."'
                                                            and meetings.customer_id in (".$customer_ids_for_organization.")
                                                            group by assigned_to

                                                ) meetings on meetings.assigned_to = employees.id
                                            where  employees.organization_id = '".$organization_id."' $sales_filter_query
                                            order by full_name";

        $meeting_count_per_person = DB::select($query_meeting_count_per_person);

        $query_contracts_value_per_person = "select CONCAT(employees.given_name,' ',employees.surname) as full_name, IFNULL(quotations.sub_total,0) as sub_total
                                                from employees
                                                left join (
                                                            select leads.assigned_to, sum(net_total) as sub_total
                                                            from quotations
                                                            left join leads on leads.id = quotations.lead_id
                                                            where quotations.quotation_closed_at between '".$date_range['from_date']."' and '".$date_range['to_date']."'
                                                            and quotation_status = 'Closed'
                                                            and quotations.customer_id in (".$customer_ids_for_organization.")
                                                            group by leads.assigned_to

                                                ) quotations on quotations.assigned_to = employees.id
                                            where employees.organization_id = '".$organization_id."' $sales_filter_query
                                            order by full_name";

        $contracts_value_per_person = DB::select($query_contracts_value_per_person);

        $query_quotations_value_per_person ="select CONCAT(employees.given_name,' ',employees.surname) as full_name, IFNULL(quotations.sub_total,0) as sub_total
                                                from employees
                                                left join (
                                                            select leads.assigned_to, sum(net_total) as sub_total
                                                            from quotations
                                                            left join leads on leads.id = quotations.lead_id
                                                            where quotations.created_at between '".$date_range['from_date']."' and '".$date_range['to_date']."'
                                                            and quotations.quotation_status != 'Updated'
                                                            and quotations.customer_id in (".$customer_ids_for_organization.")
                                                            group by leads.assigned_to

                                                ) quotations on quotations.assigned_to = employees.id
                                            where employees.organization_id = '".$organization_id."' $sales_filter_query
                                            order by full_name";

        $quotations_value_per_person = DB::select($query_quotations_value_per_person);

        $chart_data = array(
            'call_count_per_person' => $call_count_per_person,
            'meeting_count_per_person' => $meeting_count_per_person,
            'contracts_value_per_person' => $contracts_value_per_person,
            'quotations_value_per_person' => $quotations_value_per_person,
        );

        return json_encode($chart_data);

    }

    public function ajaxLoadReportsActivityEfficiencyTable()
    {
        $organization_id = Session::get('user-organization-id');

        $customer_ids_for_organization = Customer::where('organization_id',$organization_id)->lists('id');

        if(count($customer_ids_for_organization) > 0) {
            $customer_ids_for_organization = implode(", ", $customer_ids_for_organization);
        } else {
            $customer_ids_for_organization = "''";
        }

        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $user_id = Session::get('user-id');
        $user_level = Session::get('user-level');

        // if sales only load own assigned leads
        $sales_filter_query = '';
        if($user_level == 'sales') {
            $sales_filter_query = "and employees.id = '".$user_id."'";
        }

        $query_call_count_per_persons = "select CONCAT(employees.given_name,' ',employees.surname) as full_name,
                                                employees.id as employee_id,
                                                IFNULL(calls.call_count,0)  as call_count
                                                from employees
                                                left join (
                                                            select calls.assigned_to, count(*) as call_count
                                                            from calls
                                                            where calls.call_date between '".$from_date."' and '".$to_date."'
                                                            and calls.customer_id in (".$customer_ids_for_organization.")
                                                            group by assigned_to

                                                ) calls on calls.assigned_to = employees.id
                                            where employees.organization_id = '".$organization_id."' $sales_filter_query
                                            order by full_name";

        $call_count_per_persons = DB::select($query_call_count_per_persons);

        $query_meeting_count_per_persons = "select CONCAT(employees.given_name,' ',employees.surname) as full_name,
                                                employees.id as employee_id,
                                                IFNULL(meetings.meeting_count,0) as meeting_count
                                                from employees
                                                left join (
                                                            select meetings.assigned_to, count(*) as meeting_count
                                                            from meetings
                                                            where meetings.created_at between '".$from_date."' and '".$to_date."'
                                                            and meetings.customer_id in (".$customer_ids_for_organization.")
                                                            group by assigned_to

                                                ) meetings on meetings.assigned_to = employees.id
                                            where employees.organization_id = '".$organization_id."' $sales_filter_query
                                            order by full_name";

        $meeting_count_per_persons = DB::select($query_meeting_count_per_persons);

        $activities_efficiencies_array = array();

        foreach($call_count_per_persons as $call_count_per_person){
            foreach($meeting_count_per_persons as $meeting_count_per_person){
                if($meeting_count_per_person->employee_id == $call_count_per_person->employee_id ){

                    if($call_count_per_person->call_count != 0){
                        $ratio = ($meeting_count_per_person->meeting_count / $call_count_per_person->call_count )*100;
                        $ratio = round($ratio,1);
                        $data_array = array(
                            'full_name' => $call_count_per_person->full_name,
                            'call_count' => $call_count_per_person->call_count,
                            'meeting_count' => $meeting_count_per_person->meeting_count,
                            'call_meeting_ratio' => $ratio,
                        );

                    }else{
                        $data_array = array(
                            'full_name' => $call_count_per_person->full_name,
                            'call_count' => $call_count_per_person->call_count,
                            'meeting_count' => $meeting_count_per_person->meeting_count,
                            'call_meeting_ratio' => 0,
                        );
                    }

                    array_push($activities_efficiencies_array,$data_array);
                }
            }
        }

        return View::make('reports._ajax_partials.activities_efficiency_table',compact('activities_efficiencies_array'));
    }

    public function ajaxLoadReportsSalesEfficiencyTable()
    {
        $organization_id = Session::get('user-organization-id');

        //Date Filter has been removed upon request by Julie

//        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
//        $filter_from_date = Input::get('dashboard_filter_from_date');
//        $filter_to_date = Input::get('dashboard_filter_to_date');
//
//        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
//
//        $from_date = $date_range['from_date']. ' 00:00:00';
//        $to_date = $date_range['to_date']. ' 23:59:59';

        $customer_ids_for_organization = Customer::where('organization_id',$organization_id)->lists('id');

        if(count($customer_ids_for_organization) > 0) {
            $customer_ids_for_organization = implode(", ", $customer_ids_for_organization);
        } else {
            $customer_ids_for_organization = "''";
        }

        $user_id = Session::get('user-id');
        $user_level = Session::get('user-level');

        // if sales only load own assigned leads
        $sales_filter_query = '';
        if($user_level == 'sales') {
            $sales_filter_query = "and employees.id = '".$user_id."'";
        }

        $query_contracts_value_per_persons = "select CONCAT(employees.given_name,' ',employees.surname) as full_name,
                                                        employees.id as employee_id,
                                                        IFNULL(quotations.sub_total,0) as sub_total,
                                                        IFNULL(quotations.contract_count,0) as contract_count
                                                from employees
                                                left join (
                                                            select leads.assigned_to, SUM(net_total) as sub_total, count(*) as contract_count
                                                            from quotations
                                                            left join leads on quotations.lead_id = leads.id
                                                            where quotations.quotation_status = 'Closed'
                                                            and quotations.customer_id in (".$customer_ids_for_organization.") and quotations.quotation_status != 'Updated'
                                                            group by assigned_to

                                                ) quotations on quotations.assigned_to = employees.id
                                            where  employees.organization_id = '".$organization_id."' $sales_filter_query
                                            order by full_name";

        $contracts_value_per_persons = DB::select($query_contracts_value_per_persons);

        $query_quotations_value_per_persons = "select CONCAT(employees.given_name,' ',employees.surname) as full_name,
                                                            employees.id as employee_id,
                                                         IFNULL(quotations.sub_total,0) as sub_total,
                                                         IFNULL(quotations.quoted_count,0) as quoted_count
                                                from employees
                                                left join (
                                                            select leads.assigned_to, SUM(net_total) as sub_total, count(*) as quoted_count
                                                            from quotations
                                                            left join leads on quotations.lead_id = leads.id
                                                            where quotations.customer_id in (".$customer_ids_for_organization.") and quotations.quotation_status != 'Updated'
                                                            group by assigned_to

                                                ) quotations on quotations.assigned_to = employees.id
                                            where  employees.organization_id = '".$organization_id."' $sales_filter_query
                                            order by full_name";

        $quotations_value_per_persons = DB::select($query_quotations_value_per_persons);

        $sales_efficiencies_array = array();

        foreach($quotations_value_per_persons as $quotations_value_per_person){
            foreach($contracts_value_per_persons as $contracts_value_per_person){

                if( $quotations_value_per_person->employee_id == $contracts_value_per_person->employee_id){

                    if($quotations_value_per_person->quoted_count != 0){

                        //CLosing Ratio = Contracts (#) divided by Quotes (#) x 100%
                        $ratio = ( $contracts_value_per_person->contract_count / $quotations_value_per_person->quoted_count )*100;
                        $ratio = round($ratio,1);

                        //Revenue / Contract = Contracts ($) divided by Contracts (#)
                        if($contracts_value_per_person->contract_count != 0){
                            $revenue_contract = ( $contracts_value_per_person->sub_total / $contracts_value_per_person->contract_count );
                            $revenue_contract = round($revenue_contract);
                        }else{
                            $revenue_contract = 0;
                        }

                        $data_array = array(
                            'full_name' => $contracts_value_per_person->full_name,
                            'quoted_amount' => '$ '.NumberUtilities::formatNumberWithComma($quotations_value_per_person->sub_total),
                            'quoted_count' => $quotations_value_per_person->quoted_count,
                            'contract_amount' => '$ '.NumberUtilities::formatNumberWithComma($contracts_value_per_person->sub_total),
                            'contract_count' => $contracts_value_per_person->contract_count,
                            'closing_ratio' => $ratio. ' %',
                            'revenue_contract' => '$ '.NumberUtilities::formatNumberWithComma($revenue_contract),
                        );

                    }else{

                        $data_array = array(
                            'full_name' => $contracts_value_per_person->full_name,
                            'quoted_amount' => '$ '.NumberUtilities::formatNumberWithComma($quotations_value_per_person->sub_total),
                            'quoted_count' => $quotations_value_per_person->quoted_count,
                            'contract_amount' => '$ '.NumberUtilities::formatNumberWithComma($contracts_value_per_person->sub_total),
                            'contract_count' => $contracts_value_per_person->contract_count,
                            'closing_ratio' => 0,
                            'revenue_contract' => '$ 0',
                        );

                    }

                    array_push($sales_efficiencies_array,$data_array);
                }
            }
        }

        return View::make('reports._ajax_partials.sales_efficiency_table',compact('sales_efficiencies_array'));
    }

    public function generateReport()
    {
        $report_model = new Report();
        $filters_array = array();

        $filters_array['report_type'] = Input::get('report_type');
        $filters_array['dashboard_filter_date_range'] = Input::get('dashboard_filter_date_range');
        $filters_array['dashboard_filter_from_date'] = Input::get('dashboard_filter_from_date');
        $filters_array['dashboard_filter_to_date'] = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filters_array['dashboard_filter_date_range'], $filters_array['dashboard_filter_from_date'], $filters_array['dashboard_filter_to_date']);
        $filters_array['from_date'] = $date_range['from_date']. ' 00:00:00';
        $filters_array['to_date'] = $date_range['to_date']. ' 23:59:59';

        syslog(LOG_INFO,'report_type -- '.$filters_array['report_type']);
        syslog(LOG_INFO,'from_time -- '.$filters_array['from_date']);
        syslog(LOG_INFO,'to_date -- '.$filters_array['to_date']);

        //leads report filters
        $filters_array['leads_report_filter_lead_source'] = Input::get('leads_report_filter_lead_source');
        $filters_array['leads_report_filter_lead_rating'] = Input::get('leads_report_filter_lead_rating');
        $filters_array['leads_report_filter_assigned_to'] = Input::get('leads_report_filter_assigned_to');
        $filters_array['leads_report_filter_campaign'] = Input::get('leads_report_filter_campaign');

        //quotations report filters
        $filters_array['quotations_report_filter_lead_source'] = Input::get('quotations_report_filter_lead_source');
        $filters_array['quotations_report_filter_assigned_to'] = Input::get('quotations_report_filter_assigned_to');
        $filters_array['quotations_report_filter_campaign'] = Input::get('quotations_report_filter_campaign');
        $filters_array['quotations_report_filter_status'] = Input::get('quotations_report_filter_status');

        //sales report filters
        $filters_array['sales_report_filter_lead_source'] = Input::get('sales_report_filter_lead_source');
        $filters_array['sales_report_filter_assigned_to'] = Input::get('sales_report_filter_assigned_to');
        $filters_array['sales_report_filter_campaign'] = Input::get('sales_report_filter_campaign');

        //activity report filters
        $filters_array['activity_report_filter_customer'] = Input::get('activity_report_filter_customer');
        $filters_array['activity_report_filter_activity_created_by'] = Input::get('activity_report_filter_activity_created_by');
        $filters_array['activity_report_filter_activity_type'] = Input::get('activity_report_filter_activity_type');

        //customer report filters
        $filters_array['customer_report_filter_customer'] = Input::get('customer_report_filter_customer');
        $filters_array['customer_report_filter_assigned_to'] = Input::get('customer_report_filter_assigned_to');
        $filters_array['customer_report_filter_tags'] = Input::get('customer_report_filter_tags');

        $report_model->setFiltersArray($filters_array);

        return $report_model->generateReport();

    }
}