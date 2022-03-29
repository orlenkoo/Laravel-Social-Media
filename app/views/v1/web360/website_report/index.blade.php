@extends('layouts.v1_layout')

@section('content')


    <div class="row expanded">
        <div class="large-12 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Website Report</h1>
                </div>

                <div class="panel-content">
                    <div class="row expanded">
                        <div class="large-3 columns">
                            <div class="controls">
                                <label>Website Properties</label>
                                {{ Form::select('webtics_pixel_property', WebticsPixelProperty::getWebticsPixelPropertyNames(),
                                                    null, array('id' => 'webtics_pixel_property')) }}
                            </div>
                        </div>

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

                        <div class="large-3 columns">
                            <div class="controls">
                                <label>&nbsp;</label>
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
                        <h1>
                            Engagement
                        </h1>
                </div>
                <div class="panel-content">
                    <div class="row expanded">
                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'engagement_metric_loader', 'style' => 'display:none')) }}
                            <div id="engagement_metric_statistics"></div>
                            <script type="text/javascript">

                                function getEngagementMetricReport() {
                                    $('#engagement_metric_loader').show();
                                    $('#engagement_metric_statistics').hide();
                                    $('#engagement_metric_statistics').html('');

                                    var start_date = $("#start_date").val();
                                    var end_date = $("#end_date").val();
                                    var source = $("#source").val();
                                    var medium = $("#medium").val();
                                    var webtics_pixel_property = $("#webtics_pixel_property").val();

                                    console.log('webtics_pixel_property -- ' + webtics_pixel_property);

                                    $.ajax({
                                        url: 'web360-pixel-report/get-engagement-metric-report?start_date=' + start_date + '&end_date=' + end_date + '&source=' + source + '&medium=' + medium + '&webtics_pixel_property=' + webtics_pixel_property
                                    }).done(function (data) {

                                        $('#engagement_metric_loader').hide();
                                        $('#engagement_metric_statistics').show();
                                        $('#engagement_metric_statistics').html(data);

                                        $(document).foundation('equalizer', 'reflow');

                                    });
                                }

                            </script>
                    </div>
                </div>
            </div>
        </div>

        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                      <h1>
                            ROI
                        </h1>
                </div>
                <div class="panel-content">
                    <div class="row expanded">
                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'roi_metric_loader', 'style' => 'display:none')) }}
                            <div id="roi_metric_statistics"></div>
                            <script type="text/javascript">

                                function getROIMetricReport() {
                                    $('#roi_metric_loader').show();
                                    $('#roi_metric_statistics').hide();
                                    $('#roi_metric_statistics').html('');

                                    var medium = $("#medium").val();
                                    var source = $("#source").val();
                                    var start_date = $("#start_date").val();
                                    var end_date = $("#end_date").val();
                                    var webtics_pixel_property = $("#webtics_pixel_property").val();

                                    $.ajax({
                                        url: 'web360-pixel-report/get-roi-metric-report?start_date=' + start_date + '&end_date=' + end_date + '&source=' + source + '&medium=' + medium + '&webtics_pixel_property=' + webtics_pixel_property
                                    }).done(function (data) {

                                        $('#roi_metric_loader').hide();
                                        $('#roi_metric_statistics').show();
                                        $('#roi_metric_statistics').html(data);


                                        $(document).foundation('equalizer', 'reflow');
                                    });
                                }
                            </script>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row expanded">
        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Visits Breakdown by Medium</h1>
                </div>
                <div class="panel-content">
                    <div class="row expanded">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'visits_breakdown_by_medium_loader', 'style' => 'display:none')) }}
                        <div id="visits_breakdown_by_medium_div"></div>
                        <script type="text/javascript">

                            function getVisitsBreakdownbyMediumReport() {

                                $('#visits_breakdown_by_medium_loader').show();
                                $('#visits_breakdown_by_medium_div').hide();
                                $('#visits_breakdown_by_medium_div').html('');

                                var start_date = $("#start_date").val();
                                var end_date = $("#end_date").val();
                                var webtics_pixel_property = $("#webtics_pixel_property").val();

                                google.charts.load('current', {'packages': ['corechart']});

                                $.ajax({
                                    url: 'web360-pixel-report/get-visits-breakdown-by-medium?start_date=' + start_date + '&end_date=' + end_date + '&webtics_pixel_property=' + webtics_pixel_property
                                }).done(function (data) {

                                    var data_obj = JSON.parse(data);


                                    var visits_breakdown_data = data_obj;
                                    var visits_breakdown_array = [];

                                    for (var k in visits_breakdown_data) {
                                        visits_breakdown_array.push([visits_breakdown_data[k]['medium'], visits_breakdown_data[k]['number_of_visits']]);
                                    }

                                    $('#visits_breakdown_by_medium_loader').hide();
                                    $('#visits_breakdown_by_medium_div').show();

                                    var datat = new google.visualization.DataTable();
                                    datat.addColumn('string', 'Topping');
                                    datat.addColumn('number', 'Slices');
                                    datat.addRows(visits_breakdown_array);

                                    var chart = new google.visualization.PieChart(document.getElementById("visits_breakdown_by_medium_div"));
                                    chart.draw(datat);


                                    $(document).foundation('equalizer', 'reflow');

                                });
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>


        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Leads Breakdown by Medium</h1>
                </div>
                <div class="panel-content">
                    <div class="row expanded">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'lead_by_breakdown_loader', 'style' => 'display:none')) }}
                        <div id="lead_by_breakdown_div"></div>
                        <script type="text/javascript">

                            function getLeadBreakdownByLeadRatingReport() {

                                $('#lead_by_breakdown_loader').show();
                                $('#lead_by_breakdown_div').hide();
                               // $('#lead_by_breakdown_div').html('');

                                var start_date = $("#start_date").val();
                                var end_date = $("#end_date").val();
                                var webtics_pixel_property = $("#webtics_pixel_property").val();

                                google.charts.load('current', {'packages': ['corechart']});
                                $.ajax({
                                    url: 'web360-pixel-report/get-leads-breakdown-by-medium?start_date=' + start_date + '&end_date=' + end_date + '&webtics_pixel_property=' + webtics_pixel_property
                                }).done(function (data) {

                                    var data_obj = JSON.parse(data);

                                    if (data_obj != '') {
                                        var leads_breakdown_array = [];

                                        for (var k in data_obj) {
                                            leads_breakdown_array.push([k, data_obj[k]]);
                                        }

                                        $('#lead_by_breakdown_loader').hide();
                                        $('#lead_by_breakdown_div').show();

                                        var datat = new google.visualization.DataTable();
                                        datat.addColumn('string', 'Topping');
                                        datat.addColumn('number', 'Slices');
                                        datat.addRows(leads_breakdown_array);

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
    </div>


    <div class="row expanded">
        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Lead Conversion Rate By Medium</h1>
                </div>
                <div class="panel-content">
                    <div class="row expanded">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'lead_conversion_rate_loader', 'style' => 'display:none')) }}
                        <div id="lead_conversion_rate_by_medium_div"></div>
                        <script type="text/javascript">

                            function showLeadConvertionRateChart() {

                                $('#lead_conversion_rate_loader').show();
                                $('#lead_conversion_rate_by_medium_div').hide();
                                $('#lead_conversion_rate_by_medium_div').html('');

                                var start_date = $("#start_date").val();
                                var end_date = $("#end_date").val();
                                var webtics_pixel_property = $("#webtics_pixel_property").val();

                                google.charts.load('current', {'packages': ['corechart']});

                                $.ajax({
                                    url: 'web360-pixel-report/get-lead-conversion-rate-by-medium?start_date=' + start_date + '&end_date=' + end_date + '&webtics_pixel_property=' + webtics_pixel_property
                                }).done(function (data) {

                                    if (data != '') {
                                        $('#lead_conversion_rate_loader').hide();
                                        $('#lead_conversion_rate_by_medium_div').show();

                                        var leads_conversion_array = [
                                            ["Element", "Density", {role: "style"}]
                                        ];

                                        for (var k in data) {
                                            leads_conversion_array.push([data[k]['medium'], parseInt(data[k]['lead_conversion_rate']), "#0033FF"]);
                                        }

                                        var data_chart = google.visualization.arrayToDataTable(leads_conversion_array);

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
                                            width: 400,
                                            height: 300,
                                            bar: {groupWidth: "90%"},
                                            legend: {position: "none"},
                                            vAxis: {
                                                title: 'Lead Conversion Rate (%) '
                                            }
                                        };

                                        var chart = new google.visualization.ColumnChart(document.getElementById("lead_conversion_rate_by_medium_div"));
                                        chart.draw(view, options);
                                    } else {

                                        $('#lead_conversion_rate_loader').hide();
                                        $('#lead_conversion_rate_by_medium_div').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                        $('#lead_conversion_rate_by_medium_div').show();
                                    }

                                    $(document).foundation('equalizer', 'reflow');

                                });
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>


        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Your Website Leads (Last 5 Weeks)</h1>
                </div>
                <div class="panel-content">
                    <div class="row expanded">
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
                            function getWeeklyLeadVolumeChart() {
                                $('#loader_homepage_widget_website_leads_chart').show();
                                $('#homepage_widget_website_leads_chart').hide();


                                $.ajax({
                                    url: '/event360-leads/ajax/homepage-widget-leads-weekly?lead_source=web360_enquiries'
                                }).done(function (data) {

                                    if (data != '[]') {

                                        $('#loader_homepage_widget_website_leads_chart').hide();
                                        $('#homepage_widget_website_leads_chart').show();

                                        //console.log(data);
                                        var chart_data = $.parseJSON(data);
                                        var chart_data_array = [['Week at', 'Leads', {role: 'style'}]];
                                        for (var i = 1; i <= chart_data.length; i++) {
                                            chart_data_array [i] = [chart_data[i - 1].date, chart_data[i - 1].leads, 'green'];
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
                                            width: 280,
                                            height: 300,
                                            bar: {groupWidth: "90%"},
                                            legend: {position: "none"},
                                        };
                                        var chart = new google.visualization.ColumnChart(document.getElementById("homepage_widget_website_leads_chart"));
                                        chart.draw(view, options);
                                    } else {

                                        $('#loader_homepage_widget_website_leads_chart').hide();
                                        $('#homepage_widget_website_leads_chart').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                                        $('#homepage_widget_website_leads_chart').show();
                                    }

                                    $(document).foundation('equalizer', 'reflow');

                                });

                            }


                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>

        function getChartMetricReport() {

            getEngagementMetricReport();
            getROIMetricReport();
//            showLatencyComparisonChart();
            showLeadConvertionRateChart();
            getVisitsBreakdownbyMediumReport();
            getLeadBreakdownByLeadRatingReport();
            getWeeklyLeadVolumeChart();
        }


        getChartMetricReport();



        setInterval(function(){ getChartMetricReport(); }, 300000);


    </script>

@stop



