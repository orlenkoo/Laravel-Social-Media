@extends('layouts.default')

@section('content')
    <input type="hidden" name="email_campaign_id" id="email_campaign_id" value="{{ $email_campaign->id }}">

    <div class="row expanded">
        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-content">

                    <div class="row">
                        <div class="large-12 columns">
                            <label for="campaign_id">Campaign
                                <select name="campaign_id" id="campaign_id" onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'campaign_id', this.value, 'campaign_id', 'EmailModuleEmailCampaign');">
                                    <option value="">Select Campaign</option>
                                </select>
                            </label>
                            <script>
                                getCampaignsList();
                                function getCampaignsList() {
                                    $.ajax({
                                        url: '/campaigns/ajax/get-campaigns-list'
                                    }).done(function(data){
                                        $('#campaign_id').empty();
                                        data = $.parseJSON(data);
                                        $('#campaign_id').append("<option value=''>Select one</option>");

                                        for(var i in data)
                                        {
                                            if(data[i].id == '{{ $email_campaign->campaign_id }}') {
                                                $('#campaign_id').append("<option value='" + data[i].id +"' selected>" + data[i].campaign_name + "</option>");
                                            } else {
                                                $('#campaign_id').append("<option value='" + data[i].id +"'>" + data[i].campaign_name + "</option>");
                                            }

                                        }

                                        $('#campaign_id').selectize({
                                            create: false,
                                            sortField: 'text'
                                        });
                                    });
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="start_date_time">
                                Start Date Time *
                                <input type="text" name="start_date_time" id="start_date_time" value="{{ $email_campaign->start_date_time }}" class="datetimepicker"onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'start_date_time', this.value, 'start_date_time', 'EmailModuleEmailCampaign');">
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="end_date_time">
                                End Date Time *
                                <input type="text" name="end_date_time" id="end_date_time" value="{{ $email_campaign->end_date_time }}" class="datetimepicker"onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'end_date_time', this.value, 'end_date_time', 'EmailModuleEmailCampaign');">
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="status">Status
                                <select name="status" id="status" onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'status', this.value, 'status', 'EmailModuleEmailCampaign');">
                                    @foreach(EmailModuleEmailCampaign::$status as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $email_campaign->status? "selected": "" }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="start_automatically">
                                <input type="checkbox" name="start_automatically" id="start_automatically" {{ $email_campaign->start_automatically == 1? "checked": "" }} onchange="updateStartAutomaticallyStatus()"> Start Automatically
                            </label>
                            <script>
                                function updateStartAutomaticallyStatus() {
                                    var start_automatically_value = 0;
                                    if($('#start_automatically').is(':checked')) {
                                        start_automatically_value = 1;
                                    }
                                    ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'start_automatically', start_automatically_value, 'start_automatically', 'EmailModuleEmailCampaign');
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns">
                            <h5>From</h5>
                            <em>Who is sending this campaign?</em>
                        </div>
                    </div>

                    <div class="row">
                        <div class="large-12 columns">
                            <label for="from_name">
                                Name
                                <input type="text" name="from_name" id="from_name" value="{{ $email_campaign->from_name }}"onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'from_name', this.value, 'from_name', 'EmailModuleEmailCampaign');">
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="large-12 columns">
                            <label for="from_email_address">
                                Email Address
                                <input type="text" name="from_email_address" id="from_email_address" value="{{ $email_campaign->from_email_address }}"onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'from_email_address', this.value, 'from_email_address', 'EmailModuleEmailCampaign');">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="large-9 columns">

            <div class="row">
                <div class="large-12 columns">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="large-8 columns">
                                    <h1>Manage Email Campaign</h1>
                                </div>
                                <div class="large-4 columns">
                                    <input type="button" class="tiny button secondary float-right" value="Send a Test Email">
                                </div>
                            </div>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="large-12 columns">
                                    <label for="email_campaign_name">
                                        Email Campaign Name:
                                        <input type="text" value="{{ $email_campaign->campaign_name }}" name="email_campaign_name" id="email_campaign_name" onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'email_campaign_name', this.value, 'email_campaign_name', 'EmailModuleEmailCampaign');">
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-12 columns">
                                    <label for="subject">
                                        Subject (What's the subject line for this campaign?)
                                        <input type="text" name="subject" id="subject" value="{{ $email_campaign->subject }}"onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'subject', this.value, 'subject', 'EmailModuleEmailCampaign');">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="large-6 columns">

                    <div class="panel">
                        <div class="panel-content">
                            <div class="row">
                                <div class="large-9 columns">
                                    <h5>To</h5>
                                    <em>Who are you sending this campaign to?</em>
                                </div>
                                <div class="large-3 columns">
                                    <input type="button" class="tiny button float-right" value="Create New Email List" data-open="popup_add_new_email_list_form">
                                    <div class="large panel reveal" id="popup_add_new_email_list_form" data-reveal>
                                        @include('email_module._partials.add_new_email_list_form')
                                        <button class="close-button" data-close aria-label="Close modal" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-12 columns">
                                    <label for="search_email_list_search_query">
                                        Search by Email List Name
                                        <input type="text" name="search_email_list_search_query" id="search_email_list_search_query" value="" onchange="ajaxLoadEmailLists()">
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-12 columns">
                                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_email_lists', 'class' => 'float-center', 'style' => 'display:none')) }}
                                    <div id="response_email_lists">

                                    </div>
                                    <script>
                                        /*==================== PAGINATION =========================*/

                                        $(document).on('click', '#pagination_email_lists_list a', function (e) {
                                            e.preventDefault();
                                            var page = $(this).attr('href').split('page=')[1];
                                            //location.hash = page;
                                            ajaxLoadEmailLists(page);
                                        });

                                        function ajaxLoadEmailLists(page) {
                                            var search_email_list_search_query = $('#search_email_list_search_query').val();
                                            var email_campaign_id = $('#email_campaign_id').val();

                                            $('#loader_email_lists').show();
                                            $('#response_email_lists').hide();


                                            $.ajax({
                                                url: '/email-module/ajax/load-email-lists?' +
                                                'page=' + page +
                                                '&search_query=' + search_email_list_search_query +
                                                '&email_campaign_id=' + email_campaign_id

                                            }).done(function (data) {
                                                $('#response_email_lists').html(data);
                                                $('#loader_email_lists').hide();
                                                $('#response_email_lists').show();
                                                $(document).foundation();
                                                $('.datetimepicker').datetimepicker({
                                                    datepicker: true,
                                                    format: 'Y-m-d H:i',
                                                    step : 30,
                                                    scrollInput : false
                                                });
                                            });
                                        }

                                        ajaxLoadEmailLists(1);
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="large-6 columns">

                    <div class="panel">
                        <div class="panel-content">
                            <div class="row">
                                <div class="large-9 columns">
                                    <h5>Content</h5>
                                    <em>Design the content of your email.</em>
                                </div>
                                <div class="large-3 columns">
                                    <input type="button" class="tiny button float-right" value="Update Content" data-open="popup_update_content">
                                    <div class="large panel reveal" id="popup_update_content" data-reveal>
                                        @include('email_module._partials.manage_email_content')
                                        <button class="close-button" data-close aria-label="Close modal" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-12 columns">
                                    <iframe id="email_content_preview_iframe" src="/email-module/ajax/load-email-content_preview?email_campaign_id={{ $email_campaign->id }}" width="100%" height="500px" style="overflow-y: scroll;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>



@stop