
<div class="row expanded">
    @if(Session::get('user-level') != 'sales')
        <div class="large-12 columns">
            <label for="my_leads_widget_assigned_to">
                Assigned To:
                <select name="my_leads_widget_assigned_to" id="my_leads_widget_assigned_to" multiple onchange="ajaxLoadMyLeadsDashboardChartData()">
                </select>
            </label>
        </div>
    @else
        <input type="hidden" id="my_leads_widget_assigned_to" style="display: none;" value="{{ Session::get('user-id') }}">
    @endif
</div>

<div class="row expanded">
    <div class="large-6 columns">
        <div class="panel">
            <div class="panel-heading">
                <h1>Lead Overview - Volume</h1>
            </div>
            <div class="panel-content">
                <div class="row">
                    <div class="large-12 columns">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_lead_overview_volume', 'style' => 'display:none')) }}
                        <div id="chart_lead_overview_volume"></div>
                    </div>
                </div>
            </div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(ajaxLoadMyLeadsDashboardLeadOverviewVolumeChart);

                function ajaxLoadMyLeadsDashboardLeadOverviewVolumeChart(){

                    var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
                    var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                    var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                    var my_leads_widget_assigned_to = $('#my_leads_widget_assigned_to').val();

                    $('#loader_chart_lead_overview_volume').show();
                    $('#chart_lead_overview_volume').hide();

                    $.ajax({
                        url: '/leads/ajax/load-my-leads-dashboard-lead-overview-volume-chart?' +
                        'dashboard_filter_date_range=' + dashboard_filter_date_range +
                        '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                        '&dashboard_filter_to_date=' + dashboard_filter_to_date +
                        '&my_leads_widget_assigned_to=' + my_leads_widget_assigned_to

                    }).done(function (data) {

                        if(data != '[]'){

                            var lead_overview_volume_chart_data = $.parseJSON(data);

                            /********  LEAD OVERVIEW BY VOLUME  *********/

                            $('#loader_chart_lead_overview_volume').hide();
                            $('#chart_lead_overview_volume').show();

                            var lead_overview_volume_chart_data_array = [['Leads', 'Number of Leads',{ role: 'style' }]];

                            lead_overview_volume_chart_data_array [1] = ['Raw Lead', parseInt(lead_overview_volume_chart_data[0].raw_lead_count), '#E7D900'];
                            lead_overview_volume_chart_data_array [2] = ['Lead', parseInt(lead_overview_volume_chart_data[0].lead_count), '#E7D900'];
                            lead_overview_volume_chart_data_array [3] = ['Junk', parseInt(lead_overview_volume_chart_data[0].junk_count), '#E7D900'];
                            lead_overview_volume_chart_data_array [4] = ['Duplicate', parseInt(lead_overview_volume_chart_data[0].duplicate_count), '#E7D900'];
                            lead_overview_volume_chart_data_array [5] = ['Quoted - Won', parseInt(lead_overview_volume_chart_data[0].quoted_won_count), '#E7D900'];
                            lead_overview_volume_chart_data_array [6] = ['Quoted - Negotiation', parseInt(lead_overview_volume_chart_data[0].quoted_negotiation_count), '#E7D900'];
                            lead_overview_volume_chart_data_array [7] = ['Quoted', parseInt(lead_overview_volume_chart_data[0].quoted_count) , '#E7D900'];
                            lead_overview_volume_chart_data_array [8] = ['Quoted - Lost', parseInt(lead_overview_volume_chart_data[0].quoted_lost_count), '#E7D900'];
                            lead_overview_volume_chart_data_array [9] = ['Total', parseInt(lead_overview_volume_chart_data[0].total_count), '#E7D900'];

                            var lead_overview_volume_data_chart = google.visualization.arrayToDataTable(lead_overview_volume_chart_data_array);

                            var lead_overview_volume_view = new google.visualization.DataView(lead_overview_volume_data_chart);
                            lead_overview_volume_view.setColumns([0, 1,
                                {
                                    calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation"
                                },
                                2]);

                            var lead_overview_volume_options = {
                                title: null,
                                width: "100%",
                                height: 300,
                                bar: {groupWidth: "90%"},
                                legend: {position: "none"},
                            };
                            var lead_overview_volume_chart = new google.visualization.ColumnChart(document.getElementById("chart_lead_overview_volume"));
                            lead_overview_volume_chart.draw(lead_overview_volume_view, lead_overview_volume_options);

                            /********** END LEAD OVERVIEW BY VOLUME  *********/



                        }else{

                            $('#loader_chart_lead_overview_volume').hide();
                            $('#chart_lead_overview_volume').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                            $('#chart_lead_overview_volume').show();

                        }
                    });
                }

            </script>
        </div>
    </div>
    <div class="large-6 columns">
        <div class="panel">
            <div class="panel-heading">
                <h1>Lead Overview - Value</h1>
            </div>
            <div class="panel-content">
                <div class="row">
                    <div class="large-12 columns">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_lead_overview_value', 'style' => 'display:none')) }}
                        <div id="chart_lead_overview_value"></div>
                    </div>
                </div>
            </div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(ajaxLoadMyLeadsDashboardLeadOverviewValueChart);

                function ajaxLoadMyLeadsDashboardLeadOverviewValueChart(){

                    var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
                    var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                    var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                    var my_leads_widget_assigned_to = $('#my_leads_widget_assigned_to').val();

                    $('#loader_chart_lead_overview_value').show();
                    $('#chart_lead_overview_value').hide();

                    $.ajax({
                        url: '/leads/ajax/load-my-leads-dashboard-lead-overview-value-chart?' +
                        'dashboard_filter_date_range=' + dashboard_filter_date_range +
                        '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                        '&dashboard_filter_to_date=' + dashboard_filter_to_date +
                        '&my_leads_widget_assigned_to=' + my_leads_widget_assigned_to

                    }).done(function (data) {

                        if(data != '[]'){

                            var lead_overview_value_chart_data = $.parseJSON(data);

                            /********  LEAD OVERVIEW BY VALUE  *********/

                            $('#loader_chart_lead_overview_value').hide();
                            $('#chart_lead_overview_value').show();

                            var lead_overview_value_chart_data_array = [['Leads', 'Leads Value',{ role: 'style' }]];

                            lead_overview_value_chart_data_array [1] = ['Quoted - Won', parseInt(lead_overview_value_chart_data[0].quoted_won_value), '#E7D900'];
                            lead_overview_value_chart_data_array [2] = ['Quoted - Negotiation', parseInt(lead_overview_value_chart_data[0].quoted_negotiation_value), '#E7D900'];
                            lead_overview_value_chart_data_array [3] = ['Quoted', parseInt(lead_overview_value_chart_data[0].quoted_value) , '#E7D900'];
                            lead_overview_value_chart_data_array [4] = ['Quoted - Lost', parseInt(lead_overview_value_chart_data[0].quoted_lost_value), '#E7D900'];
                            lead_overview_value_chart_data_array [5] = ['Total', parseInt(lead_overview_value_chart_data[0].total_value), '#E7D900'];

                            var lead_overview_value_data_chart = google.visualization.arrayToDataTable(lead_overview_value_chart_data_array);

                            var lead_overview_value_view = new google.visualization.DataView(lead_overview_value_data_chart);
                            lead_overview_value_view.setColumns([0, 1,
                                {
                                    calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation"
                                },
                                2]);

                            var lead_overview_value_options = {
                                title: null,
                                width: "100%",
                                height: 300,
                                bar: {groupWidth: "100%"},
                                legend: {position: "none"},
                            };
                            var lead_overview_value_chart = new google.visualization.ColumnChart(document.getElementById("chart_lead_overview_value"));
                            lead_overview_value_chart.draw(lead_overview_value_view, lead_overview_value_options);

                            /********** END LEAD OVERVIEW BY VALUE  *********/


                        }else{

                            $('#loader_chart_lead_overview_value').hide();
                            $('#chart_lead_overview_value').html('<p class="alert-box info radius">No Leads Value Available For the Selected Time Period.</p>');
                            $('#chart_lead_overview_value').show();

                        }
                    });
                }

            </script>
        </div>
    </div>
