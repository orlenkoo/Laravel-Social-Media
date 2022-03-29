@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="leads_list_search_text">
                                Search For:
                                <input id="leads_list_search_text" name="leads_list_search_text" type="text" placeholder="Lead Meta Details, lead Name">
                            </label>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="leads_list_filter_lead_source">
                                Source:
                                <select name="leads_list_filter_lead_source" id="leads_list_filter_lead_source">
                                    <option value="">All</option>
                                    @foreach(Lead::$lead_sources as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="leads_list_filter_lead_rating">
                                Rating:
                                <select name="leads_list_filter_lead_rating" id="leads_list_filter_lead_rating">
                                    <option value="">All</option>
                                    @foreach(Lead::$lead_ratings as $lead_rating)
                                        <option value="{{ $lead_rating }}">{{ $lead_rating }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="leads_list_filter_assigned_to">
                                Assigned To:
                                <select name="leads_list_filter_assigned_to" id="leads_list_filter_assigned_to" multiple>
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="large-12 columns">
                            <label for="leads_list_filter_campaign">
                                Campaign:
                                <select name="leads_list_filter_campaign" id="leads_list_filter_campaign" multiple>
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="large-12 columns">
                            <label for="leads_list_filter_tag">
                                Tag:
                                <select name="leads_list_filter_tag" id="leads_list_filter_tag" multiple>
                                </select>
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

                    <script>
                        function clearLeadsFilter() {
                            $('#leads_list_filter_lead_source').val('');
                            $('#leads_list_filter_lead_rating').val('');
                            $('#leads_list_search_text').val('');
                            var leads_list_filter_assigned_to = $('#leads_list_filter_assigned_to').selectize();
                            var control_leads_list_filter_assigned_to = leads_list_filter_assigned_to[0].selectize;
                            control_leads_list_filter_assigned_to.clear();
                            var leads_list_filter_campaign = $('#leads_list_filter_campaign').selectize();
                            var control_leads_list_filter_campaign = leads_list_filter_campaign[0].selectize;
                            control_leads_list_filter_campaign.clear();

                            var leads_list_filter_tag = $('#leads_list_filter_tag').selectize();
                            var control_leads_list_filter_tag = leads_list_filter_tag[0].selectize;
                            control_leads_list_filter_tag.clear();
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
                            <h1>Leads</h1>
                        </div>
                        <div class="large-6 columns">
                            <button class="button tiny float-right" type="button" data-open="reveal_add_new_lead">Add New Lead</button>
                            <div class="reveal panel small" id="reveal_add_new_lead" data-reveal>
                                <div class="panel-heading">
                                    <h4>Add New Lead</h4>
                                </div>
                                <div class="panel-content">

                                    @include('leads._partials.add_new_lead_form')

                                    <button class="close-button" data-close aria-label="Close modal" type="button">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_leads_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                    <div id="response_leads_list">

                    </div><!--end response_leads_list-->
                    <script>
                        // this function gets repeated in all the dashboards. And will have calls to functions that load content on those dashboards
                        function loadDashboardData() {
                            ajaxLoadLeadsList(1);
                        }
                        /*==================== PAGINATION =========================*/

                        $(document).on('click', '#pagination_leads_list a', function (e) {
                            e.preventDefault();
                            var page = $(this).attr('href').split('page=')[1];
                            //location.hash = page;
                            ajaxLoadLeadsList(page);
                        });

                        function ajaxLoadLeadsList(page) {
                            console.log('ajaxLoadLeadsList');
                            $('#loader_leads_list').show();
                            $('#response_leads_list').html('');
                            $('#response_leads_list').hide();

                            var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
                            var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                            var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                            var leads_list_filter_lead_source = $('#leads_list_filter_lead_source').val();
                            var leads_list_filter_lead_rating = $('#leads_list_filter_lead_rating').val();
                            var leads_list_search_text = $('#leads_list_search_text').val();
                            var leads_list_filter_assigned_to = $('#leads_list_filter_assigned_to').val();
                            var leads_list_filter_campaign = $('#leads_list_filter_campaign').val();
                            var leads_list_filter_tag = $('#leads_list_filter_tag').val();

                            $.ajax({
                                url: '/leads/ajax/load-leads-list?' +
                                'page=' + page +
                                '&dashboard_filter_date_range=' + dashboard_filter_date_range +
                                '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                                '&dashboard_filter_to_date=' + dashboard_filter_to_date +
                                '&leads_list_filter_lead_rating=' + leads_list_filter_lead_rating +
                                '&leads_list_search_text=' + leads_list_search_text +
                                '&leads_list_filter_lead_source=' + leads_list_filter_lead_source +
                                '&leads_list_filter_assigned_to=' + leads_list_filter_assigned_to +
                                '&leads_list_filter_campaign=' + leads_list_filter_campaign +
                                '&leads_list_filter_tag=' + leads_list_filter_tag

                            }).done(function (data) {
                                $('#response_leads_list').html(data);
                                $('#loader_leads_list').hide();
                                $('#response_leads_list').show();
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
                                setLeadTags();

                            });
                        }
                        loadDashboardData();



                        function assignLeadTags(tag_id,lead_id){

                            var lead_tags = $("#"+tag_id+"").val();

                            $.post("/leads/ajax/assign-lead-tags",
                                    {
                                        lead_id: lead_id,
                                        lead_tags: lead_tags
                                    },
                                    function (response, status) {
                                        $.notify(response, "success");
                                        getLeadTagsList();
                                    });
                        }

                        function setLeadTags(){
                            $(".lead_tags").selectize({
                                maxItems: null,
                                valueField: 'id',
                                labelField: 'tag',
                                searchField: ['tag', 'id'],
                                render: {
                                    item: function (item, escape) {
                                        return '<div>' +
                                                (item.tag ? '<span class="tag">' + escape(item.tag) + '</span>' : '') +
                                                (item.tag ? '' : '<span class="id">' + escape(item.id) + '</span>') +
                                                '</div>';
                                    },
                                    option: function (item, escape) {
                                        var label = item.tag || item.id;
                                        var caption = '';
                                        return '<div>' +
                                                '<span class="label">' + escape(label) + '</span>' +
                                                (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
                                                '</div>';
                                    }
                                },
                                createFilter: function (input) {
                                    return true;
                                },
                                create: function (input) {
                                    return {id: input};
                                }
                            });
                        }

                        $(document).ready(function() {

                            $('#search_lead_tags').selectize({
                                create: false,
                                sortField: 'text'
                            });

                            setLeadTags();
                            getAssignedToEmployeesList();
                            getCampaignsList();
                            getLeadTagsList();
                        });

                        function getAssignedToEmployeesList() {
                            $.ajax({
                                url: '/employees/ajax/get-employees-list'
                            }).done(function (data) {
                                $('#leads_list_filter_assigned_to').empty();
                                data = $.parseJSON(data);
                                $('#leads_list_filter_assigned_to').append("<option value=''>Assigned to</option>");

                                for (var i in data) {
                                    $('#leads_list_filter_assigned_to').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                                }

                                $('#leads_list_filter_assigned_to').selectize({
                                    create: false,
                                    sortField: 'text'
                                });
                            });
                        }

                        function getCampaignsList() {
                            $.ajax({
                                url: '/campaigns/ajax/get-campaigns-list'
                            }).done(function (data) {
                                $('#leads_list_filter_campaign').empty();
                                data = $.parseJSON(data);
                                $('#leads_list_filter_campaign').append("<option value=''>Campaign</option>");

                                for (var i in data) {
                                    $('#leads_list_filter_campaign').append("<option value='" + data[i].auto_tagged_campaign + "'>" + data[i].auto_tagged_campaign + "</option>");
                                }

                                $('#leads_list_filter_campaign').selectize({
                                    create: false,
                                    sortField: 'text'
                                });
                            });
                        }

                        function getLeadTagsList() {

                            var leads_list_filter_tag = $('#leads_list_filter_tag').selectize();
                            var control_leads_list_filter_tag = leads_list_filter_tag[0].selectize;
                            control_leads_list_filter_tag.destroy();

                            $.ajax({
                                url: '/leads/ajax/get-lead-tags'
                            }).done(function (data) {
                                $('#leads_list_filter_tag').empty();
                                data = $.parseJSON(data);
                                $('#leads_list_filter_tag').append("<option value=''>Tag</option>");

                                for (var i in data) {
                                    $('#leads_list_filter_tag').append("<option value='" + data[i].id + "'>" + data[i].tag + "</option>");
                                }

                                $('#leads_list_filter_tag').selectize({
                                    create: false,
                                    sortField: 'text'
                                });
                            });
                        }

                    </script>
                </div>
            </div>
        </div>
    </div>

@stop