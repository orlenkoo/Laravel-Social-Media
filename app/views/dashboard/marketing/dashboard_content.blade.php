<ul class="tabs" data-tabs id="tabs_marketing_dashboard">
    <li class="tabs-title is-active"><a href="#panel_leads_management" aria-selected="true">Leads Management</a></li>
    <li class="tabs-title"><a href="#panel_my_efficiency" onclick="ajaxLoadReportsActivityEfficiencyTable();ajaxLoadReportsSalesEfficiencyTable();">My Efficiency</a></li>
    <li class="tabs-title"><a href="#panel_my_leads" onclick="ajaxLoadMyLeadsDashboardChartData()">My Leads</a></li>
    <li class="tabs-title"><a href="#panel_my_dashboard">My Dashboard</a></li>
</ul>

<div class="tabs-content" data-tabs-content="tabs_marketing_dashboard">
    <div class="tabs-panel is-active" id="panel_leads_management">
        <div class="row expanded">
            <div class="large-4 columns">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="large-6 columns">
                                <h1>Leads</h1>
                            </div>
                            <div class="large-6 columns">
                                <button class="button tiny float-right" type="button"
                                        data-open="reveal_add_new_lead">Add New Lead
                                </button>
                                <div class="reveal panel small" id="reveal_add_new_lead" data-reveal>
                                    <div class="panel-heading">
                                        <h4>Add New Lead</h4>
                                    </div>
                                    <div class="panel-content">

                                        @include('leads._partials.add_new_lead_form')

                                        <button class="close-button" data-close aria-label="Close modal"
                                                type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-content">
                        @include('dashboard.common._partials.leads_filter_and_leads_list_response')
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
            <div class="large-8 columns">
                <div class="panel">
                    <div class="panel-content">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_dashboard_lead_details', 'class' => 'float-center', 'style' => 'display:none')) }}
                        <div id="response_dashboard_lead_details">

                        </div><!--end response_dashboard_lead_details-->
                    </div>
                </div>
            </div>


        </div>
    </div><!--end panel_leads_management-->

    <div class="tabs-panel" id="panel_my_efficiency">
        <div class="row expanded">
            @include('dashboard.common._partials.my_efficiency_widget')
        </div>
    </div><!--end panel_my_efficiency-->

    <div class="tabs-panel" id="panel_my_leads">
        <div class="row expanded">
            @include('dashboard.common._partials.my_leads_widget')
        </div>
    </div><!--end panel_my_leads-->

    <div class="tabs-panel" id="panel_my_dashboard">
        <div class="row expanded">
            <div class="large-8 columns">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="large-12 columns">
                                <h1>Campaign Performance</h1>
                            </div>
                        </div>
                    </div>
                    <div class="panel-content">
                        <div class="row">
                            <div class="large-12 columns">
                                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_dashboard_campaign_performance_table', 'class' => 'float-center', 'style' => 'display:none')) }}
                                <div id="dashboard_campaign_performance_table"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="large-4 columns">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="large-12 columns">
                                <h1>Lead Statistics</h1>
                            </div>
                        </div>
                    </div>
                    <div class="panel-content">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_dashboard_lead_statistics_table', 'class' => 'float-center', 'style' => 'display:none')) }}
                        <div id="dashboard_lead_statistics_table"></div>
                    </div>
                </div>
            </div>
        </div>

    </div><!--end panel_my_dashboard-->
</div>

<script>
    // this function gets repeated in all the dashboards. And will have calls to functions that load content on those dashboards
    function loadDashboardData() {
        ajaxLoadDashboardLeadsList(1);
        ajaxLoadDashboardLeadStatistics();
        ajaxLoadDashboardCampaignPerformance();
        ajaxLoadMyLeadsDashboardChartData();
        ajaxLoadReportsActivityEfficiencyTable();
        ajaxLoadReportsSalesEfficiencyTable();
    }

    $( document ).ready(function() {
        loadDashboardData();
    });

    function ajaxLoadDashboardLeadStatistics() {
        $("#loader_dashboard_lead_statistics_table").show();
        $("#dashboard_lead_statistics_table").hide();
        $("#dashboard_lead_statistics_table").html('');

        var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
        var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
        var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

        $.ajax({
            url: '/leads/ajax/load-dashboard-lead-statistics?dashboard_filter_date_range='+dashboard_filter_date_range +
            '&dashboard_filter_from_date='+dashboard_filter_from_date+
            '&dashboard_filter_to_date='+dashboard_filter_to_date
        }).done(function (data) {

            $("#dashboard_lead_statistics_table").html(data);
            $("#loader_dashboard_lead_statistics_table").hide();
            $("#dashboard_lead_statistics_table").show();
        });
    }

    function ajaxLoadDashboardCampaignPerformance() {

        $("#loader_dashboard_campaign_performance_table").show();
        $("#dashboard_campaign_performance_table").hide();
        $("#dashboard_campaign_performance_table").html('');

        var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
        var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
        var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

        $.ajax({
            url: '/leads/ajax/load-dashboard-campaign_performance?dashboard_filter_date_range='+dashboard_filter_date_range +
            '&dashboard_filter_from_date='+dashboard_filter_from_date+
            '&dashboard_filter_to_date='+dashboard_filter_to_date
        }).done(function (data) {

            $("#dashboard_campaign_performance_table").html(data);
            $("#loader_dashboard_campaign_performance_table").hide();
            $("#dashboard_campaign_performance_table").show();
        });
    }

    function ajaxLoadReportsActivityEfficiencyTable(){

        var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
        var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
        var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

        $('#loader_activities_efficiency_table').show();
        $('#activities_efficiency_table_div').hide();
        $('#activities_efficiency_table_div').html('');

        $.ajax({
            url: '/reports/ajax/load-report-activity-efficiency-table?' +
            'dashboard_filter_date_range=' + dashboard_filter_date_range +
            '&dashboard_filter_from_date=' + dashboard_filter_from_date +
            '&dashboard_filter_to_date=' + dashboard_filter_to_date
        }).done(function (data) {

            $('#activities_efficiency_table_div').html(data);
            $('#loader_activities_efficiency_table').hide();
            $('#activities_efficiency_table_div').show();

        });

    }


    function ajaxLoadReportsSalesEfficiencyTable(){

        $('#loader_sales_efficiency_table').show();
        $('#sales_efficiency_table_div').hide();
        $('#sales_efficiency_table_div').html('');

        $.ajax({
            url: '/reports/ajax/load-report-sales-efficiency-table?'

        }).done(function (data) {

            $('#sales_efficiency_table_div').html(data);
            $('#loader_sales_efficiency_table').hide();
            $('#sales_efficiency_table_div').show();
        });

    }

</script>


</script>