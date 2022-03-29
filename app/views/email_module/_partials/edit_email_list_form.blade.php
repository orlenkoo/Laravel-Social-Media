{{ Form::open(array('route' => 'email_module.ajax.edit_email_list', 'ajax' => 'true', 'id' => 'edit_email_list_form_' . $email_list->id,'autocomplete' => 'off')) }}
<input type="hidden" name="email_list_id" id="email_list_id" value="{{ $email_list->id }}">
<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Edit Email List</h4>
        </div>
    </div>
</div>
<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="email_list_name">
                Email List Name
                <input type="text" name="email_list_name" id="email_list_name_{{ $email_list->id }}" value="{{ $email_list->email_list_name }}">
            </label>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="large-6 columns" style="border-right: 1px solid #0a0a0a;">
            <div class="row">
                <div class="large-12 columns">
                    <h5>Search For Contacts</h5>
                </div>
            </div>
            <div class="row">
                <div class="large-8 columns">
                    <label for="search_for_contacts_search_query">
                        Search For
                        <input type="text" name="search_for_contacts_search_query" id="search_for_contacts_search_query_{{ $email_list->id }}" onchange="ajaxSearchAndLoadContactsList_{{ $email_list->id }}(1)">
                    </label>
                </div>
                <div class="large-4 columns">
                    <label for="search_for_contacts_search_by">
                        Search By
                        <select name="search_for_contacts_search_by" id="search_for_contacts_search_by_{{ $email_list->id }}" onchange="ajaxSearchAndLoadContactsList_{{ $email_list->id }}(1)">
                            <option value="customer_name">Customer Name</option>
                            <option value="customer_tags">Customer Tags</option>
                            <option value="contact_name">Contact Name</option>
                            <option value="contact_email">Contact Email</option>
                            <option value="contact_phone_number">Contact Phone Number</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_search_contacts_list_'. $email_list->id, 'class' => 'float-center', 'style' => 'display:none')) }}
                    <div id="response_search_contacts_list_{{ $email_list->id }}">

                    </div>
                </div>
            </div>


        </div>

        <script>
            /*==================== PAGINATION =========================*/

            $(document).on('click', '#pagination_search_contacts_list a', function (e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                //location.hash = page;
                ajaxSearchAndLoadContactsList_{{ $email_list->id }}(page);
            });

            function ajaxSearchAndLoadContactsList_{{ $email_list->id }}(page) {
                var search_query = encodeURIComponent($('#search_for_contacts_search_query_{{ $email_list->id }}').val());
                var search_by = $('#search_for_contacts_search_by_{{ $email_list->id }}').val();

                $('#loader_search_contacts_list_{{ $email_list->id }}').show();
                $('#response_search_contacts_list_{{ $email_list->id }}').hide();


                $.ajax({
                    url: '/email-module/ajax/search-and-load-contacts-list?' +
                    'page=' + page +
                    '&search_query=' + search_query +
                    '&search_by=' + search_by +
                    '&selected_contents_list_id=selected_contacts_ids_{{ $email_list->id }}' +
                    '&selected_contents_reload_function=ajaxLoadSelectedContactsList_{{ $email_list->id }}'

                }).done(function (data) {
                    $('#response_search_contacts_list_{{ $email_list->id }}').html(data);
                    $('#loader_search_contacts_list_{{ $email_list->id }}').hide();
                    $('#response_search_contacts_list_{{ $email_list->id }}').show();
                    $('.datetimepicker').datetimepicker({
                        datepicker: true,
                        format: 'Y-m-d H:i',
                        step : 30,
                        scrollInput : false
                    });
                });
            }
        </script>

        <div class="large-6 columns">
            <div class="row">
                <div class="large-12 columns">
                    <h5>Selected Contacts</h5>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <label for="">
                        Search Selected Contacts List
                        <input type="text" name="search_selected_contacts_list_query" id="search_selected_contacts_list_query_{{ $email_list->id }}">
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <input type="hidden" id="selected_contacts_ids_{{ $email_list->id }}" name="selected_contacts_ids" value="{{ json_encode($email_list->getSelectedContactIds($email_list->id)) }}">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_selected_contacts_list_'. $email_list->id, 'class' => 'float-center', 'style' => 'display:none')) }}
                    <div id="response_selected_contacts_list_{{ $email_list->id }}">

                    </div>
                </div>
            </div>

            <script>
                /*==================== PAGINATION =========================*/

                $(document).on('click', '#pagination_selected_contacts_list a', function (e) {
                    e.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    //location.hash = page;
                    ajaxLoadSelectedContactsList_{{ $email_list->id }}(page);
                });

                function ajaxLoadSelectedContactsList_{{ $email_list->id }}(page) {
                    var selected_contacts_ids = $('#selected_contacts_ids_{{ $email_list->id }}').val();
                    var search_selected_contacts_list_query = $('#search_selected_contacts_list_query_{{ $email_list->id }}').val();

                    $('#loader_selected_contacts_list_{{ $email_list->id }}').show();
                    $('#response_selected_contacts_list_{{ $email_list->id }}').hide();


                    $.ajax({
                        url: '/email-module/ajax/load-selected-contacts-list?' +
                        'page=' + page +
                        '&selected_contacts_ids=' + selected_contacts_ids +
                        '&search_query=' + search_selected_contacts_list_query +
                        '&selected_contents_list_id=selected_contacts_ids_{{ $email_list->id }}' +
                        '&selected_contents_reload_function=ajaxLoadSelectedContactsList_{{ $email_list->id }}'

                    }).done(function (data) {
                        $('#response_selected_contacts_list_{{ $email_list->id }}').html(data);
                        $('#loader_selected_contacts_list_{{ $email_list->id }}').hide();
                        $('#response_selected_contacts_list_{{ $email_list->id }}').show();
                        $('.datetimepicker').datetimepicker({
                            datepicker: true,
                            format: 'Y-m-d H:i',
                            step : 30,
                            scrollInput : false
                        });
                    });
                }
            </script>
        </div>
    </div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns">
            <input type="button" value="Cancel" class="button tiny alert float-right" onclick="$('#popup_edit_email_list_form_<?php echo $email_list->id; ?>').foundation('close');">
        </div>
        <div class="large-2 columns text-right">

            <div class="row save_bar">
                <div class="large-12 columnsr">
                    {{ Form::submit('Save', array("class" => "button success tiny float-right", "id" => "new_email_campaign_form_save_button_$email_list->id")) }}

                </div>
            </div>

            <div class="row loading_animation" style="display: none;">
                <div class="large-12 columns text-center">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
                </div>
            </div>
        </div>
    </div>
</div>


{{ Form::close() }}

<script>
    $(document).ready(function() {
        setUpAjaxForm('edit_email_list_form_{{ $email_list->id }}', 'create', '#popup_edit_email_list_form_{{ $email_list->id }}', function(){
            ajaxLoadEmailLists(1);
        });
        resetForm('edit_email_list_form_{{ $email_list->id }}');
    });


</script>

