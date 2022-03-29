@extends('layouts.default')

@section('content')

    <div class="row page-title-bar">
        <div class="large-12 columns">
            <h1>Quick Access</h1>
        </div>
    </div>

    <div class="row">
        <div class="large-3 columns">
            <div class="panel">
                <h5>
                    Your Website Leads (Last 5 Weeks)
                </h5>
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

                            var equal_demo = '';
                            if(equal_demo == '') {

                                $('#loader_homepage_widget_website_leads_chart').hide();
                                $('#homepage_widget_website_leads_chart').show();

                                //console.log(data);
                                var chart_data = $.parseJSON(data);
                                var chart_data_array = [['Week at', 'Leads',{ role: 'style' }]];

                                    chart_data_array [1] = ['21-10-2016', 3, 'green'];
                                    chart_data_array [2] = ['28-10-2016', 4, 'green'];
                                    chart_data_array [3] = ['04-11-2016', 2, 'green'];
                                    chart_data_array [4] = ['11-11-2016', 5, 'green'];


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
        <div class="large-3 columns">
            <div class="panel">
                <h5>
                    Event360 Website Leads (Last 5 Weeks)
                </h5>
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


                            var equal_demo = '';
                            if(equal_demo == '') {


                                $('#loader_homepage_widget_event360_leads_chart').hide();
                                $('#homepage_widget_event360_leads_chart').show();

                                console.log(data);
                                var chart_data = $.parseJSON(data);
                                var chart_data_array = [['Week at', 'Leads', {role: 'style'}]];

                                chart_data_array [1] = ['21-10-2016', 4, 'blue'];
                                chart_data_array [2] = ['28-10-2016', 2, 'blue'];
                                chart_data_array [3] = ['04-11-2016', 3, 'blue'];
                                chart_data_array [4] = ['11-11-2016', 4, 'blue'];

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
                                    width: 280,
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
        <div class="large-3 columns">
            <div class="panel">
                <h5>
                    Message Leads (Last 5 Weeks)
                </h5>
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

                            var equal_demo = '';
                            if(equal_demo == '') {
                                $('#loader_homepage_widget_message_leads_chart').hide();
                                $('#homepage_widget_message_leads_chart').show();

                                console.log(data);
                                var chart_data = $.parseJSON(data);
                                var chart_data_array = [['Week at', 'Leads', {role: 'style'}]];
                                chart_data_array [1] = ['21-10-2016', 2, 'orange'];
                                chart_data_array [2] = ['28-10-2016', 0, 'orange'];
                                chart_data_array [3] = ['04-11-2016', 1, 'orange'];
                                chart_data_array [4] = ['11-11-2016', 2, 'orange'];

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
                                    width: 280,
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

        <div class="large-3 columns">

            <div class="panel">
                <h5>
                    Event360 Live Lead Alert
                </h5>
                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_non_advertising_event360_leads_table', 'style' => 'display:none')) }}

                <div id="non_advertising_event360_leads_table">

                </div>
                <script>

                    function getAjaxNonAdvertisingEvent360Leads() {

                        $('#loader_non_advertising_event360_leads_table').show();
                        $('#non_advertising_event360_leads_table').hide();
                        $('#non_advertising_event360_leads_table').html('');

                        $.ajax({
                            url: '/event360-leads/ajax/get/non-advertising-event360-leads-widget?'

                        }).done(function (data) {
                            $('#non_advertising_event360_leads_table').html(data);
                            $('#loader_non_advertising_event360_leads_table').hide();
                            $('#non_advertising_event360_leads_table').show();
                            $("#non_advertising_event360_leads_details").tablesorter();
                            $('.datepicker').datetimepicker({
                                format: 'Y-m-d',
                                lang: 'en',
                                scrollInput: false
                            });
                            $(document).foundation();
                        });
                    }
                    getAjaxNonAdvertisingEvent360Leads();

                </script>
            </div>

        </div>

    </div>



    <div class="row">
        <div class="large-12 columns">
            <ul class="tabs" data-tab>
                <li class="tab-title active"><a href="#panel-event360-enquiries">Event360 Enquiry Leads</a></li>
                <li class="tab-title"><a href="#panel-event360-messages">Messages</a></li>
            </ul>
            <div class="tabs-content">
                <div class="content active" id="panel-event360-enquiries">


                    <div class="row">
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
                </div><!--end panel-event360-enquiries-->






                <div class="content" id="panel-event360-messages">



                    <div class="row">
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
            </div>

            <script>
                function updateAjaxLeadRating(lead_id, lead_rating) {
                    var r = confirm("Would you like to Update this Lead Rating to "+lead_rating+"?");
                    if (r == true) {
                        $.post("/event360-leads/ajax/update-rating/", {lead_id: lead_id, lead_rating: lead_rating}, function (data) {
                            $.notify('Updated Successfully.');
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
                            $.notify('Updated Successfully.');
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

@stop