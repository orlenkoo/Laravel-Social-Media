@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="search_query">
                                Search For
                                {{ Form::text('search_query', null, array('placeholder' => 'Search by Campaign Name or Content', 'id' => 'search_query')) }}
                            </label>
                        </div>
                        <div class="large-12 columns">
                            <label for="status_filter">
                                Status
                                {{Form::select('status_filter',array(''=>'Select Status') +  Campaign::$campaign_status , '',array('id' => 'status_filter'))}}
                            </label>
                        </div>
                        <div class="large-12 columns">
                            <label for="date_filter">
                                Date
                                {{Form::select('date_filter',array(
                                                                    ''=>'Select Date',
                                                                    'Start Date'=>'Start Date',
                                                                    'End Date'=>'End Date',
                                                                    ), '',array('id' => 'date_filter'))}}
                            </label>
                        </div>

                    </div>
                    <div class="row">
                        <div class="large-6 columns text-right">
                            <input type="button" value="Clear Filter" class="alert button tiny" style="width: 100%;" onclick="clearLeadsFilter()">
                        </div>
                        <div class="large-6 columns text-right">
                            <input type="button" class="button tiny success" value="Search" style="margin-top: 0px; width: 100%;" onclick="loadDashboardData()">
                        </div>
                    </div>

                    <script>
                        function clearLeadsFilter() {
                            $('#search_query').val('');
                            loadDashboardData();
                        }
                    </script>
                </div>
            </div>
        </div>
        <div class="large-9 columns">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row expanded">
                        <div class="large-6 columns">
                            <h1>Campaigns</h1>
                        </div>
                        <div class="large-6 columns">
                            <button class="button tiny float-right" type="button" data-open="reveal_add_new_campaign">Add New Campaign</button>
                            <div class="reveal panel large reveal_add_new_campaign" id="reveal_add_new_campaign" name="reveal_add_new_campaign" data-reveal>
                                @include('campaigns._partials.add_new_campaign_form')
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_campaigns_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                    <div id="response_campaign_list_div">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function loadDashboardData() {
            ajaxLoadCampaignsList(1);
        }

        /*==================== PAGINATION =========================*/

        $(document).on('click', '#pagination_campaigns_list a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            //location.hash = page;
            ajaxLoadCampaignsList(page);
        });

        function ajaxLoadCampaignsList(page) {
            $('#loader_campaigns_list').show();
            $('#response_campaign_list_div').hide();

            var search_query = $("#search_query").val();
            var date_filter = $("#date_filter").val();
            var status_filter = $("#status_filter").val();

            $.ajax({
                url: '/campaigns/ajax/load-campaigns-list?' +
                'page=' + page +
                '&search_query=' + search_query +
                '&date_filter=' + date_filter +
                '&status_filter=' + status_filter

            }).done(function (data) {
                $('#response_campaign_list_div').html(data);
                $('#loader_campaigns_list').hide();
                $('#response_campaign_list_div').show();
                $(document).foundation();
                $('.datepicker').datetimepicker({
                    timepicker: false,
                    format: 'Y-m-d',
                    lang: 'en',
                    scrollInput: false
                });
                $('.campaign-media-channels-selectize').selectize({
                    create: false,
                    sortField: 'text'
                });
            });
        }

        loadDashboardData();

    </script>

@stop