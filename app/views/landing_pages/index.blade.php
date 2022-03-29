@extends('layouts.default')

@section('content')

    <div class="row expanded">

        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Search By Page Name or URL:
                                {{ Form::text('search_query', null, array('placeholder' => 'Search By Page Name or URL', 'id' => 'search_query')) }}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Status:
                                {{Form::select('status_filter',array(''=>'Select Status') + LandingPage::$status , '',array('id' => 'status_filter'))}}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Campaign:
                                {{Form::select('campaign_filter',array(''=>'Select Campaign') , '',array('id' => 'campaign_filter'))}}
                            </label>
                        </div>
                    </div>

                    <div class="row">

                        <div class="large-6 columns text-right">
                            <input type="button" value="Clear Filters" class="alert button tiny" style="width: 100%;" onclick="clearLeadsFilter()">
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
                            <h1>Landing Pages</h1>
                        </div>
                        <div class="large-6 columns">
                            <button class="button tiny float-right" type="button" data-open="reveal_add_new_landing_page">Add New Landing Page</button>
                            <div class="reveal" id="reveal_add_new_landing_page" name="reveal_add_new_landing_page" data-reveal>
                                <div class="panel-content">
                                    <div class="row">
                                        <div class="large-12 columns">
                                            @include('landing_pages._partials.add_landing_page_form')
                                        </div>
                                    </div>
                                    <button class="close-button" data-close aria-label="Close modal" type="button">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_landing_pages', 'class' => 'float-center', 'style' => 'display:none')) }}
                    <div id="response_landing_pages">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function loadDashboardData(){
            ajaxLoadLandgingPagesList(1);
        }

        loadDashboardData();

        /*==================== PAGINATION =========================*/

        $(document).on('click', '#pagination_landing_pages_list a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            //location.hash = page;
            ajaxLoadLandgingPagesList(page);
        });

        function ajaxLoadLandgingPagesList(page) {

            $('#loader_landing_pages').show();
            $('#response_landing_pages').hide();

            var dashboard_filter_date_range = $('#dashboard_filter_date_range').val();
            var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
            var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

            $.ajax({
                url: '/landing-pages/ajax/load-landing-pages-list?' +
                'page=' + page +
                '&dashboard_filter_date_range=' + dashboard_filter_date_range +
                '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                '&dashboard_filter_to_date=' + dashboard_filter_to_date

            }).done(function (data) {

                $('#response_landing_pages').html(data);
                $('#loader_landing_pages').hide();
                $('#response_landing_pages').show();
                $(document).foundation();

            });
        }

        function getCampaignsList() {
            $.ajax({
                url: '/campaigns/ajax/get-campaigns-list'
            }).done(function(data){
                $('#campaign_filter').empty();
                data = $.parseJSON(data);
                $('#campaign_filter').append("<option value=''>Select Campaign</option>");

                for(var i in data)
                {
                    $('#campaign_filter').append("<option value='" + data[i].id +"'>" + data[i].campaign_name + "</option>");
                }

                $('#campaign_filter').selectize({
                    create: false,
                    sortField: 'text'
                });
            });
        }
        getCampaignsList();

        $(document).ready(function(){
            $('#status_filter').selectize({
                create: false,
                sortField: 'text'
            });
        });

    </script>

@stop