</div>

<div class="row expanded">
    <div class="large-6 columns">
        <div class="panel">
            <div class="panel-heading">
                <h1>Lead Volume By Campaigns</h1>
            </div>
            <div class="panel-content">
                <div class="row">
                    <div class="large-12 columns">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_lead_volume_by_campaign', 'style' => 'display:none')) }}
                        <div id="chart_lead_volume_by_campaign"></div>
                    </div>
                </div>
            </div>
            <script>

                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(ajaxLoadMyLeadsDashboardLeadVolumeByCampaignChart);

                function ajaxLoadMyLeadsDashboardLeadVolumeByCampaignChart(){

                    var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
                    var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                    var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                    var my_leads_widget_assigned_to = $('#my_leads_widget_assigned_to').val();

                    $('#loader_chart_lead_volume_by_campaign').show();
                    $('#chart_lead_volume_by_campaign').hide();

                    $.ajax({
                        url: '/leads/ajax/load-my-leads-dashboard-lead-volume-by-campaign-chart?' +
                        'dashboard_filter_date_range=' + dashboard_filter_date_range +
                        '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                        '&dashboard_filter_to_date=' + dashboard_filter_to_date +
                        '&my_leads_widget_assigned_to=' + my_leads_widget_assigned_to

                    }).done(function (data) {

                        if(data != '[]'){

                            var lead_volume_by_campaigns_chart_data = $.parseJSON(data);

                            /********  LEAD VOLUME BY CAMPAIGN  *********/

                            $('#loader_chart_lead_volume_by_campaign').hide();
                            $('#chart_lead_volume_by_campaign').show();

                            var lead_volume_by_campaign_chart_data_array = [['Campaign', 'Campaign Volume']];

                            for(var i=1; i<= lead_volume_by_campaigns_chart_data.length; i++) {
                                lead_volume_by_campaign_chart_data_array[i] = [lead_volume_by_campaigns_chart_data[i-1].campaign, parseInt(lead_volume_by_campaigns_chart_data[i-1].campaign_count)];
                            }

                            console.log(lead_volume_by_campaign_chart_data_array);

                            var lead_volume_by_campaign_data_chart = google.visualization.arrayToDataTable(lead_volume_by_campaign_chart_data_array);

                            console.log(lead_volume_by_campaign_data_chart);

                            var lead_volume_by_campaign_options = {
                                title: null,
                                pieHole: 0.4,
                                height:300,
                                sliceVisibilityThreshold: 0,
                                legend: {
                                    position: 'labeled'
                                }
                            };
                            var lead_volume_by_campaign_chart = new google.visualization.PieChart(document.getElementById("chart_lead_volume_by_campaign"));
                            lead_volume_by_campaign_chart.draw(lead_volume_by_campaign_data_chart, lead_volume_by_campaign_options);

                            /********** END LEAD VOLUME BY CAMPAIGN  *********/


                        }else{

                            $('#loader_chart_lead_volume_by_campaign').hide();
                            $('#chart_lead_volume_by_campaign').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                            $('#chart_lead_volume_by_campaign').show();
                        }
                    });
                }
            </script>
        </div>
    </div>
    <div class="large-6 columns">
        <div class="panel">
            <div class="panel-heading">
                <h1>Lead Value By Campaigns</h1>
            </div>
            <div class="panel-content">
                <div class="row">
                    <div class="large-12 columns">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_lead_value_by_campaign', 'style' => 'display:none')) }}
                        <div id="chart_lead_value_by_campaign"></div>
                    </div>
                </div>
            </div>
            <script>

                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(ajaxLoadMyLeadsDashboardLeadValueByCampaignChart);

                function ajaxLoadMyLeadsDashboardLeadValueByCampaignChart(){

                    var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
                    var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                    var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                    var my_leads_widget_assigned_to = $('#my_leads_widget_assigned_to').val();

                    $('#loader_chart_lead_value_by_campaign').show();
                    $('#chart_lead_value_by_campaign').hide();

                    $.ajax({
                        url: '/leads/ajax/load-my-leads-dashboard-lead-value-by-campaign-chart?' +
                        'dashboard_filter_date_range=' + dashboard_filter_date_range +
                        '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                        '&dashboard_filter_to_date=' + dashboard_filter_to_date +
                        '&my_leads_widget_assigned_to=' + my_leads_widget_assigned_to

                    }).done(function (data) {

                        if(data != '[]'){

                            var lead_value_by_campaigns_chart_data = $.parseJSON(data);

                            /********  LEAD VALUE BY CAMPAIGN  *********/

                            $('#loader_chart_lead_value_by_campaign').hide();
                            $('#chart_lead_value_by_campaign').show();

                            var lead_value_by_campaign_chart_data_array = [['Campaign', 'Campaign Value']];

                            for(var i=1; i<= lead_value_by_campaigns_chart_data.length; i++) {
                                lead_value_by_campaign_chart_data_array[i] = [lead_value_by_campaigns_chart_data[i-1].campaign, parseInt(lead_value_by_campaigns_chart_data[i-1].campaign_value)];
                            }

                            console.log(lead_value_by_campaign_chart_data_array);

                            var lead_value_by_campaign_data_chart = google.visualization.arrayToDataTable(lead_value_by_campaign_chart_data_array);

                            console.log(lead_value_by_campaign_data_chart);

                            var lead_value_by_campaign_options = {
                                title: null,
                                height:300,
                                sliceVisibilityThreshold: 0,
                                legend: {
                                    position: 'labeled'
                                },
                                pieSliceText: 'value'
                            };

                            var lead_value_by_campaign_chart = new google.visualization.PieChart(document.getElementById("chart_lead_value_by_campaign"));
                            lead_value_by_campaign_chart.draw(lead_value_by_campaign_data_chart, lead_value_by_campaign_options);

                            /********** END LEAD VALUE BY CAMPAIGN  *********/


                        }else{

                            $('#loader_chart_lead_value_by_campaign').hide();
                            $('#chart_lead_value_by_campaign').html('<p class="alert-box info radius">No Leads Value Available For the Selected Time Period.</p>');
                            $('#chart_lead_value_by_campaign').show();
                        }
                    });
                }
            </script>
        </div>
    </div>
</div>

<script>

    function ajaxLoadMyLeadsDashboardChartData(){
        ajaxLoadMyLeadsDashboardLeadOverviewVolumeChart();
        ajaxLoadMyLeadsDashboardLeadOverviewValueChart();
        ajaxLoadMyLeadsDashboardLeadVolumeByCampaignChart();
        ajaxLoadMyLeadsDashboardLeadValueByCampaignChart();
    }

</script>