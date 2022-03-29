@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-2 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h5>My Activities</h5>
                </div>
                <div class="panel-content">
                    <ul class="vertical tabs" data-tabs id="example-tabs">
                        <li class="tabs-title is-active"><a href="#panel_calls" aria-selected="true" onclick="ajaxLoadCallsList(1)">Calls</a></li>
                        <li class="tabs-title"><a href="#panel_meetings" onclick="ajaxLoadMeetingsList(1)">Meetings</a></li>
                        <li class="tabs-title"><a href="#panel_emails" >Emails</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="large-10 columns">
            <div class="panel">
                <div class="row expanded collapse">
                    <div data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel_calls">
                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <h1>Calls</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-content">
                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        {{ Form::text('search_query_calls_filter', null, array('placeholder' => 'Search by Agenda and Summary', 'id' => 'search_query_calls_filter', 'onchange' => 'loadDashboardData()')) }}
                                    </div>
                                    <div class="large-4 columns">

                                    </div>
                                    <div class="large-4 columns">
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_calls_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                                        <div id="response_calls_list">

                                        </div><!--end response_calls_list-->
                                        <script>

                                            /*==================== PAGINATION =========================*/

                                            $(document).on('click', '#pagination_calls_list a', function (e) {
                                                e.preventDefault();
                                                var page = $(this).attr('href').split('page=')[1];
                                                ajaxLoadCallsList(page);
                                            });

                                            function ajaxLoadCallsList(page) {
                                                $('#loader_calls_list').show();
                                                $('#response_calls_list').hide();

                                                var search_query_calls_filter = $("#search_query_calls_filter").val();
                                                var dashboard_filter_date_range = $('#dashboard_filter_date_range').val();
                                                var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                                                var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                                                $.ajax({
                                                    url: '/my-activities/ajax/load-calls-list?' +
                                                    'page=' + page
                                                    + '&search_query_calls_filter=' + search_query_calls_filter +
                                                    '&dashboard_filter_date_range=' + dashboard_filter_date_range +
                                                    '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                                                    '&dashboard_filter_to_date=' + dashboard_filter_to_date


                                                }).done(function (data) {
                                                    $('#response_calls_list').html(data);
                                                    $('#loader_calls_list').hide();
                                                    $('#response_calls_list').show();
                                                    $('.datepicker').datetimepicker({
                                                        timepicker: false,
                                                        format: 'Y-m-d',
                                                        lang: 'en',
                                                        scrollInput: false
                                                    });
                                                    $('.timepicker').datetimepicker({
                                                        datepicker: false,
                                                        format: 'g:i A',
                                                        formatTime: 'g:i A',
                                                        lang: 'en',
                                                        scrollInput: false
                                                    });
                                                    $(document).foundation();
                                                });
                                            }



                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div><!--end panel_calls-->

                        <div class="tabs-panel" id="panel_meetings">
                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <h1>Meetings</h1>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-content">
                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        {{ Form::text('search_query_meetings_filter', null, array('placeholder' => 'Search by Agenda/Meeting With', 'id' => 'search_query_meetings_filter', 'onchange' => 'loadDashboardData()')) }}
                                    </div>

                                    <div class="large-4 columns">
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_meetings_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                                        <div id="response_meetings_list">

                                        </div><!--end response_meetings_list-->
                                        <script>

                                            /*==================== PAGINATION =========================*/

                                            $(document).on('click', '#pagination_meetings_list a', function (e) {
                                                e.preventDefault();
                                                var page = $(this).attr('href').split('page=')[1];
                                                ajaxLoadMeetingsList(page);
                                            });

                                            function ajaxLoadMeetingsList(page) {
                                                $('#loader_meetings_list').show();
                                                $('#response_meetings_list').hide();

                                                var search_query_meetings_filter = $("#search_query_meetings_filter").val();
                                                var dashboard_filter_date_range = $('#dashboard_filter_date_range').val();
                                                var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                                                var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                                                $.ajax({
                                                    url: '/my-activities/ajax/load-meetings-list?' +
                                                    'page=' + page +
                                                    '&search_query_meetings_filter=' + search_query_meetings_filter +
                                                    '&dashboard_filter_date_range=' + dashboard_filter_date_range +
                                                    '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                                                    '&dashboard_filter_to_date=' + dashboard_filter_to_date
                                                }).done(function (data) {
                                                    $('#response_meetings_list').html(data);
                                                    $('#loader_meetings_list').hide();
                                                    $('#response_meetings_list').show();
                                                    $('.datepicker').datetimepicker({
                                                        timepicker: false,
                                                        format: 'Y-m-d',
                                                        lang: 'en',
                                                        scrollInput: false
                                                    });
                                                    $('.timepicker').datetimepicker({
                                                        datepicker: false,
                                                        format: 'g:i A',
                                                        formatTime: 'g:i A',
                                                        lang: 'en',
                                                        scrollInput: false
                                                    });
                                                    $(document).foundation();
                                                });
                                            }




                                        </script>
                                    </div>
                                </div>
                            </div>

                        </div><!--end panel_meetings-->

                        <div class="tabs-panel" id="panel_emails">
                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <h1>Emails</h1>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-content">
                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        {{ Form::text('search_query_emails_filter', null, array('placeholder' => 'Search by To/CC/BCC/Subject/Body', 'id' => 'search_query_emails_filter', 'onchange' => 'loadDashboardData()')) }}
                                    </div>
                                    <div class="large-4 columns">
                                        {{Form::select('status_filter',array(''=>'Select Status') + Email::$status , '',array('id' => 'status_filter', 'onchange' => 'loadDashboardData()'))}}
                                    </div>
                                    <div class="large-4 columns">
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_emails_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                                        <div id="response_emails_list">

                                        </div><!--end response_emails_list-->
                                        <script>

                                            function loadDashboardData() {
                                                ajaxLoadCallsList(1);
                                                ajaxLoadMeetingsList(1);
                                                ajaxLoadEmailsList(1);
                                            }

                                            loadDashboardData();
                                            /*==================== PAGINATION =========================*/

                                            $(document).on('click', '#pagination_emails_list a', function (e) {
                                                e.preventDefault();
                                                var page = $(this).attr('href').split('page=')[1];
                                                ajaxLoadEmailsList(page);
                                            });

                                            function ajaxLoadEmailsList(page) {
                                                $('#loader_emails_list').show();
                                                $('#response_emails_list').hide();

                                                var search_query_emails_filter = $("#search_query_emails_filter").val();
                                                var status_filter = $("#status_filter").val();
                                                var dashboard_filter_date_range = $('#dashboard_filter_date_range').val();
                                                var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                                                var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                                                $.ajax({
                                                    url: '/my-activities/ajax/load-emails-list?' +
                                                    'page=' + page +
                                                    '&search_query_emails_filter=' + search_query_emails_filter +
                                                    '&status_filter=' + status_filter +
                                                    '&dashboard_filter_date_range=' + dashboard_filter_date_range +
                                                    '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                                                    '&dashboard_filter_to_date=' + dashboard_filter_to_date
                                                }).done(function (data) {
                                                    $('#response_emails_list').html(data);
                                                    $('#loader_emails_list').hide();
                                                    $('#response_emails_list').show();
                                                    $(document).foundation();
                                                });
                                            }

                                        </script>
                                    </div>
                                </div>
                            </div>

                        </div><!--end panel_emails -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('my_activities._partials.edit_activity_popup_forms', ['post_data_to_load' => 'my_activities'])



@stop