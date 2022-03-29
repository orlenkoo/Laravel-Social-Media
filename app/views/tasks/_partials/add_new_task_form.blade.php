<?php
    $id = uniqid();
    $activity_data = TasksController::setActivityDataForCalendar($activity_type,$activity_object);
?>
{{ Form::open(array('route' => 'tasks.ajax.add_task', 'ajax' => 'true', 'id' => 'new_task_form_'.$id,'autocomplete' => 'off')) }}

<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Add New Task</h4>
        </div>
    </div>
</div>

<div class="panel-content">
    <div class="row">
        <div class="large-6 columns">

            <div class="large-12 columns">
                <input type="text" id="new_task_form_title_{{$id}}" name="title" value="@if(!empty($activity_data)){{$activity_data['title']}}@endif" placeholder="Title" data-validation="required">
            </div>

            <div class="large-6 columns">
                <input type="text" id="new_task_form_from_date_time_{{$id}}" name="from_date_time" value="@if(!empty($activity_data)){{$activity_data['date_start']}}@endif" class="datetimepicker" placeholder="From DateTime" data-validation="required" onchange="checkTaskDates_{{$id}}();">
            </div>

            <div class="large-6 columns">
                <input type="text" id="new_task_form_to_date_time_{{$id}}" name="to_date_time" value="@if(!empty($activity_data)){{$activity_data['date_end']}}@endif" class="datetimepicker" placeholder="To DateTime" data-validation="required" onchange="checkTaskDates_{{$id}}();">
            </div>

            <div class="large-6 columns">
                <select name="assigned_to" id="new_task_form_assigned_to_{{$id}}" data-validation="required" class="new_task_form_assigned_to"></select>
            </div>

            <div class="large-6 columns">
                <input type="text" id="new_task_form_location_{{$id}}" name="location" value="@if(!empty($activity_data)){{$activity_data['location']}}@endif" placeholder="Location">
            </div>

            <div class="large-12 columns">
                <textarea name="description" id="new_task_form_description_{{$id}}" cols="30" rows="10" placeholder="Description">@if(!empty($activity_data)){{$activity_data['description']}}@endif</textarea>
            </div>

            <hr>

            <div class="large-6 columns">
                <label for="customer_id">Customer
                    <select name="customer_id" id="new_task_form_customer_id_{{$id}}" onchange="ajaxLoadContactsListByCustomer_{{$id}}(this.value)">
                        <option value="">Select Customer</option>ajaxLoadContactsListByCustomer
                    </select>
                </label>

            </div>

            <div class="large-6 columns">
                <label for="contact_id">
                    Contact
                    <select name="contact_id" id="new_task_form_contact_id_{{$id}}">
                        <option value="">Select Customer</option>
                    </select>
                </label>
            </div>


        </div>
        <div class="large-6 columns">
            <div class="large-12 columns">
                <p><strong>Reminders</strong></p>

                <table id="task_reminders_table_{{$id}}" class="basic-table">
                    <tr class="">
                        <td width="30%">
                            <select name="reminder_types[]" id="add_task_reminder_type_{{$id}}" data-validation="required">
                                @foreach(TaskReminder::$reminder_types as $key => $value)
                                    @if($key == 'email')
                                        <option selected value="{{$key}}">{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <br/>
                        </td>
                        <td width="20%">
                            &nbsp;
                            <input type="text" name="times[]" id="numbersonly add_task_reminder_time_{{$id}}" value="5" data-validation="required">
                            <br/>
                        </td>
                        <td width="30%">
                            <select name="time_units[]" id="add_task_reminder_time_unit_{{$id}}" data-validation="required">
                                @foreach(TaskReminder::$time_units as $key => $value)
                                    @if($key == 'minutes')
                                        <option selected value="{{$key}}">{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <br/>
                        </td>
                        <td width="20%">
                            &nbsp;
                            <button class="remove_task_reminder_button_{{$id}} button tiny alert">X</button>
                        </td>
                    </tr>
                </table>

                <p><input type="button" value="Add New Reminder" class="button tiny alert" onclick="addReminder_{{$id}}();"></p>

            </div>

            <br>

            <p><strong>Guests</strong></p>

            <div class="large-12 columns">
                <input type="text" id="add_task_reminder_guests_{{$id}}" name="guest_emails" value="" placeholder="Guest Emails" data-validation="">
            </div>
        </div>
    </div>
</div>

<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns text-right">
            <input type="button" value="Cancel" class="button tiny alert" onclick="$('#reveal_add_new_task<?php if(!empty($activity_data)) echo '_'.$activity_data['id']; ?>').foundation('close');">
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Save', array("class" => "button success tiny", "id" => "new_task_form_save_button_".$id)) }}
        </div>
    </div>
    <div class="row loading_animation" style="display: none;">
        <div class="large-12 columns text-center">
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
        </div>
    </div>
</div>



{{ Form::close() }}

<script>

    $(document).ready(function() {
        setUpAjaxForm('new_task_form_{{$id}}', 'create', '',
            function(){
                clearAddNewTaskForm_{{$id}}();
                $('#reveal_add_new_task<?php if(!empty($activity_data)) echo '_'.$activity_data['id']; ?>').foundation('close');
                ajaxLoadTasksList(1);
                refreshTaskCalender();
            }
        );

        $('#add_task_reminder_guests_{{$id}}').selectize({
            delimiter: ', ',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $(document).on('click', 'button.remove_task_reminder_button_{{$id}}', function () {
            $(this).closest('tr').remove();
            return false;
        });

        getAssignedToEmployeesList_{{$id}}();
        ajaxLoadCustomerList_{{$id}}();

        $('.datetimepicker').datetimepicker({
            datepicker: true,
            format: 'Y-m-d H:i',
            step : 30,
            scrollInput: false
        });
    });

    function addReminder_{{$id}}() {

        var table_row_html = '<tr><td width="30%"><select name="reminder_types[]" id="add_task_reminder_type_{{$id}}" data-validation="required">@foreach(TaskReminder::$reminder_types as $key => $value) @if($key == 'email')<option selected value="{{$key}}">{{$value}}</option>@else<option value="{{$key}}">{{$value}}</option>@endif @endforeach</select><br/></td><td width="20%">&nbsp;<input type="text" name="times[]" value="5" id="numbersonly add_task_reminder_time_{{$id}}" data-validation="required"><br/></td><td width="30%"><select name="time_units[]" id="add_task_reminder_time_unit_{{$id}}" data-validation="required">@foreach(TaskReminder::$time_units as $key => $value) @if($key == 'minutes')<option selected value="{{$key}}">{{$value}}</option>@else<option value="{{$key}}">{{$value}}</option>@endif @endforeach</select><br/></td><td width="20%"> &nbsp;<button class="remove_task_reminder_button_{{$id}} button tiny alert">X</button></td></tr>';

        if ($("#task_reminders_table_{{$id}} tr").length > 1) {

            $('#task_reminders_table_{{$id}} tr:last').after(table_row_html);

        } else {

            $('#task_reminders_table_{{$id}}').append(table_row_html);

        }

        return false;
    }

    function clearAddNewTaskForm_{{$id}}(){
        $("#new_task_form_title_{{$id}}").val('');
        $("#new_task_form_from_date_time_{{$id}}").val('');
        $("#new_task_form_to_date_time_{{$id}}").val('');
        $("#new_task_form_location_{{$id}}").val('');
        $("#new_task_form_description_{{$id}}").val('');

        var new_task_form_assigned_to = $('#new_task_form_assigned_to_{{$id}}').selectize();
        var control_new_task_form_assigned_to = new_task_form_assigned_to[0].selectize;
        control_new_task_form_assigned_to.clear();

        var add_task_reminder_guests = $('#add_task_reminder_guests_{{$id}}').selectize();
        var control_add_task_reminder_guests = add_task_reminder_guests[0].selectize;
        control_add_task_reminder_guests.clear();

        $("#task_reminders_table_{{$id}}").find("tr").remove();

        addReminder_{{$id}}();
    }


    function getAssignedToEmployeesList_{{$id}}() {
        $.ajax({
            url: '/employees/ajax/get-employees-list'
        }).done(function (data) {
            $('#new_task_form_assigned_to_{{$id}}').empty();
            data = $.parseJSON(data);
            $('#new_task_form_assigned_to_{{$id}}').append("<option value=''>Assigned to</option>");

            for (var i in data) {
                if (data[i].id == '{{ Session::get('user-id') }}') {
                    $('#new_task_form_assigned_to_{{$id}}').append("<option value='" + data[i].id + "' selected>" + data[i].full_name + "</option>");
                } else {
                    $('#new_task_form_assigned_to_{{$id}}').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                }

            }

            $('#new_task_form_assigned_to_{{$id}}').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }


    function ajaxLoadCustomerList_{{$id}}() {
        $.ajax({
            url: '/customers/ajax/load-customer-list'
        }).done(function (data) {
            $('#new_task_form_customer_id_{{$id}}').empty();
            data = $.parseJSON(data);
            $('#new_task_form_customer_id_{{$id}}').append("<option value=''>Customer</option>");

            for (var i in data) {
                    $('#new_task_form_customer_id_{{$id}}').append("<option value='" + data[i].id + "'>" + data[i].customer_name + "</option>");
            }

            $('#new_task_form_customer_id_{{$id}}').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function ajaxLoadContactsListByCustomer_{{$id}}(customer_id) {

        var $select = $('#new_task_form_contact_id_{{$id}}').selectize();
        var control = $select[0].selectize;
        control.clear();
        control.clearOptions();
        control.refreshOptions();
        control.destroy();

        $.ajax({
            url: '/contacts/ajax/load-contacts-list-by-customer-id?customer_id=' + customer_id
        }).done(function (data) {
            $('#new_task_form_contact_id_{{$id}}').empty();
            data = $.parseJSON(data);
            $('#new_task_form_contact_id_{{$id}}').append("<option value=''>Contact</option>");

            for (var i in data) {
                    $('#new_task_form_contact_id_{{$id}}').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
            }

            $('#new_task_form_contact_id_{{$id}}').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function checkTaskDates_{{$id}}(){

        var new_task_form_from_date_time = $("#new_task_form_from_date_time_{{$id}}").val();
        var new_task_form_to_date_time = $("#new_task_form_to_date_time_{{$id}}").val();
        if(new_task_form_from_date_time == '' || new_task_form_to_date_time == '' || compareTime(new_task_form_to_date_time,new_task_form_from_date_time)){
            return;
        }else{
            $("#new_task_form_to_date_time_{{$id}}").val('');
            $.notify("Task To date must come after From Date.");
        }
    }


</script>