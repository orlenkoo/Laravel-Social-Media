@extends('layouts.v1_layout')

@section('content')


    <div class="row expanded">
        <div class="large-12 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Event 360 Report</h1>
                </div>
                <div class="panel-content">

                    <div class="row expanded">
                        <div class="large-3 columns">
                            <div class="controls">
                                <label>Start Date</label>
                                {{ Form::text('start_date',$from_date, array('id'=> 'start_date', 'class'=> 'datepicker')) }}
                            </div>
                        </div>

                        <div class="large-3 columns">

                            <div class="controls">
                                <label>End Date</label>
                                {{ Form::text('end_date',$to_date, array('id'=> 'end_date', 'class'=> 'datepicker' )) }}
                            </div>

                        </div>
                        <div class="large-3 columns">&nbsp;</div>
                        <div class="large-3 columns">&nbsp;</div>
                    </div>
                    <div class="row expanded">
                        <div class="large-3 columns">
                            <div class="controls">
                                <a href="#" onclick="getChartMetricReport();" class="button radius tiny">Generate Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row expanded">
        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Reach & Engagement</h1>
                </div>
                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'engagement_metric_loader', 'style' => 'display:none')) }}
                    <div id="engagement_metric_statistics"></div>
                    <script type="text/javascript">

                        function getEngagementMetricReport() {
                            $('#engagement_metric_loader').show();
                            $('#engagement_metric_statistics').hide();
                            $('#engagement_metric_statistics').html('');

                            var start_date = $("#start_date").val();
                            var end_date = $("#end_date").val();

                            $.ajax({
                                url: 'event360-pixel-report/get-engagement-metric-report?start_date=' + start_date + '&end_date=' + end_date
                            }).done(function (data) {
                                if (data != -1) {
                                    $('#engagement_metric_loader').hide();
                                    $('#engagement_metric_statistics').show();
                                    $('#engagement_metric_statistics').html(data);
                                } else {
                                    $('#engagement_metric_loader').hide();
                                    $('#engagement_metric_statistics').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                    $('#engagement_metric_statistics').show();
                                }

                                $(document).foundation('equalizer', 'reflow');
                            });
                        }

                    </script>
                </div>
            </div>
        </div>

        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>ROI</h1>
                </div>
                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'roi_metric_loader', 'style' => 'display:none')) }}
                    <div id="roi_metric_statistics"></div>
                    <script type="text/javascript">

                        function getROIMetricReport() {
                            $('#roi_metric_loader').show();
                            $('#roi_metric_statistics').hide();
                            $('#roi_metric_statistics').html('');

                            var start_date = $("#start_date").val();
                            var end_date = $("#end_date").val();

                            $.ajax({
                                url: 'event360-pixel-report/get-roi-metric-report?start_date=' + start_date + '&end_date=' + end_date
                            }).done(function (data) {
                                if (data != -1) {
                                    $('#roi_metric_loader').hide();
                                    $('#roi_metric_statistics').show();
                                    $('#roi_metric_statistics').html(data);
                                } else {
                                    $('#roi_metric_loader').hide();
                                    $('#roi_metric_statistics').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                    $('#roi_metric_statistics').show();
                                }

                                $(document).foundation('equalizer', 'reflow');
                            });
                        }
                    </script>
                </div>
            </div>
        </div>

        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Leads Volume Comparison</h1>
                </div>

                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'lead_volume_comparison_loader', 'style' => 'display:none')) }}
                    <div id="lead_volume_comparison_div"></div>
                    <script type="text/javascript">

                        function getLeadVolumeComparisonReport() {

                            $('#lead_volume_comparison_loader').show();
                            $('#lead_volume_comparison_div').hide();
                            $('#lead_volume_comparison_div').html('');

                            var start_date = $("#start_date").val();
                            var end_date = $("#end_date").val();

                            google.charts.load('current', {'packages': ['corechart']});

                            $.ajax({
                                url: 'event360-pixel-report/get-lead-volume-comparison?start_date=' + start_date + '&end_date=' + end_date
                            }).done(function (data) {

                                if (data != -1) {
                                    $('#lead_volume_comparison_loader').hide();
                                    $('#lead_volume_comparison_div').show();

                                    var datat = new google.visualization.DataTable();
                                    datat.addColumn('string', 'Topping');
                                    datat.addColumn('number', 'Slices');
                                    datat.addRows([
                                        ["Event360 Lead", data.event360_lead_count],
                                        ["Web360 Lead", data.web360_lead_count]
                                    ]);

                                    var chart = new google.visualization.PieChart(document.getElementById("lead_volume_comparison_div"));
                                    chart.draw(datat);
                                } else {
                                    $('#lead_volume_comparison_loader').hide();
                                    $('#lead_volume_comparison_div').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                    $('#lead_volume_comparison_div').show();
                                }

                                $(document).foundation('equalizer', 'reflow');
                            });
                        }

                    </script>
                </div>
            </div>
        </div>
    </div>



    <div class="row expanded">
        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Leads Breakdown By Type</h1>
                </div>

                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'lead_breakdown_by_type_loader', 'style' => 'display:none')) }}

                    <div id="lead_breakdown_by_type_div"></div>
                    <script type="text/javascript">

                        function showLeadBreakdownByTypeChart() {

                            $('#lead_breakdown_by_type_loader').show();
                            $('#lead_breakdown_by_type_div').hide();
                            $('#lead_breakdown_by_type_div').html('');

                            var start_date = $("#start_date").val();
                            var end_date = $("#end_date").val();

                            google.charts.load('current', {'packages': ['corechart']});

                            $.ajax({
                                url: 'event360-pixel-report/get-lead-breakdown-by-type?start_date=' + start_date + '&end_date=' + end_date
                            }).done(function (data) {

                                if (data != -1 && data != '') {

                                    var lead_breakdown_array = [];

                                    for (var k in data) {

                                        lead_breakdown_array.push([data[k]['lead_source'], data[k]['lead_count']]);
                                    }

                                    $('#lead_breakdown_by_type_loader').hide();
                                    $('#lead_breakdown_by_type_div').show();

                                    var datat = new google.visualization.DataTable();
                                    datat.addColumn('string', 'Topping');
                                    datat.addColumn('number', 'Slices');
                                    datat.addRows(lead_breakdown_array);

                                    var chart = new google.visualization.PieChart(document.getElementById("lead_breakdown_by_type_div"));
                                    chart.draw(datat);
                                } else {
                                    $('#lead_breakdown_by_type_loader').hide();
                                    $('#lead_breakdown_by_type_div').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                    $('#lead_breakdown_by_type_div').show();
                                }

                                $(document).foundation('equalizer', 'reflow');
                            });
                        }
                    </script>
                </div>
            </div>
        </div>

        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Event360 Leads breakdown by Lead Rating</h1>
                </div>

                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'lead_by_breakdown_loader', 'style' => 'display:none')) }}
                    <div id="lead_by_breakdown_div"></div>
                    <script type="text/javascript">
                        function getLeadBreakdownByLeadRatingReport() {

                            $('#lead_by_breakdown_loader').show();
                            $('#lead_by_breakdown_div').hide();
                            $('#lead_by_breakdown_div').html('');

                            var start_date = $("#start_date").val();
                            var end_date = $("#end_date").val();

                            google.charts.load('current', {'packages': ['corechart']});

                            $.ajax({
                                url: 'event360-pixel-report/get-leads-breakdown-by-lead-rating?start_date=' + start_date + '&end_date=' + end_date
                            }).done(function (data) {

                                if (data != -1 && data != '') {

                                    var lead_breakdown_array = [];

                                    for (var k in data) {
                                        lead_breakdown_array.push([data[k]['lead_rating'], data[k]['lead_count']]);
                                    }

                                    $('#lead_by_breakdown_loader').hide();
                                    $('#lead_by_breakdown_div').show();

                                    var datat = new google.visualization.DataTable();
                                    datat.addColumn('string', 'Topping');
                                    datat.addColumn('number', 'Slices');
                                    datat.addRows(lead_breakdown_array);

                                    var chart = new google.visualization.PieChart(document.getElementById("lead_by_breakdown_div"));
                                    chart.draw(datat);
                                } else {
                                    $('#lead_by_breakdown_loader').hide();
                                    $('#lead_by_breakdown_div').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                    $('#lead_by_breakdown_div').show();
                                }

                                $(document).foundation('equalizer', 'reflow');
                            });
                        }
                    </script>
                </div>
            </div>
        </div>

    </div>


    <div class="row expanded">
        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Your Website Leads (Last 5 Weeks)</h1>
                </div>

                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_homepage_widget_website_leads_chart', 'style' => 'display:none')) }}
                    <div id="homepage_widget_website_leads_chart"></div>
                    <script type="text/javascript">

                        // Load the Visualization API and the corechart package.
                        google.charts.load('current', {'packages': ['corechart']});

                        //                            // Set a callback to run when the Google Visualization API is loaded.
                        //                            google.charts.setOnLoadCallback(drawActivityChart);

                        // Callback that creates and populates a data table,
                        // instantiates the pie chart, passes in the data and
                        // draws it.
                        function homePageWidgetWebsiteLeadsChart() {
                            $('#loader_homepage_widget_website_leads_chart').show();
                            $('#homepage_widget_website_leads_chart').hide();


                            $.ajax({
                                url: '/event360-leads/ajax/homepage-widget-leads-weekly?lead_source=web360_enquiries'
                            }).done(function (data) {

                                if(data != '[]'){

                                    $('#loader_homepage_widget_website_leads_chart').hide();
                                    $('#homepage_widget_website_leads_chart').show();

                                    //console.log(data);
                                    var chart_data = $.parseJSON(data);
                                    var chart_data_array = [['Week at', 'Leads',{ role: 'style' }]];
                                    for(var i=1; i<= chart_data.length; i++) {
                                        chart_data_array [i] = [chart_data[i-1].date, chart_data[i-1].leads, '#49bc1b'];
                                    }

                                    var data_chart = google.visualization.arrayToDataTable(chart_data_array);

                                    var view = new google.visualization.DataView(data_chart);
                                    view.setColumns([0, 1,
                                        {
                                            calc: "stringify",
                                            sourceColumn: 1,
                                            type: "string",
                                            role: "annotation"
                                        },
                                        2]);

                                    var options = {
                                        title: null,
                                        width: "100%",
                                        height: 300,
                                        bar: {groupWidth: "90%"},
                                        legend: {position: "none"},
                                    };
                                    var chart = new google.visualization.ColumnChart(document.getElementById("homepage_widget_website_leads_chart"));
                                    chart.draw(view, options);
                                }else{

                                    $('#loader_homepage_widget_website_leads_chart').hide();
                                    $('#homepage_widget_website_leads_chart').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                    $('#homepage_widget_website_leads_chart').show();
                                }

                            });

                        }


                    </script>
                </div>
            </div>
        </div>

        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Event360 Online Leads (Last 5 Weeks)</h1>
                </div>

                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_homepage_widget_event360_leads_chart', 'style' => 'display:none')) }}
                    <div id="homepage_widget_event360_leads_chart"></div>
                    <script type="text/javascript">

                        // Load the Visualization API and the corechart package.
                        google.charts.load('current', {'packages': ['corechart']});

                        //                            // Set a callback to run when the Google Visualization API is loaded.
                        //                            google.charts.setOnLoadCallback(drawActivityChart);

                        // Callback that creates and populates a data table,
                        // instantiates the pie chart, passes in the data and
                        // draws it.
                        function homePageWidgetEvent360LeadsChart() {
                            $('#loader_homepage_widget_event360_leads_chart').show();
                            $('#homepage_widget_event360_leads_chart').hide();


                            $.ajax({
                                url: '/event360-leads/ajax/homepage-widget-leads-weekly?lead_source=event360_enquiries'
                            }).done(function (data) {


                                if (data != '[]') {


                                    $('#loader_homepage_widget_event360_leads_chart').hide();
                                    $('#homepage_widget_event360_leads_chart').show();

                                    console.log(data);
                                    var chart_data = $.parseJSON(data);
                                    var chart_data_array = [['Week at', 'Leads', {role: 'style'}]];
                                    for (var i = 1; i <= chart_data.length; i++) {
                                        chart_data_array [i] = [chart_data[i - 1].date, chart_data[i - 1].leads, '#1b6bbc'];
                                    }

                                    console.log(chart_data_array);


                                    var data_chart = google.visualization.arrayToDataTable(chart_data_array);

                                    var view = new google.visualization.DataView(data_chart);
                                    view.setColumns([0, 1,
                                        {
                                            calc: "stringify",
                                            sourceColumn: 1,
                                            type: "string",
                                            role: "annotation"
                                        },
                                        2]);

                                    var options = {
                                        title: null,
                                        width: "100%",
                                        height: 300,
                                        bar: {groupWidth: "90%"},
                                        legend: {position: "none"},
                                    };
                                    var chart = new google.visualization.ColumnChart(document.getElementById("homepage_widget_event360_leads_chart"));
                                    chart.draw(view, options);
                                }else{
                                    $('#loader_homepage_widget_event360_leads_chart').hide();
                                    $('#homepage_widget_event360_leads_chart').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                    $('#homepage_widget_event360_leads_chart').show();

                                }
                            });

                        }


                    </script>
                </div>
            </div>
        </div>

        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Event360 Message Leads (Last 5 Weeks)</h1>
                </div>

                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_homepage_widget_message_leads_chart', 'style' => 'display:none')) }}
                    <div id="homepage_widget_message_leads_chart"></div>
                    <script type="text/javascript">

                        // Load the Visualization API and the corechart package.
                        google.charts.load('current', {'packages': ['corechart']});

                        //                            // Set a callback to run when the Google Visualization API is loaded.
                        //                            google.charts.setOnLoadCallback(drawActivityChart);

                        // Callback that creates and populates a data table,
                        // instantiates the pie chart, passes in the data and
                        // draws it.
                        function homePageWidgetMessageLeadsChart() {
                            $('#loader_homepage_widget_message_leads_chart').show();
                            $('#homepage_widget_message_leads_chart').hide();


                            $.ajax({
                                url: '/event360-leads/ajax/homepage-widget-leads-weekly?lead_source=event360_messenger_threads'
                            }).done(function (data) {

                                if (data != '[]') {
                                    $('#loader_homepage_widget_message_leads_chart').hide();
                                    $('#homepage_widget_message_leads_chart').show();

                                    console.log(data);
                                    var chart_data = $.parseJSON(data);
                                    var chart_data_array = [['Week at', 'Leads', {role: 'style'}]];
                                    for (var i = 1; i <= chart_data.length; i++) {
                                        chart_data_array [i] = [chart_data[i - 1].date, chart_data[i - 1].leads, '#bc1b4c'];
                                    }

                                    console.log(chart_data_array);


                                    var data_chart = google.visualization.arrayToDataTable(chart_data_array);

                                    var view = new google.visualization.DataView(data_chart);
                                    view.setColumns([0, 1,
                                        {
                                            calc: "stringify",
                                            sourceColumn: 1,
                                            type: "string",
                                            role: "annotation"
                                        },
                                        2]);

                                    var options = {
                                        title: null,
                                        width: "100%",
                                        height: 300,
                                        bar: {groupWidth: "90%"},
                                        legend: {position: "none"},
                                    };
                                    var chart = new google.visualization.ColumnChart(document.getElementById("homepage_widget_message_leads_chart"));
                                    chart.draw(view, options);
                                }else{
                                    $('#loader_homepage_widget_message_leads_chart').hide();
                                    $('#homepage_widget_message_leads_chart').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                    $('#homepage_widget_message_leads_chart').show();
                                }
                            });

                        }


                    </script>
                </div>
            </div>
        </div>

        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Event360 Call Leads (Last 5 Weeks)</h1>
                </div>

                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_homepage_widget_call_leads_chart', 'style' => 'display:none')) }}
                    <div id="homepage_widget_call_leads_chart"></div>
                    <script type="text/javascript">

                        // Load the Visualization API and the corechart package.
                        google.charts.load('current', {'packages': ['corechart']});

                        //                            // Set a callback to run when the Google Visualization API is loaded.
                        //                            google.charts.setOnLoadCallback(drawActivityChart);

                        // Callback that creates and populates a data table,
                        // instantiates the pie chart, passes in the data and
                        // draws it.
                        function homePageWidgetCallLeadsChart() {
                            $('#loader_homepage_widget_call_leads_chart').show();
                            $('#homepage_widget_call_leads_chart').hide();


                            $.ajax({
                                url: '/event360-leads/ajax/homepage-widget-leads-weekly?lead_source=event360_calls'
                            }).done(function (data) {

                                if (data != '[]') {
                                    $('#loader_homepage_widget_call_leads_chart').hide();
                                    $('#homepage_widget_call_leads_chart').show();

                                    console.log(data);
                                    var chart_data = $.parseJSON(data);
                                    var chart_data_array = [['Week at', 'Leads', {role: 'style'}]];
                                    for (var i = 1; i <= chart_data.length; i++) {
                                        chart_data_array [i] = [chart_data[i - 1].date, chart_data[i - 1].leads, '#1bbc98'];
                                    }

                                    console.log(chart_data_array);


                                    var data_chart = google.visualization.arrayToDataTable(chart_data_array);

                                    var view = new google.visualization.DataView(data_chart);
                                    view.setColumns([0, 1,
                                        {
                                            calc: "stringify",
                                            sourceColumn: 1,
                                            type: "string",
                                            role: "annotation"
                                        },
                                        2]);

                                    var options = {
                                        title: null,
                                        width: "100%",
                                        height: 300,
                                        bar: {groupWidth: "90%"},
                                        legend: {position: "none"},
                                    };
                                    var chart = new google.visualization.ColumnChart(document.getElementById("homepage_widget_call_leads_chart"));
                                    chart.draw(view, options);
                                }else{
                                    $('#loader_homepage_widget_call_leads_chart').hide();
                                    $('#homepage_widget_call_leads_chart').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                    $('#homepage_widget_call_leads_chart').show();
                                }
                            });

                        }


                    </script>
                </div>
            </div>
        </div>
    </div>




    <script>

        function getChartMetricReport() {

            getEngagementMetricReport();
            getROIMetricReport();
            showLeadBreakdownByTypeChart();
            getLeadVolumeComparisonReport();
            getLeadBreakdownByLeadRatingReport();
            homePageWidgetWebsiteLeadsChart();
            homePageWidgetEvent360LeadsChart();
            homePageWidgetCallLeadsChart();
            homePageWidgetMessageLeadsChart();


        }

        getChartMetricReport();

        setInterval(function(){ getChartMetricReport(); }, 300000);
    </script>

@stop



