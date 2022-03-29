@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-12 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Reports</h1>
                </div>
                <div class="panel-content">
                    {{ Form::open(array('route' => 'reports.generate_report', 'data-abide' => true ,'id' => 'reports_form')) }}
                    <div class="row expanded">
                        <div class="large-3 columns">
                            <label for="customer_tags">
                                Report Type
                                <select name="filter_report_type" id="filter_report_type" placeholder="Report Type" onchange="showFilters(this);">
                                    @foreach(Report::$reportTypes as $report_type_key => $report_type)
                                        <option value='{{ $report_type_key }}'>{{ $report_type }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="large-9 columns">
                            &nbsp;
                        </div>
                    </div>

                    <div class="row expanded" id="leads_report_filters" style="display: none;">
                        <div class="large-3 columns">
                            <label for="leads_report_filter_lead_source">
                                Lead Source:
                                <select name="leads_report_filter_lead_source" id="leads_report_filter_lead_source" multiple>
                                    <option value="">All</option>
                                    @foreach(Lead::$lead_sources as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="leads_report_filter_lead_rating">
                                Lead Rating:
                                <select name="leads_report_filter_lead_rating" id="leads_report_filter_lead_rating" multiple>
                                    <option value="">All</option>
                                    @foreach(Lead::$lead_ratings as $lead_rating)
                                        <option value="{{ $lead_rating }}">{{ $lead_rating }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="leads_list_filter_assigned_to">
                                Assigned To:
                                <select name="leads_report_filter_assigned_to" id="leads_report_filter_assigned_to" multiple>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="leads_list_filter_campaign">
                                Campaign Sources:
                                <select name="leads_report_filter_campaign" id="leads_report_filter_campaign" multiple>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row expanded" id="quotations_report_filters" style="display: none;">
                        <div class="large-3 columns">
                            <label for="quotations_report_filter_lead_source">
                                Lead Source:
                                <select name="quotations_report_filter_lead_source" id="quotations_report_filter_lead_source" multiple>
                                    <option value="">All</option>
                                    @foreach(Lead::$lead_sources as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="quotations_report_filter_assigned_to">
                                Assigned To:
                                <select name="quotations_report_filter_assigned_to" id="quotations_report_filter_assigned_to" multiple>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="quotations_report_filter_campaign">
                                Campaign Sources:
                                <select name="quotations_report_filter_campaign" id="quotations_report_filter_campaign" multiple>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="quotations_report_filter_status">
                                Quotation Status:
                                {{Form::select('quotations_report_filter_status',array(''=>'Select') + Quotation::$quotation_status , '',array('id' => 'quotations_report_filter_status','multiple' => 'multiple'))}}
                            </label>
                        </div>
                    </div>
                    <div class="row expanded" id="sales_report_filters" style="display: none;">
                        <div class="large-3 columns">
                            <label for="sales_report_filter_lead_source">
                                Lead Source:
                                <select name="sales_report_filter_lead_source" id="sales_report_filter_lead_source" multiple>
                                    <option value="">All</option>
                                    @foreach(Lead::$lead_sources as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="sales_report_filter_assigned_to">
                                Assigned To:
                                <select name="sales_report_filter_assigned_to" id="sales_report_filter_assigned_to" multiple>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="sales_report_filter_campaign">
                                Campaign Sources:
                                <select name="sales_report_filter_campaign" id="sales_report_filter_campaign" multiple>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            &nbsp;
                        </div>
                    </div>
                    <div class="row expanded" id="activity_report_filters" style="display: none;">
                        <div class="large-3 columns">
                            <label for="activity_report_filter_customer">
                                Customer Name:
                                <select name="activity_report_filter_customer" id="activity_report_filter_customer" multiple>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="activity_report_filter_activity_created_by">
                                Activity Created by:
                                <select name="activity_report_filter_activity_created_by" id="activity_report_filter_activity_created_by" multiple>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="activity_report_filter_activity_type">
                                Activity Type:
                                <select name="activity_report_filter_activity_type" id="activity_report_filter_activity_type" multiple>
                                    <option value="">All</option>
                                    @foreach(Report::$activity_report_filter_activity_type as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            &nbsp;
                        </div>
                    </div>
                    <div class="row expanded" id="customer_report_filters" style="display: none;">
                        <div class="large-3 columns">
                            <label for="customer_report_filter_customer">
                                Customer Name:
                                <select name="customer_report_filter_customer" id="customer_report_filter_customer" multiple>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="customer_report_filter_assigned_to">
                                Assigned to:
                                <select name="customer_report_filter_assigned_to" id="customer_report_filter_assigned_to" multiple>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            <label for="customer_report_filter_tags">
                                Tags:
                                <select name="customer_report_filter_tags" id="customer_report_filter_tags" multiple>
                                    <option value="">All</option>
                                    <?php
                                    $tags = CustomersController::getTagsForOrganization();
                                    foreach($tags as $tag){
                                        $id = $tag['id'];
                                        $tag = $tag['tag'];
                                        echo "<option value='$id'>$tag</option>";
                                    }
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div class="large-3 columns">
                            &nbsp;
                        </div>
                    </div>

                    <div class="row expanded">
                        <div class="large-1 columns text-left">
                            <input type="button" class="button tiny success" value="Generate" style="margin-top: 0px; width: 100%;" onclick="ajaxGenerateReports()">
                        </div>
                        <div class="large-1 columns text-left">
                            <input type="button" value="Clear Filters" class="alert button tiny" style="width: 100%;" onclick="clearFilters()">
                        </div>
                        <div class="large-10 columns text-left">
                            &nbsp;
                        </div>
                    </div>
                    {{ Form::close() }}

                    <div class="row expanded text-center" id="report_details_loader" style="display:none">
                        {{ HTML::image('assets/img/loading.gif', 'Loading...', array()) }}
                    </div>

                    <div class="row expanded">
                        <div id="report_details"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>



    <script>

        $( document ).ready(function() {

            var selectize_field_array = [
                'filter_report_type',
                'leads_report_filter_lead_source',
                'leads_report_filter_lead_rating',
                'quotations_report_filter_lead_source',
                'quotations_report_filter_status',
                'sales_report_filter_lead_source',
                'activity_report_filter_activity_type',
                'customer_report_filter_tags',
            ];

            selectize_field_array.forEach(function(field) {
                $('#'+field+'').selectize({
                    create: false
                });
            });

            getAssignedToEmployeesList();
            getCampaignsList();
        });

        function showFilters(obj) {
            // alert(obj.value);
            var toshow = obj.value;

            //Set all filters to none
            document.getElementById('leads_report_filters').style.display = 'none';
            document.getElementById('quotations_report_filters').style.display = 'none';
            document.getElementById('sales_report_filters').style.display = 'none';
            document.getElementById('activity_report_filters').style.display = 'none';
            document.getElementById('customer_report_filters').style.display = 'none';

            var toShowFilter = toshow + '_filters';

            document.getElementById('' + toShowFilter + '').style.display = 'block';

            //to clear the filter of the previous data
            clearFilters();

        }

        function ajaxGenerateReports() {

            $('#report_details').html('');
            $('#report_details').hide();
            $("#report_details_loader").show();

            var report_type = $('#filter_report_type').val();
            var dashboard_filter_date_range = $('#dashboard_filter_date_range').val();
            var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
            var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

            // check if report type is set
            if(!report_type) {
                $.notify('Please select report type.')
                return false;
            }

            //leads report filters
            var leads_report_filter_lead_source = $('#leads_report_filter_lead_source').val();
            var leads_report_filter_lead_rating = $('#leads_report_filter_lead_rating').val();
            var leads_report_filter_assigned_to = $('#leads_report_filter_assigned_to').val();
            var leads_report_filter_campaign = $('#leads_report_filter_campaign').val();

            //quotations report filters
            var quotations_report_filter_lead_source = $('#quotations_report_filter_lead_source').val();
            var quotations_report_filter_assigned_to = $('#quotations_report_filter_assigned_to').val();
            var quotations_report_filter_campaign = $('#quotations_report_filter_campaign').val();
            var quotations_report_filter_status = $('#quotations_report_filter_status').val();

            //sales report filters
            var sales_report_filter_lead_source = $('#sales_report_filter_lead_source').val();
            var sales_report_filter_assigned_to = $('#sales_report_filter_assigned_to').val();
            var sales_report_filter_campaign = $('#sales_report_filter_campaign').val();

            //activity report filters
            var activity_report_filter_customer = $('#activity_report_filter_customer').val();
            var activity_report_filter_activity_created_by = $('#activity_report_filter_activity_created_by').val();
            var activity_report_filter_activity_type = $('#activity_report_filter_activity_type').val();

            //customer report filters
            var customer_report_filter_customer = $('#customer_report_filter_customer').val();
            var customer_report_filter_assigned_to = $('#customer_report_filter_assigned_to').val();
            var customer_report_filter_tags = $('#customer_report_filter_tags').val();

            $.post("/reports/generate-report/", {
                report_type: report_type,
                dashboard_filter_date_range: dashboard_filter_date_range,
                dashboard_filter_from_date: dashboard_filter_from_date,
                dashboard_filter_to_date: dashboard_filter_to_date,

                //leads report filters
                leads_report_filter_lead_source: leads_report_filter_lead_source,
                leads_report_filter_lead_rating: leads_report_filter_lead_rating,
                leads_report_filter_assigned_to: leads_report_filter_assigned_to,
                leads_report_filter_campaign: leads_report_filter_campaign,

                //quotations report filters
                quotations_report_filter_lead_source: quotations_report_filter_lead_source,
                quotations_report_filter_assigned_to: quotations_report_filter_assigned_to,
                quotations_report_filter_campaign: quotations_report_filter_campaign,
                quotations_report_filter_status: quotations_report_filter_status,

                //sales report filters
                sales_report_filter_lead_source: sales_report_filter_lead_source,
                sales_report_filter_assigned_to: sales_report_filter_assigned_to,
                sales_report_filter_campaign: sales_report_filter_campaign,

                //activity report filters
                activity_report_filter_customer: activity_report_filter_customer,
                activity_report_filter_activity_created_by: activity_report_filter_activity_created_by,
                activity_report_filter_activity_type: activity_report_filter_activity_type,

                //customer report filters
                customer_report_filter_customer: customer_report_filter_customer,
                customer_report_filter_assigned_to: customer_report_filter_assigned_to,
                customer_report_filter_tags: customer_report_filter_tags,

            }, function (data) {

                $("#report_details_loader").hide();
                $('#report_details').show();
                $('#report_details').html(data);
                $(document).foundation();
            });
        }


        function clearFilters(){

            var clear_field_array = [
                'leads_report_filter_lead_source',
                'leads_report_filter_lead_rating',
                'quotations_report_filter_lead_source',
                'quotations_report_filter_status',
                'sales_report_filter_lead_source',
                'activity_report_filter_activity_type',
                'customer_report_filter_tags',
                'leads_report_filter_assigned_to',
                'quotations_report_filter_assigned_to',
                'sales_report_filter_assigned_to',
                'activity_report_filter_activity_created_by',
                'customer_report_filter_assigned_to',
                'leads_report_filter_campaign',
                'quotations_report_filter_campaign',
                'sales_report_filter_campaign',
                'activity_report_filter_customer',
                'customer_report_filter_customer',
            ];

            clear_field_array.forEach(function(field) {
                var generated_by_filter = $('#' + field).selectize();
                var control_generated_by_filter = generated_by_filter[0].selectize;
                control_generated_by_filter.clear();
            });

        }

        $(document).ready(function() {

            $('#search_lead_tags').selectize({
                create: false,
                sortField: 'text'
            });

            getAssignedToEmployeesList();
            getCampaignsList();
            getCustomersList();
        });

        function getAssignedToEmployeesList() {
            $.ajax({
                url: '/employees/ajax/get-employees-list'
            }).done(function (data) {

                data = $.parseJSON(data);

                var assigned_to_field_array = [
                    'leads_report_filter_assigned_to',
                    'quotations_report_filter_assigned_to',
                    'sales_report_filter_assigned_to',
                    'activity_report_filter_activity_created_by',
                    'customer_report_filter_assigned_to',
                ];

                assigned_to_field_array.forEach(function(field) {
                    $('#' + field).empty();
                    $('#' + field).append("<option value=''>Assigned to</option>");

                    for (var i in data) {
                        $('#' + field).append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                    }

                    $('#' + field).selectize({
                        create: false,
                        sortField: 'text'
                    });
                });

            });
        }

        function getCampaignsList() {
            $.ajax({
                url: '/campaigns/ajax/get-campaigns-list'
            }).done(function (data) {

                data = $.parseJSON(data);

                var campaign_field_array = [
                    'leads_report_filter_campaign',
                    'quotations_report_filter_campaign',
                    'sales_report_filter_campaign',
                ];

                campaign_field_array.forEach(function(field) {
                    $('#' + field).empty();
                    $('#' + field).append("<option value=''>Campaign</option>");

                    for (var i in data) {
                        $('#' + field).append("<option value='" + data[i].auto_tagged_campaign + "'>" + data[i].auto_tagged_campaign + "</option>");
                    }

                    $('#' + field).selectize({
                        create: false,
                        sortField: 'text'
                    });
                });
            });
        }

        function getCustomersList() {
            $.ajax({
                url: '/customers/ajax/load-customer-list'
            }).done(function(data){


                data = $.parseJSON(data);

                var campaign_field_array = [
                    'activity_report_filter_customer',
                    'customer_report_filter_customer',
                ];

                campaign_field_array.forEach(function(field) {
                    $('#' + field).empty();
                    $('#' + field).append("<option value=''>Select one</option>");

                    for (var i in data) {
                        $('#' + field).append("<option value='" + data[i].id + "'>" + data[i].customer_name + "</option>");
                    }

                    $('#' + field).selectize({
                        create: false,
                        sortField: 'text'
                    });
                });

            });
        }


    </script>
@stop