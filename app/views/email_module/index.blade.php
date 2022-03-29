@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Search by Campaign Name:
                                {{ Form::text('search_query', null, array('placeholder' => 'Search By Campaign Name', 'id' => 'search_query')) }}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Status:
                                {{ Form::select('filter_status', ['' => 'Select'] + EmailModuleEmailCampaign::$status, '', array('id' => 'filter_status')) }}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-6 columns text-right">
                            <input type="button" value="Clear Filters" class="alert button tiny" style="width: 100%;" onclick="clearFilters()">
                        </div>
                        <div class="large-6 columns text-right">
                            <input type="button" class="button tiny success" value="Search" style="margin-top: 0px; width: 100%;" onclick="loadDashboardData()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="large-9 columns">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row expanded">
                        <div class="large-6 columns">
                            <h1>Email Campaigns</h1>
                        </div>
                        <div class="large-6 columns">
                            <input type="button" value="Add New Campaign" class="button tiny float-right" data-open="popup_add_new_email_campaign_form">
                            <div class="reveal" id="popup_add_new_email_campaign_form" data-reveal>
                                @include('email_module._partials.add_new_email_campaign_form')
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_email_campaigns', 'class' => 'float-center', 'style' => 'display:none')) }}
                    <div id="response_email_campaigns">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        /*==================== PAGINATION =========================*/

        $(document).on('click', '#pagination_email_campaigns_list a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            //location.hash = page;
            ajaxLoadEmailCampaignsList(page);
        });

        function ajaxLoadEmailCampaignsList(page) {
            $('#loader_email_campaigns').show();
            $('#response_email_campaigns').hide();

            var search_query = $("#search_query").val();

            $.ajax({
                url: '/email-module/ajax/load-email-campaigns-list?' +
                'page=' + page +
                '&search_query=' + search_query

            }).done(function (data) {
                $('#response_email_campaigns').html(data);
                $('#loader_email_campaigns').hide();
                $('#response_email_campaigns').show();
                $('.datetimepicker').datetimepicker({
                    datepicker: true,
                    format: 'Y-m-d H:i',
                    step : 30,
                    scrollInput : false
                });
            });
        }

        function clearFilters() {
            
        }
        
        function loadDashboardData() {
            ajaxLoadEmailCampaignsList(1);
        }

        loadDashboardData();

    </script>
@stop