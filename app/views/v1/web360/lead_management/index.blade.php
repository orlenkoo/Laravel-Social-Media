@extends('layouts.v1_layout')

<?php
$can_access_call_tab = false;
if (Employee::projectChecker("event360")) {
    if (is_object($event360_vendor_profile)) {
        if ($event360_vendor_profile->advertiser == 1) {
            $can_access_call_tab = true;
        }
    }
} else {
    $can_access_call_tab = true;
}

?>

@section('content')


    <div class="row expanded">
        <div class="large-12 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>Leads Management</h1>
                </div>
                <div class="panel-content">

                        <ul class="tabs" data-tabs id="tabs_leads_webtics_product">
                            <?php
                            $active_tab = "is-active";
                            ?>
                            @if(Employee::projectChecker("event360"))
                                <li class="tabs-title {{ $active_tab }}"><a href="#panel-event360-enquiries">Event360 Online Leads</a></li>
                                    <?php $active_tab = ""; ?>
                            @endif
                            <li class="tabs-title {{ $active_tab }}"><a href="#panel-website-enquiries">Website Online Leads</a></li>
                            @if($can_access_call_tab)
                                <li class="tabs-title"><a href="#panel-calls-tracking">Calls</a></li>
                            @endif
                            @if(Employee::projectChecker("event360"))
                                <li class="tabs-title"><a href="#panel-event360-messages">Event360 Messages</a></li>
                            @endif
                        </ul>


                        <div class="tabs-content" data-tabs-content="tabs_leads_webtics_product">
                            <?php
                            $active_tab = "is-active";
                            ?>
                            @if(Employee::projectChecker("event360"))

                            <div class="tabs-panel {{ $active_tab }}" id="panel-event360-enquiries">

                                <div class="row expanded">
                                        <div class="large-12 columns">

                                            <h5>Filter By:</h5>
                                        </div>
                                    </div>
                                <div class="row expanded">
                                        <div class="large-3 columns">
                                            {{ Form::label('filter_event360_enquiries_lead_status', 'Lead Status', array('class'=>'control-label')) }}
                                            <?php
                                            $lead_status = Lead::$lead_status;

                                            if (Employee::projectChecker("event360")) {

                                                $lead_status = Lead::$event360_lead_status;
                                            }
                                            ?>
                                            {{ Form::select('filter_event360_enquiries_lead_status', array(null => 'Select') + $lead_status, null, array('id' => 'filter_event360_enquiries_lead_status')) }}
                                        </div>
                                        <div class="large-3 columns">
                                            {{ Form::label('filter_event360_enquiries_lead_rating', 'Lead Rating', array('class'=>'control-label')) }}
                                            {{ Form::select('filter_event360_enquiries_lead_rating', array(null => 'Select') + Lead::$lead_ratings, null, array('id' => 'filter_event360_enquiries_lead_rating')) }}
                                        </div>
                                        <div class="large-3 columns">
                                            {{ Form::label('filter_event360_enquiries_lead_from_date', 'From Date', array('class'=>'control-label')) }}
                                            <div class="controls">{{ Form::text('filter_event360_enquiries_lead_from_date', $from_date, array('class' => 'datepicker', 'id' => 'filter_event360_enquiries_lead_from_date')) }}</div>
                                        </div>
                                        <div class="large-3 columns">
                                            {{ Form::label('filter_event360_enquiries_lead_to_date', 'To Date', array('class'=>'control-label')) }}
                                            <div class="controls">{{ Form::text('filter_event360_enquiries_lead_to_date', $to_date, array('class' => 'datepicker', 'id' => 'filter_event360_enquiries_lead_to_date')) }}</div>
                                        </div>
                                    </div>
                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        <a class="button tiny delete-button" onclick="clearFilter()">Clear Filter</a>
                                        <a class="button tiny success" onclick="getAjaxEvent360EnquiryLeadsList(1)">Search</a>
                                    </div>
                                    <div class="large-4 columns">

                                    </div>
                                    <div class="large-4 columns">

                                    </div>
                                </div>
                                    <hr>

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

                                            var filter_event360_enquiries_lead_status = $('#filter_event360_enquiries_lead_status').val();
                                            var filter_event360_enquiries_lead_rating = $('#filter_event360_enquiries_lead_rating').val();
                                            var filter_event360_enquiries_lead_from_date = $('#filter_event360_enquiries_lead_from_date').val();
                                            var filter_event360_enquiries_lead_to_date = $('#filter_event360_enquiries_lead_to_date').val();

                                            $.ajax({
                                                url: '/event360-leads/ajax/get/leads?' +
                                                'page=' + page +
                                                '&lead_source=event360_enquiries' +
                                                '&filter_event360_enquiries_lead_status=' + filter_event360_enquiries_lead_status +
                                                '&filter_event360_enquiries_lead_rating=' + filter_event360_enquiries_lead_rating +
                                                '&filter_event360_enquiries_lead_from_date=' + filter_event360_enquiries_lead_from_date +
                                                '&filter_event360_enquiries_lead_to_date=' + filter_event360_enquiries_lead_to_date
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

                            </div>
                                    <?php $active_tab = ""; ?>

                                @endif



                                <div class="tabs-panel {{ $active_tab }}" id="panel-website-enquiries">
                                    <div class="row expanded">
                                        <div class="large-12 columns">
                                            <h5>Filter By:</h5>
                                        </div>
                                    </div>
                                    <div class="row expanded">
                                        <div class="large-4 columns">
                                            {{ Form::label('filter_web360_enquiries_lead_rating', 'Lead Rating', array('class'=>'control-label')) }}
                                            {{ Form::select('filter_web360_enquiries_lead_rating', array(null => 'Select') + Lead::$lead_ratings, null, array('id' => 'filter_web360_enquiries_lead_rating')) }}
                                        </div>
                                        <div class="large-4 columns">
                                            {{ Form::label('filter_web360_enquiries_lead_from_date', 'From Date', array('class'=>'control-label')) }}
                                            <div class="controls">{{ Form::text('filter_web360_enquiries_lead_from_date', $from_date, array('class' => 'datepicker', 'id' => 'filter_web360_enquiries_lead_from_date')) }}</div>
                                        </div>
                                        <div class="large-4 columns">
                                            {{ Form::label('filter_web360_enquiries_lead_to_date', 'To Date', array('class'=>'control-label')) }}
                                            <div class="controls">{{ Form::text('filter_web360_enquiries_lead_to_date', $to_date, array('class' => 'datepicker', 'id' => 'filter_web360_enquiries_lead_to_date')) }}</div>
                                        </div>
                                    </div>
                                    <div class="row expanded">
                                        <div class="large-4 columns">
                                            <a class="button tiny delete-button" onclick="clearFilterWeb360()">Clear Filter</a>
                                            <a class="button tiny success" onclick="getAjaxWeb360EnquiryLeadsList(1)">Search</a>
                                        </div>
                                        <div class="large-4 columns">

                                        </div>
                                        <div class="large-4 columns">

                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row expanded">
                                        <div class="large-12 columns">
                                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_leads_web360_enquiries_list', 'style' => 'display:none')) }}

                                            <div id="leads_web360_enquiries_list">

                                            </div>
                                        </div>
                                    </div>

                                    <script>


                                        /*==================== PAGINATION =========================*/

                                        $(document).on('click', '#web360_pagination_leads_list a', function (e) {
                                            e.preventDefault();
                                            var page = $(this).attr('href').split('page=')[1];
                                            //location.hash = page;
                                            getAjaxWeb360EnquiryLeadsList(page);
                                        });


                                        function getAjaxWeb360EnquiryLeadsList(page) {


                                            $('#loader_leads_web360_enquiries_list').show();
                                            $('#leads_web360_enquiries_list').hide();
                                            $('#leads_web360_enquiries_list').html('');

                                            //var filter_web360_enquiries_lead_status = $('#filter_web360_enquiries_lead_status').val();
                                            var filter_web360_enquiries_lead_rating = $('#filter_web360_enquiries_lead_rating').val();
                                            var filter_web360_enquiries_lead_from_date = $('#filter_web360_enquiries_lead_from_date').val();
                                            var filter_web360_enquiries_lead_to_date = $('#filter_web360_enquiries_lead_to_date').val();

                                            $.ajax({
                                                url: '/event360-leads/ajax/get/leads?' +
                                                'page=' + page +
                                                '&lead_source=web360_enquiries' +
                                                //'&filter_web360_enquiries_lead_status=' + filter_web360_enquiries_lead_status +
                                                '&filter_web360_enquiries_lead_rating=' + filter_web360_enquiries_lead_rating +
                                                '&filter_web360_enquiries_lead_from_date=' + filter_web360_enquiries_lead_from_date +
                                                '&filter_web360_enquiries_lead_to_date=' + filter_web360_enquiries_lead_to_date
                                            }).done(function (data) {
                                                $('#leads_web360_enquiries_list').html(data);
                                                $('#loader_leads_web360_enquiries_list').hide();
                                                $('#leads_web360_enquiries_list').show();
                                                $("#lead_details").tablesorter();
                                                $('.datepicker').datetimepicker({
                                                    format: 'Y-m-d',
                                                    lang: 'en',
                                                    scrollInput: false
                                                });
                                                $(document).foundation();
                                            });
                                        }

                                        getAjaxWeb360EnquiryLeadsList(1);
                                    </script>

                                </div> <!--end panel-website-enquiries-->


                                @if($can_access_call_tab)
                                    <div class="tabs-panel" id="panel-calls-tracking">
                                        <div class="row expanded">
                                            <div class="large-12 columns">
                                                <h5>Filter By:</h5>
                                            </div>
                                        </div>
                                        <div class="row expanded">
                                            <div class="large-3 columns">
                                                {{ Form::label('filter_call_tracking_lead_source', 'Lead Source', array('class'=>'control-label')) }}
                                                <div class="controls">{{ Form::text('filter_call_tracking_lead_source', '', array('class' => '', 'id' => 'filter_call_tracking_lead_source')) }}</div>
                                            </div>
                                            <div class="large-3 columns">
                                                {{ Form::label('filter_call_tracking_lead_rating', 'Lead Rating', array('class'=>'control-label')) }}
                                                {{ Form::select('filter_call_tracking_lead_rating', array(null => 'Select') + Lead::$lead_ratings, null, array('id' => 'filter_call_tracking_lead_rating')) }}
                                            </div>
                                            <div class="large-3 columns">
                                                {{ Form::label('filter_call_tracking_from_date', 'From Date', array('class'=>'control-label')) }}
                                                <div class="controls">{{ Form::text('filter_call_tracking_from_date', $from_date, array('class' => 'datepicker', 'id' => 'filter_call_tracking_from_date')) }}</div>
                                            </div>
                                            <div class="large-3 columns">
                                                {{ Form::label('filter_call_tracking_to_date', 'To Date', array('class'=>'control-label')) }}
                                                <div class="controls">{{ Form::text('filter_call_tracking_to_date', $to_date, array('class' => 'datepicker', 'id' => 'filter_call_tracking_to_date')) }}</div>
                                            </div>
                                        </div>
                                        <div class="row expanded">
                                            <div class="large-4 columns">
                                                <a class="button tiny delete-button" onclick="clearFilterCallTracking()">Clear Filter</a>
                                                <a class="button tiny success" onclick="getAjaxCallTrackingLeadsList(1)">Search</a>
                                            </div>
                                            <div class="large-4 columns">

                                            </div>
                                            <div class="large-4 columns">

                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row expanded">
                                            <div class="large-12 columns">
                                                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_delacon_call_tracking_report', 'style' => 'display:none')) }}

                                                <div id="delacon_call_tracking_report">

                                                </div>
                                            </div>
                                        </div>

                                        <script>


                                            function getAjaxCallTrackingLeadsList(page) {


                                                $('#loader_delacon_call_tracking_report').show();
                                                $('#delacon_call_tracking_report').hide();
                                                $('#delacon_call_tracking_report').html('');

                                                var filter_call_tracking_lead_source = $('#filter_call_tracking_lead_source').val();
                                                var filter_call_tracking_lead_rating = $('#filter_call_tracking_lead_rating').val();
                                                var filter_call_tracking_from_date = $('#filter_call_tracking_from_date').val();
                                                var filter_call_tracking_to_date = $('#filter_call_tracking_to_date').val();


                                                $.ajax({
                                                    url: '/third-party-api/delacon/report?' +
                                                    'page=' + page +
                                                    '&filter_call_tracking_lead_source=' + filter_call_tracking_lead_source +
                                                    '&filter_call_tracking_lead_rating=' + filter_call_tracking_lead_rating +
                                                    '&filter_call_tracking_from_date=' + filter_call_tracking_from_date +
                                                    '&filter_call_tracking_to_date=' + filter_call_tracking_to_date
                                                }).done(function (data) {
                                                    $('#delacon_call_tracking_report').html(data);
                                                    $('#loader_delacon_call_tracking_report').hide();
                                                    $('#delacon_call_tracking_report').show();
                                                    $(document).foundation();
                                                });
                                            }

                                            getAjaxCallTrackingLeadsList(1);
                                        </script>

                                    </div> <!--end panel-calls-tracking-->
                                @endif


                                @if(Employee::projectChecker("event360"))
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
                                    </div> <!--end panel-event360-messages-->
                                @endif
                        </div>


                </div>
            </div>
        </div>

    </div>



    <script>
        function refreshAndCallFunctions() {
            @if(Employee::projectChecker("event360"))
                getAjaxEvent360EnquiryLeadsList(1);
            getAjaxEvent360MessengerThreadLeadsList(1);
            @endif

            getAjaxWeb360EnquiryLeadsList(1);

            @if($can_access_call_tab)
                getAjaxCallTrackingLeadsList(1);
            @endif

        }
        setInterval(function () {
            refreshAndCallFunctions();
        }, 300000);
    </script>

    @include('v1.web360.lead_management._partials.leads_enquiries_javascript_functions')


@stop