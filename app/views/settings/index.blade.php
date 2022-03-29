@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-2 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h5>Settings</h5>
                </div>
                <div class="panel-content">
                    <ul class="vertical tabs" data-tabs id="example-tabs">
                        @if(Employee::checkAuthorizedAccess(array('management', 'marketing')))
                            {{--<li class="tabs-title is-active"><a href="#panel_media_channels" aria-selected="true" onclick="ajaxLoadMediaChannelsList(1)">Media Channels</a></li>--}}
                            <li class="tabs-title is-active"><a href="#panel_email_templates" aria-selected="true" >Email Templates</a></li>
                            <li class="tabs-title"><a href="#panel_employees" onclick="ajaxLoadEmployeesList(1)">Employees</a></li>
                            <li class="tabs-title"><a href="#panel_preferences">Preferences</a></li>
                        @endif
                        @if(Employee::checkAuthorizedAccess(array('management')))
                                {{--<li class="tabs-title"><a href="#panel_data_export" onclick="ajaxLoadDataExportList(1)">Data Export</a></li>--}}
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="large-10 columns">
            <div class="panel">
                <div class="row expanded collapse">
                    <div data-tabs-content="example-tabs">
                        <div class="tabs-panel" id="panel_media_channels">
                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-6 columns">
                                        <h1>Media Channels</h1>
                                    </div>

                                    <div class="large-6 columns">
                                        <input type="button" value="Add Media Channel" class="button tiny float-right" data-open="newMediaChannelForm">
                                        <div class="panel reveal" id="newMediaChannelForm" data-reveal>
                                            @include('settings.media_channels._partials.add_new_media_channel_form')
                                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-content">
                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        <label for="">
                                            {{ Form::text('search_query_media_channel', null, array('placeholder' => 'Search by Channel', 'id' => 'search_query_media_channel', 'onchange' => "ajaxLoadMediaChannelsList()")) }}
                                        </label>
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_media_channels_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                                        <div id="response_media_channels_list">

                                        </div><!--end response_media_channels_list-->
                                        <script>
                                            /*==================== PAGINATION =========================*/

                                            $(document).on('click', '#pagination_media_channels_list a', function (e) {
                                                e.preventDefault();
                                                var page = $(this).attr('href').split('page=')[1];
                                                //location.hash = page;
                                                ajaxLoadMediaChannelsList(page);
                                            });

                                            function ajaxLoadMediaChannelsList(page) {
                                                $('#loader_media_channels_list').show();
                                                $('#response_media_channels_list').hide();

                                                var search_query_media_channel = $("#search_query_media_channel").val();

                                                $.ajax({
                                                    url: '/settings/ajax/load-media-channels-list?' +
                                                    'page=' + page +
                                                    '&search_query_media_channel=' + search_query_media_channel

                                                }).done(function (data) {
                                                    $('#response_media_channels_list').html(data);
                                                    $('#loader_media_channels_list').hide();
                                                    $('#response_media_channels_list').show();
                                                    $(document).foundation();
                                                });
                                            }


                                            $( document ).ready(function() {
                                                ajaxLoadMediaChannelsList(1);
                                            });

                                        </script>
                                    </div>
                                </div>


                            </div>
                        </div><!--end panel_media_channels-->

                        <div class="tabs-panel is-active" id="panel_employees">
                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-6 columns">
                                        <h1>Employees</h1>
                                    </div>

                                    <div class="large-6 columns">
                                        <input type="button" value="Add Employee" class="button tiny float-right" data-open="popup_add_employee_form">
                                        <div class="panel reveal" id="popup_add_employee_form" data-reveal>
                                            @include('employees._partials.form')
                                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="panel-content">
                                <div class="row expanded">
                                    <div class="large-6 columns">
                                        {{ Form::text('search_query', null, array('placeholder' => 'Search by Given Name / Surname / Designation / Contact', 'id' => 'search_query', 'onchange'=>'ajaxLoadEmployeesList()')) }}
                                    </div>

                                    <div class="large-4 columns">
                                        {{  Form::select('search_user_level',array("" => "Filter By User Level") + Employee::$user_levels, null, array('id' => 'search_user_level', 'onchange'=>'ajaxLoadEmployeesList()')) }}
                                    </div>

                                    <div class="large-2 columns">

                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_employees_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                                        <div id="response_employees_list">

                                        </div><!--end response_media_channels_list-->
                                        <script>
                                            /*==================== PAGINATION =========================*/

                                            $(document).on('click', '#pagination_employees_list a', function (e) {
                                                e.preventDefault();
                                                var page = $(this).attr('href').split('page=')[1];
                                                //location.hash = page;
                                                ajaxLoadEmployeesList(page);
                                            });

                                            function ajaxLoadEmployeesList(page) {
                                                $('#loader_employees_list').show();
                                                $('#response_employees_list').hide();

                                                var search_query = $("#search_query").val();
                                                var search_user_level = $("#search_user_level").val();

                                                $.ajax({
                                                    url: '/employees/ajax/load-employees-list?' +
                                                    'page=' + page +
                                                    '&search_query=' + search_query +
                                                    '&search_user_level=' + search_user_level

                                                }).done(function (data) {
                                                    $('#response_employees_list').html(data);
                                                    $('#loader_employees_list').hide();
                                                    $('#response_employees_list').show();
                                                    $(document).foundation();
                                                });
                                            }


                                        </script>
                                    </div>
                                </div>


                            </div>
                        </div><!--end panel_employees-->

                        <div class="tabs-panel" id="panel_preferences">
                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <h1>Preferences</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-content">
                                @include('settings.organization_preferences._partials.organization_preferences_form')
                            </div>
                        </div><!--end panel_preferences -->

                        <div class="tabs-panel" id="panel_data_export">
                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <h1>Data Export</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-content">

                                @include('data_exports._partials.form')

                                <div class="row expanded">

                                    <div class="large-12 columns">
                                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_data_export_requests_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                                        <div id="response_data_export_requests_list">
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <script>

                                $(document).on('click', '#pagination_data_export_requests_list a', function (e) {
                                    e.preventDefault();
                                    var page = $(this).attr('href').split('page=')[1];
                                    //location.hash = page;
                                    ajaxLoadDataExportList(page);
                                });

                                function ajaxLoadDataExportList(page) {
                                    $('#loader_data_export_requests_list').show();
                                    $('#response_data_export_requests_list').hide();

                                    $.ajax({
                                        url: '/data-export/ajax/load-data-export-list?' +
                                        'page=' + page

                                    }).done(function (data) {
                                        $('#response_data_export_requests_list').html(data);
                                        $('#loader_data_export_requests_list').hide();
                                        $('#response_data_export_requests_list').show();
                                        $(document).foundation();
                                    });
                                }

                            </script>
                        </div>

                        <div class="tabs-panel" id="panel_email_templates">
                            <div class="panel-heading">
                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <h1>Email Templates</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-content">
                                <ul class="tabs" data-tabs id="email_templates_tabs">
                                    <li class="tabs-title is-active"><a href="#panel_tags" aria-selected="true" onclick="ajaxLoadEmailTemplateUserDefinedTagsList(1)">Tags</a></li>
                                    <li class="tabs-title"><a href="#panel_action_buttons" aria-selected="true" onclick="ajaxLoadEmailTemplateUserDefinedActionButtonsList(1)">Action Buttons</a></li>
                                    <li class="tabs-title"><a href="#panel_templates" aria-selected="true" onclick="ajaxLoadEmailTemplatesList(1)" >Templates</a></li>
                                </ul>
                                <div class="tabs-content" data-tabs-content="email_templates_tabs">
                                    <div class="tabs-panel" id="panel_templates">
                                        <div class="panel-content">
                                            <div class="row expanded">
                                                <div class="large-12 columns">
                                                    <button class="button tiny float-right" type="button" data-open="reveal_add_email_template">Add Email Template</button>
                                                    <div class="panel reveal large" id="reveal_add_email_template" name="reveal_add_email_template" data-reveal>
                                                        @include('settings.email_templates._partials.add_new_email_template_form')
                                                        <button class="close-button" data-close aria-label="Close modal" type="button">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row expanded">
                                                <div class="large-12 columns">
                                                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_email_templates_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                                                    <div id="response_email_templates_list">

                                                    </div><!--end response_email_templates_tags_list-->
                                                    <script>
                                                        /*==================== PAGINATION =========================*/

                                                        $(document).on('click', '#pagination_email_templates_list a', function (e) {
                                                            e.preventDefault();
                                                            var page = $(this).attr('href').split('page=')[1];
                                                            //location.hash = page;
                                                            ajaxLoadEmailTemplatesList(page);
                                                        });

                                                        function ajaxLoadEmailTemplatesList(page) {
                                                            $('#loader_email_templates_list').show();
                                                            $('#response_email_templates_list').hide();

                                                            $.ajax({
                                                                url: '/email-templates/ajax/load-email_templates-list?' +
                                                                'page=' + page

                                                            }).done(function (data) {
                                                                $('#response_email_templates_list').html(data);
                                                                $('#loader_email_templates_list').hide();
                                                                $('#response_email_templates_list').show();
                                                                $(document).foundation();
                                                            });
                                                        }

                                                        function insertEmailTemplateSelectedTag(selected_value,ck_editor){
                                                            var selected_value = $("#"+selected_value).val();
                                                            selected_value = selected_value.trim();
                                                            CKEDITOR.instances[ck_editor].insertText(selected_value);
                                                        }

                                                        function displaySelectedTag(label_id,selected_value){
                                                            $('#'+label_id).text('Selected Tag: '+selected_value);
                                                        }



                                                    </script>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="tabs-panel is-active" id="panel_tags">
                                        <div class="panel-content">
                                            <div class="row expanded">
                                                <div class="large-8 columns">
                                                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_email_templates_tags_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                                                    <div id="response_email_templates_tags_list">

                                                    </div><!--end response_email_templates_tags_list-->
                                                    <script>
                                                        /*==================== PAGINATION =========================*/

                                                        $(document).on('click', '#pagination_email_templates_tags_list a', function (e) {
                                                            e.preventDefault();
                                                            var page = $(this).attr('href').split('page=')[1];
                                                            //location.hash = page;
                                                            ajaxLoadEmailTemplateUserDefinedTagsList(page);
                                                        });

                                                        function ajaxLoadEmailTemplateUserDefinedTagsList(page) {
                                                            $('#loader_email_templates_tags_list').show();
                                                            $('#response_email_templates_tags_list').hide();

                                                            $.ajax({
                                                                url: '/email-templates/ajax/load-user-defined-tags-list?' +
                                                                'page=' + page

                                                            }).done(function (data) {
                                                                $('#response_email_templates_tags_list').html(data);
                                                                $('#loader_email_templates_tags_list').hide();
                                                                $('#response_email_templates_tags_list').show();
                                                                $(document).foundation();
                                                            });
                                                        }

                                                        function ajaxUpdateUserDefinedTagName(tag_name,tag_id,previous_tag_name){

                                                            if(previous_tag_name == tag_name){
                                                                return;
                                                            }

                                                            $.ajax({
                                                                url: '/email-templates/ajax/check-user-defined-tag-exists?' +
                                                                'name=' + tag_name

                                                            }).done(function (data) {

                                                                var response = JSON.parse(data);

                                                                if(response.status){

                                                                    $.notify('Tag already exists.');

                                                                }else{

                                                                    $.post("/email-templates/ajax/update-user-defined-tag-name/",
                                                                        {
                                                                            tag_id: tag_id,
                                                                            name: tag_name
                                                                        }, function (data) {

                                                                        $.notify('Updated Successfully.','success');
                                                                        ajaxLoadEmailTemplateUserDefinedTagsList(1);
                                                                        ajaxLoadEmailTemplatesList(1);

                                                                    });
                                                                }
                                                            });
                                                        }

                                                        $( document ).ready(function() {
                                                            ajaxLoadEmailTemplateUserDefinedTagsList(1);
                                                        });

                                                    </script>
                                                </div>

                                                <div class="large-4 columns">
                                                    @include('settings.email_templates._partials.add_new_user_defined_tag_form')
                                                </div>

                                            </div>


                                        </div>
                                    </div>

                                    <div class="tabs-panel" id="panel_action_buttons">
                                        <div class="panel-content">
                                            <div class="row expanded">
                                                <div class="large-8 columns">
                                                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_email_templates_action_buttons_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                                                    <div id="response_email_templates_action_buttons_list">

                                                    </div><!--end response_email_templates_action_buttonss_list-->
                                                    <script>
                                                        /*==================== PAGINATION =========================*/

                                                        $(document).on('click', '#pagination_email_templates_action_buttons_list a', function (e) {
                                                            e.preventDefault();
                                                            var page = $(this).attr('href').split('page=')[1];
                                                            //location.hash = page;
                                                            ajaxLoadEmailTemplateUserDefinedActionButtonsList(page);
                                                        });

                                                        function ajaxLoadEmailTemplateUserDefinedActionButtonsList(page) {
                                                            $('#loader_email_templates_action_buttons_list').show();
                                                            $('#response_email_templates_action_buttons_list').hide();

                                                            $.ajax({
                                                                url: '/email-templates/ajax/load-user-defined-action-buttons-list?' +
                                                                'page=' + page

                                                            }).done(function (data) {
                                                                $('#response_email_templates_action_buttons_list').html(data);
                                                                $('#loader_email_templates_action_buttons_list').hide();
                                                                $('#response_email_templates_action_buttons_list').show();
                                                                $(document).foundation();
                                                            });
                                                        }

                                                        function ajaxUpdateUserDefinedActionButtonName(button_name,button_id,previous_button_name){

                                                            if(previous_button_name == button_name){
                                                                return;
                                                            }

                                                            $.ajax({
                                                                url: '/email-templates/ajax/check-user-defined-action-button-exists?' +
                                                                'button_name=' + button_name

                                                            }).done(function (data) {

                                                                var response = JSON.parse(data);

                                                                if(response.status){

                                                                    $.notify('Action Button already exists.');
                                                                }else{

                                                                    $.post("/email-templates/ajax/update-user-defined-action-button-name/",
                                                                        {
                                                                            button_id: button_id,
                                                                            button_name: button_name
                                                                        }, function (data) {

                                                                            $.notify('Updated Successfully.','success');
                                                                            ajaxLoadEmailTemplateUserDefinedActionButtonsList(1);
                                                                            ajaxLoadEmailTemplatesList(1);
                                                                        });
                                                                }
                                                            });
                                                        }

                                                    </script>
                                                </div>

                                                <div class="large-4 columns">
                                                    @include('settings.email_templates._partials.add_new_user_defined_action_button_form')
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--end panel_email_templates -->


                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

        $( document ).ready(function() {
            $(document).foundation();
        });

        function loadDashboardData() {
            ajaxLoadMediaChannelsList(1);
            ajaxLoadEmployeesList(1);
            ajaxLoadEmailTemplatesList(1);
            ajaxLoadEmailTemplateUserDefinedTagsList(1);
            ajaxLoadEmailTemplateUserDefinedActionButtonsList(1);
        }
    </script>



@stop