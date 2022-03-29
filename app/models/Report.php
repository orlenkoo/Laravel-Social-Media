<?php

class Report
{
    public static $reportTypes = array(
        '' => 'Select',
        'leads_report' => 'Leads Report',
        'quotations_report' => 'Quotations Report',
        'sales_report' => 'Sales Report',
        'activity_report' => 'Activity Report',
        'customer_report' => 'Customer Report',
    );

    public static $activity_report_filter_activity_type = array(
        'Calls' => 'Calls',
        'Emails' => 'Emails',
        'Meetings' => 'Meetings',
        'Quotations' => 'Quotations',
    );

    private $filters_array = array();
    private $report_objects = array();
    private $html_template = null;

    /**
     * @return array
     */
    public function getFiltersArray()
    {
        return $this->filters_array;
    }

    /**
     * @param array $filters_array
     */
    public function setFiltersArray($filters_array)
    {
        $this->filters_array = $filters_array;
    }

    /**
     * @return null
     */
    public function getReportType()
    {
        return $this->report_type;
    }

    /**
     * @param null $report_type
     */
    public function setReportType($report_type)
    {
        $this->report_type = $report_type;
    }

    /**
     * @return array
     */
    public function getReportObjects()
    {
        return $this->report_objects;
    }

    /**
     * @param array $report_objects
     */
    public function setReportObjects($report_objects)
    {
        $this->report_objects = $report_objects;
    }

    /**
     * @return null
     */
    public function getHtmlTemplate()
    {
        return $this->html_template;
    }

    /**
     * @param null $html_template
     */
    public function setHtmlTemplate($html_template)
    {
        $this->html_template = $html_template;
    }

    /**
     * @return null
     */
    public function getCsvTemplate()
    {
        return $this->csv_template;
    }

    /**
     * @param null $csv_template
     */
    public function setCsvTemplate($csv_template)
    {
        $this->csv_template = $csv_template;
    }

    /**
     * @return null
     */
    public function getReportName()
    {
        return $this->report_name;
    }

    /**
     * @param null $report_name
     */
    public function setReportName($report_name)
    {
        $this->report_name = $report_name;
    }


