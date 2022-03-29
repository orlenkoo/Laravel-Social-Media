@extends('layouts.v1_layout')

@section('content')

    <div class="row expanded">
        <div class="large-12 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Quick Access</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row expanded">
        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h4>
                        Your Website Leads (Last 5 Weeks)
                    </h4>
                </div>
                <div class="panel-content">
                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_homepage_widget_website_leads_chart', 'style' => 'display:none')) }}
                <!--Div that will hold the pie chart-->
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

                        homePageWidgetWebsiteLeadsChart();
                    </script>
                </div>
            </div>
        </div>
        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h4>Call Leads (Last 5 Weeks)</h4>
                </div>
                <div class="panel-content">
                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_homepage_widget_call_leads_chart', 'style' => 'display:none')) }}
                <!--Div that will hold the pie chart-->
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

                        homePageWidgetCallLeadsChart();
                    </script>
                </div>
            </div>
        </div>
    </div>


    @if(Employee::projectChecker("event360"))
    <div class="row expanded">
        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h4>Event360 Online Leads (Last 5 Weeks)</h4>
                </div>
                <div class="panel-content">
                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_homepage_widget_event360_leads_chart', 'style' => 'display:none')) }}
                <!--Div that will hold the pie chart-->
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

                        homePageWidgetEvent360LeadsChart();
                    </script>
                </div>
            </div>
        </div>
        <div class="large-6 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h4>Event360 Message Leads (Last 5 Weeks)</h4>
                </div>
                <div class="panel-content">
                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_homepage_widget_message_leads_chart', 'style' => 'display:none')) }}
                <!--Div that will hold the pie chart-->
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

                        homePageWidgetMessageLeadsChart();
                    </script>
                </div>
            </div>
        </div>
    </div>



    <div class="row expanded">
        <div class="large-12 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h4>Pending Leads / Messages</h4>
                </div>
                <div class="panel-content">
                    <ul class="tabs" data-tabs id="tabs_quick_access">
                        <li class="tabs-title is-active"><a href="#panel-event360-enquiries">Event360 Online Leads</a></li>
                        <li class="tabs-title"><a href="#panel-event360-messages">Event360 Messages</a></li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="tabs_quick_access">
                        <div class="tabs-panel is-active" id="panel-event360-enquiries">
                            <div class="row expanded">
                                <div class="large-12 columns">

                                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_leads_event360_enquiries_list', 'style' => 'display:none')) }}

                                    <div id="leads_event360_enquiries_list">

                                    </div>

                                </div>
                            </div>

                            <script>


                                /*==================== PAGINATION =========================*/

                                $(document).on('click', '#pagination_leads_list a', function (e) {
                                    e.preventDefault();
                                    var page = $(this).attr('href').split('page=')[1];
                                    //location.hash = page;
                                    getAjaxEvent360EnquiryLeadsList(page);
                                });


                                function getAjaxEvent360EnquiryLeadsList(page) {


                                    $('#loader_leads_event360_enquiries_list').show();
                                    $('#leads_event360_enquiries_list').hide();
                                    $('#leads_event360_enquiries_list').html('');


                                    $.ajax({
                                        url: '/event360-leads/ajax/get/leads?' +
                                        'page=' + page +
                                        '&lead_source=event360_enquiries' +
                                        '&filter_event360_enquiries_lead_status=Pending'

                                    }).done(function (data) {
                                        $('#leads_event360_enquiries_list').html(data);
                                        $('#loader_leads_event360_enquiries_list').hide();
                                        $('#leads_event360_enquiries_list').show();
                                        $("#lead_details").tablesorter();
                                        $('.datepicker').datetimepicker({
                                            format: 'Y-m-d',
                                            lang: 'en',
                                            scrollInput: false
                                        });
                                        $(document).foundation();
                                    });
                                }

                                getAjaxEvent360EnquiryLeadsList(1);
                            </script>
                        </div> <!--end panel-event360-enquiries-->

                        <div class="tabs-panel" id="panel-event360-messages">
                            <div class="row expanded">
                                <div class="large-12 columns">

                                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_leads_event360_messenger_threads_list', 'style' => 'display:none')) }}

                                    <div id="leads_event360_messenger_threads_list">

                                    </div>

                                </div>
                            </div>

                            <script>


                                /*==================== PAGINATION =========================*/

                                $(document).on('click', '#pagination_leads_list a', function (e) {
                                    e.preventDefault();
                                    var page = $(this).attr('href').split('page=')[1];
                                    //location.hash = page;
                                    getAjaxEvent360MessengerThreadLeadsList(page);
                                });


                                function getAjaxEvent360MessengerThreadLeadsList(page) {



                                    $('#loader_leads_event360_messenger_threads_list').show();
                                    $('#leads_event360_messenger_threads_list').hide();
                                    $('#leads_event360_messenger_threads_list').html('');


                                    $.ajax({
                                        url: '/event360-leads/ajax/get/leads?' +
                                        'page=' + page +
                                        '&lead_source=event360_messenger_threads'

                                    }).done(function (data) {
                                        $('#leads_event360_messenger_threads_list').html(data);
                                        $('#loader_leads_event360_messenger_threads_list').hide();
                                        $('#leads_event360_messenger_threads_list').show();
                                        $("#lead_details").tablesorter();
                                        $('.datepicker').datetimepicker({
                                            format: 'Y-m-d',
                                            lang: 'en',
                                            scrollInput: false
                                        });
                                        $(document).foundation();
                                    });
                                }

                                getAjaxEvent360MessengerThreadLeadsList(1);
                            </script>
                        </div><!--end panel-event360-messages-->


                        <script>
                            function refreshAndCallFunctions() {
                                homePageWidgetWebsiteLeadsChart();
                                homePageWidgetEvent360LeadsChart();
                                homePageWidgetMessageLeadsChart();
                                homePageWidgetCallLeadsChart();
                                getAjaxEvent360EnquiryLeadsList(1);
                                getAjaxEvent360MessengerThreadLeadsList(1);
                            }
                            setInterval(function(){ refreshAndCallFunctions(); }, 300000);
                        </script>

                        <script>
                            function updateAjaxLeadRating(lead_id, lead_rating) {
                                var r = confirm("Would you like to Update this Lead Rating to "+lead_rating+"?");
                                if (r == true) {
                                    $.post("/event360-leads/ajax/update-rating/", {lead_id: lead_id, lead_rating: lead_rating}, function (data) {
                                        $.notify('Updated Successfully.', "success");
                                        //alert(data);
                                        //getAjaxWeb360EnquiryLeadsList(1);
                                    });
                                } else {
                                    return false;
                                }
                            }

                            function updateAjaxLeadStatus(lead_id, lead_source) {
                                var r = confirm("Would you like to Accept this Lead?");
                                if (r == true) {
                                    $.post("/event360-leads/ajax/update-status/", {lead_id: lead_id}, function (data) {
                                        $.notify('Updated Successfully.', "success");
                                        //alert(data);
                                        if(lead_source == 'event360_enquiries') {
                                            getAjaxEvent360EnquiryLeadsList(1);
                                        } else if(lead_source == 'web360_enquiries') {
                                            getAjaxWeb360EnquiryLeadsList(1);
                                        } else if(lead_source == 'event360_messenger_threads') {
                                            getAjaxEvent360MessengerThreadLeadsList(1);
                                        }



                                    });
                                } else {
                                    return false;
                                }
                            }

                            function clearFilter() {
                                $('#filter_enquiries_lead_status').val('');
                                $('#filter_enquiries_lead_rating').val('');
                                $('#filter_enquiries_lead_from_date').val('');
                                $('#filter_enquiries_lead_to_date').val('');


                                // get the customer list after clearing filter
                                getAjaxEvent360EnquiryLeadsList(1);
                            }

                            function clearFilterWeb360() {
                                $('#filter_web360_enquiries_lead_status').val('');
                                $('#filter_web360_enquiries_lead_rating').val('');
                                $('#filter_web360_enquiries_lead_from_date').val('');
                                $('#filter_web360_enquiries_lead_to_date').val('');


                                // get the customer list after clearing filter
                                getAjaxWeb360EnquiryLeadsList(1);
                            }

                            function clearFilterEvent360MessengerThread() {
                                $('#filter_event360_messenger_threads_lead_status').val('');
                                $('#filter_event360_messenger_threads_lead_rating').val('');
                                $('#filter_event360_messenger_threads_lead_from_date').val('');
                                $('#filter_event360_messenger_threads_lead_to_date').val('');


                                // get the customer list after clearing filter
                                getAjaxEvent360MessengerThreadLeadsList(1);
                            }



                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif



@stop