    public function generateReport()
    {

        syslog(LOG_INFO,'report_type -- '.$this->filters_array['report_type']);
        syslog(LOG_INFO,'from_date -- '.$this->filters_array['from_date']);
        syslog(LOG_INFO,'to_date -- '.$this->filters_array['to_date']);

        $organization_id = Session::get('user-organization-id');

        if ($this->filters_array['report_type'] == 'leads_report') {

            $buildQuery = Lead::orderBy('datetime')
                ->whereBetween('datetime', array($this->filters_array['from_date'], $this->filters_array['to_date']))
                ->where('organization_id', $organization_id);

            if ($this->filters_array['leads_report_filter_lead_source']) {
                $buildQuery->whereIn('lead_source', $this->filters_array['leads_report_filter_lead_source']);
            }

            if ($this->filters_array['leads_report_filter_lead_rating']) {
                $buildQuery->whereIn('lead_rating', $this->filters_array['leads_report_filter_lead_rating']);
            }

            if ($this->filters_array['leads_report_filter_assigned_to']) {
                $buildQuery->whereIn('assigned_to', $this->filters_array['leads_report_filter_assigned_to']);
            }

            if ($this->filters_array['leads_report_filter_campaign']) {
                $buildQuery->whereIn('auto_tagged_campaign', $this->filters_array['leads_report_filter_campaign']);
            }

            $leads = $buildQuery->get();

            $queries = DB::getQueryLog();
            $last_query = end($queries);

            syslog(LOG_INFO, $last_query['query']);

            $this->html_template = View::make('reports.leads', array('leads' => $leads, 'filters_array' => $this->filters_array))->render();

        } else if ($this->filters_array['report_type'] == 'quotations_report') {

            $buildQuery = Quotation::orderBy('quoted_datetime')
                ->whereBetween('quoted_datetime', array($this->filters_array['from_date'], $this->filters_array['to_date']))
                ->where('organization_id', $organization_id);

            if ($this->filters_array['quotations_report_filter_status']) {
                $buildQuery->whereIn('quotation_status', $this->filters_array['quotations_report_filter_status']);
            }

            $lead_ids = $buildQuery->lists('lead_id');

            $buildQLeadsQuery = Lead::whereIn('id', $lead_ids);

            if ($this->filters_array['quotations_report_filter_lead_source']) {
                $buildQLeadsQuery->whereIn('lead_source', $this->filters_array['quotations_report_filter_lead_source']);
            }

            if ($this->filters_array['quotations_report_filter_assigned_to']) {
                $buildQLeadsQuery->whereIn('assigned_to', $this->filters_array['quotations_report_filter_assigned_to']);
            }

            if ($this->filters_array['quotations_report_filter_campaign']) {
                $buildQLeadsQuery->whereIn('auto_tagged_campaign', $this->filters_array['quotations_report_filter_campaign']);
            }

            $leads = $buildQLeadsQuery->get();

            $queries = DB::getQueryLog();
            $last_query = end($queries);

            syslog(LOG_INFO, $last_query['query']);

            $this->html_template = View::make('reports.quotations', array('leads' => $leads, 'filters_array' => $this->filters_array))->render();

        } else if ($this->filters_array['report_type'] == 'sales_report') {

            $buildQuery = Quotation::orderBy('quotation_closed_at')
                ->whereBetween('quoted_datetime', array($this->filters_array['from_date'], $this->filters_array['to_date']))
                ->where('organization_id', $organization_id);

            $lead_ids = $buildQuery->lists('lead_id');

            $buildQSalesQuery = Lead::whereIn('id', $lead_ids);

            if ($this->filters_array['sales_report_filter_lead_source']) {
                $buildQSalesQuery->whereIn('lead_source', $this->filters_array['sales_report_filter_lead_source']);
            }

            if ($this->filters_array['sales_report_filter_assigned_to']) {
                $buildQSalesQuery->whereIn('assigned_to', $this->filters_array['sales_report_filter_assigned_to']);
            }

            if ($this->filters_array['sales_report_filter_campaign']) {
                $buildQSalesQuery->whereIn('auto_tagged_campaign', $this->filters_array['sales_report_filter_campaign']);
            }

            $leads = $buildQSalesQuery->get();

            $queries = DB::getQueryLog();
            $last_query = end($queries);

            syslog(LOG_INFO, $last_query['query']);

            $this->html_template = View::make('reports.sales', array('leads' => $leads, 'filters_array' => $this->filters_array))->render();

        }else if ($this->filters_array['report_type'] == 'activity_report') {

            $buildCustomerQuery = Customer::orderBy('customer_name')
                ->where('organization_id', $organization_id);

            if ($this->filters_array['activity_report_filter_customer']) {
                $buildCustomerQuery->whereIn('id', $this->filters_array['activity_report_filter_customer']);
            }

            $buildCallQuery = Call::whereBetween('created_datetime', array($this->filters_array['from_date'], $this->filters_array['to_date']));
            $buildMeetingQuery = Meeting::whereBetween('created_datetime', array($this->filters_array['from_date'], $this->filters_array['to_date']));
            $buildEmailQuery = Email::whereBetween('sent_on', array($this->filters_array['from_date'], $this->filters_array['to_date']));
            $buildQuotationQuery = Quotation::whereBetween('quoted_datetime', array($this->filters_array['from_date'], $this->filters_array['to_date']));

            if ($this->filters_array['activity_report_filter_activity_created_by']) {
                $buildCallQuery->whereIn('assigned_to', $this->filters_array['activity_report_filter_activity_created_by']);

                $buildMeetingQuery->whereIn('assigned_to', $this->filters_array['activity_report_filter_activity_created_by']);

                $buildEmailQuery->whereIn('sent_by_id', $this->filters_array['activity_report_filter_activity_created_by']);

                $buildQuotationQuery->whereIn('quoted_by', $this->filters_array['activity_report_filter_activity_created_by']);
            }

            $activities_customers_id_array = [];

            if ($this->filters_array['activity_report_filter_activity_type']) {

                foreach($this->filters_array['activity_report_filter_activity_type'] as $activity){

                    if( $activity == 'Calls'){
                        $customer_ids = $buildCallQuery->lists('customer_id');
                        $activities_customers_id_array = array_unique (array_merge ($activities_customers_id_array, $customer_ids));
                    }

                    if( $activity == 'Meetings'){
                        $customer_ids = $buildMeetingQuery->lists('customer_id');
                        $activities_customers_id_array = array_unique (array_merge ($activities_customers_id_array, $customer_ids));
                    }

                    if( $activity == 'Emails'){
                        $customer_ids = $buildEmailQuery->lists('customer_id');
                        $activities_customers_id_array = array_unique (array_merge ($activities_customers_id_array, $customer_ids));
                    }

                    if( $activity == 'Quotations'){
                        $customer_ids = $buildQuotationQuery->lists('customer_id');
                        $activities_customers_id_array = array_unique (array_merge ($activities_customers_id_array, $customer_ids));
                    }
                }
            }

            if(sizeof($activities_customers_id_array)){
                $buildCustomerQuery->whereIn('id',$activities_customers_id_array);
            }

            $activities = $buildCustomerQuery->get();
            $queries = DB::getQueryLog();
            $last_query = end($queries);

            syslog(LOG_INFO, $last_query['query']);

            $this->html_template = View::make('reports.activity', array('activities' => $activities, 'filters_array' => $this->filters_array))->render();

        } else if ($this->filters_array['report_type'] == 'customer_report') {

            $buildCustomerQuery = Customer::orderBy('customer_name')
                ->where('organization_id', $organization_id);

            if ($this->filters_array['customer_report_filter_customer']) {
                $buildCustomerQuery->whereIn('id', $this->filters_array['customer_report_filter_customer']);
            }

            if ($this->filters_array['customer_report_filter_tags']) {
                $customer_ids = CustomerTagAssignment::whereIn('customer_tag_id',$this->filters_array['customer_report_filter_tags'])
                    ->lists('customer_id');
                $buildCustomerQuery->whereIn('id', $customer_ids);
            }

            $customer_ids = $buildCustomerQuery->lists('id');

            $buildQLeadQuery = Lead::whereIn('customer_id', $customer_ids)
                ->where('organization_id', $organization_id);

            if ($this->filters_array['customer_report_filter_assigned_to']) {
                $buildQLeadQuery->whereIn('assigned_to', $this->filters_array['customer_report_filter_assigned_to']);
            }

            $leads = $buildQLeadQuery->get();

            $queries = DB::getQueryLog();
            $last_query = end($queries);

            syslog(LOG_INFO, $last_query['query']);

            $this->html_template = View::make('reports.customer', array('leads' => $leads, 'filters_array' => $this->filters_array))->render();

        }

        return $this->html_template;
    }
}