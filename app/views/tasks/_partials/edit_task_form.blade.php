{{ Form::open(array('route' => 'tasks.ajax.update_task', 'ajax' => 'true', 'id' => 'edit_task_form_'.$task->id,'autocomplete' => 'off' )) }}

<div class="row expanded">
    <div class="large-12 columns">
        <h4>Edit Task</h4>
    </div>
</div>

<div class="row">
    <div class="large-6 columns">

        <input type="hidden" id="task_id" name="task_id" value="{{$task->id }}" >

        <div class="large-12 columns">
            <input type="text" id="edit_task_form_title_{{$task->id }}" name="title" value="{{$task->title }}" placeholder="Title" data-validation="required">
        </div>

        <div class="large-6 columns">
            <input type="text" id="edit_task_form_from_date_time_{{$task->id }}" name="from_date_time" value="{{$task->from_date_time }}" class="datetimepicker" placeholder="From DateTime" data-validation="required" onchange="checkTaskDates_{{$task->id }}();">
        </div>

        <div class="large-6 columns">
            <input type="text" id="edit_task_form_to_date_time_{{$task->id }}" name="to_date_time" value="{{$task->to_date_time }}" class="datetimepicker" placeholder="To DateTime" data-validation="required" onchange="checkTaskDates_{{$task->id }}();">
        </div>

        <div class="large-6 columns">
            <select name="assigned_to" id="edit_task_form_assigned_to_{{$task->id }}" data-validation="required"></select>
        </div>

        <div class="large-6 columns">
            <input type="text" id="edit_task_form_location_{{$task->id }}" name="location" value="{{$task->location }}" placeholder="Location" >
        </div>

        <div class="large-12 columns">
            <textarea name="description" id="edit_task_form_description_{{$task->id }}" cols="30" rows="10" placeholder="Description">{{$task->description }}</textarea>
        </div>

        <hr>

        <div class="large-6 columns">
            <label for="customer_id">Customer
                <select name="customer_id" id="edit_task_form_customer_id_{{$task->id}}" onchange="ajaxLoadContactsListByCustomer_{{$task->id}}(this.value)">
                    <option value="">Select Customer</option>ajaxLoadContactsListByCustomer
                </select>
            </label>

        </div>

        <div class="large-6 columns">
            <label for="contact_id">
                Contact
                <select name="contact_id" id="edit_task_form_contact_id_{{$task->id}}">
                    <option value="">Select Customer</option>
                </select>
            </label>
        </div>


    </div>
    <div class="large-6 columns">
        <div class="large-12 columns">
            <p><strong>Reminders</strong></p>

            <table id="task_reminders_table_{{$task->id }}" class="basic-table">
                @foreach($task->taskReminders as $reminder)
                    <tr class="">
                        <td width="30%">
                            <select name="reminder_types[]" id="add_task_reminder_type_{{$task->id }}" data-validation="required">
                                @foreach(TaskReminder::$reminder_types as $key => $value)
                                    @if($key == $reminder->reminder_type)
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
                            <input type="text" name="times[]" id="numbersonly add_task_reminder_time_{{$task->id }}" value="{{$reminder->time }}" data-validation="required">
                            <br/>
                        </td>
                        <td width="30%">
                            <select name="time_units[]" id="add_task_reminder_time_unit_{{$task->id }}" data-validation="required">
                                @foreach(TaskReminder::$time_units as $key => $value)
                                    @if($key == $reminder->time_unit)
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
                            <button class="remove_task_reminder_button_{{$task->id }} button tiny alert">X</button>
                        </td>
                    </tr>
                @endforeach
            </table>

            <p><input type="button" value="Add New Reminder" class="button tiny alert" onclick="addReminder_{{$task->id }}();"></p>

        </div>

        <br>

        <p><strong>Guests</strong></p>

        <div class="large-12 columns">
            <select name="guest_emails[]" id="add_task_reminder_guests_{{$task->id }}" multiple="multiple">
                @foreach($task->taskGuests as $guest)
                    <option selected value="{{$guest->guest_email}}">{{$guest->guest_email}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="large-8 columns">
        &nbsp;
    </div>
    <div class="large-2 columns text-right">
        <input type="button" value="Cancel" class="button tiny alert" onclick="$('#reveal_edit_new_task_{{$task->id }}').foundation('close');">
    </div>
    <div class="large-2 columns text-right">

        <div class="row save_bar">
            <div class="large-12 columns text-center">
                @if(Session::get('user-id') == $task->assigned_to)
                        {{ Form::submit('Save', array("class" => "button success tiny", "id" => "edit_task_form_save_button_".$task->id)) }}
                @else
                        {{ Form::submit('Save', array("class" => "button success tiny", "disabled" => "disabled")) }}
                @endif
            </div>
        </div>

        <div class="row loading_animation" style="display: none;">
            <div class="large-12 columns text-center">
                {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
            </div>
        </div>
    </div>
</div>

{{ Form::close() }}

<script>

    $(document).ready(function() {
        setUpAjaxForm('edit_task_form_{{$task->id }}', 'create', '',
                function(){
                    clearAddNewTaskForm_{{$task->id }}();
                    ajaxLoadTasksList(1);
                    $('#reveal_edit_new_task_{{$task->id }}').foundation('close');
                    refreshTaskCalender();
                    $('#reveal_edit_calender_task').foundation('close');
                    $('#reveal_edit_new_task_{{$task->id}}').foundation('close');
                }
        );

        $('#add_task_reminder_guests_{{$task->id }}').selectize({
            delimiter: ', ',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $(document).on('click', 'button.remove_task_reminder_button_{{$task->id }}', function () {
            $(this).closest('tr').remove();
            return false;
        });

        getAssignedToEmployeesList_{{$task->id }}();
        ajaxLoadCustomerList_{{$task->id }}();
        ajaxLoadCustomerList_{{$task->id }}();
        ajaxLoadContactsListByCustomer_{{$task->id}}('{{ $task->customer_id }}');

    });

    function addReminder_{{$task->id }}() {

        var table_row_html = '<tr><td width="30%"><select name="reminder_types[]" id="add_task_reminder_type" data-validation="required">@foreach(TaskReminder::$reminder_types as $key => $value) @if($key == 'email')<option selected value="{{$key}}">{{$value}}</option>@else<option value="{{$key}}">{{$value}}</option>@endif @endforeach</select><br/></td><td width="20%">&nbsp;<input type="text" name="times[]" value="5" id="numbersonly add_task_reminder_time" data-validation="required"><br/></td><td width="30%"><select name="time_units[]" id="add_task_reminder_time_unit" data-validation="required">@foreach(TaskReminder::$time_units as $key => $value) @if($key == 'minutes')<option selected value="{{$key}}">{{$value}}</option>@else<option value="{{$key}}">{{$value}}</option>@endif @endforeach</select><br/></td><td width="20%"> &nbsp;<button class="remove_task_reminder_button_{{$task->id }} button tiny alert">X</button></td></tr>';

        if ($("#task_reminders_table_{{$task->id }} tr").length > 1) {

            $('#task_reminders_table_{{$task->id }} tr:last').after(table_row_html);

        } else {

            $('#task_reminders_table_{{$task->id }}').append(table_row_html);

        }

        return false;
    }

    function clearAddNewTaskForm_{{$task->id }}(){

    }


    function getAssignedToEmployeesList_{{$task->id }}() {
        $.ajax({
            url: '/employees/ajax/get-employees-list'
        }).done(function (data) {
            $('#edit_task_form_assigned_to_{{$task->id }}').empty();
            data = $.parseJSON(data);
            $('#edit_task_form_assigned_to_{{$task->id }}').append("<option value=''>Assigned to</option>");

            for (var i in data) {
                if (data[i].id == '{{$task->assigned_to }}') {
                    $('#edit_task_form_assigned_to_{{$task->id }}').append("<option value='" + data[i].id + "' selected>" + data[i].full_name + "</option>");
                } else {
                    $('#edit_task_form_assigned_to_{{$task->id }}').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                }

            }

            $('#edit_task_form_assigned_to_{{$task->id }}').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function ajaxLoadCustomerList_{{$task->id}}() {
        $.ajax({
            url: '/customers/ajax/load-customer-list'
        }).done(function (data) {
            $('#edit_task_form_customer_id_{{$task->id}}').empty();
            data = $.parseJSON(data);
            $('#edit_task_form_customer_id_{{$task->id}}').append("<option value=''>Customer</option>");

            for (var i in data) {
                if (data[i].id == '{{$task->customer_id }}') {
                    $('#edit_task_form_customer_id_{{$task->id}}').append("<option value='" + data[i].id + "' selected>" + data[i].customer_name + "</option>");
                } else {
                    $('#edit_task_form_customer_id_{{$task->id}}').append("<option value='" + data[i].id + "'>" + data[i].customer_name + "</option>");
                }

            }

            $('#edit_task_form_customer_id_{{$task->id}}').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function ajaxLoadContactsListByCustomer_{{$task->id}}(customer_id) {

        var $select = $('#edit_task_form_contact_id_{{$task->id}}').selectize();
        var control = $select[0].selectize;
        control.clear();
        control.clearOptions();
        control.refreshOptions();
        control.destroy();

        $.ajax({
            url: '/contacts/ajax/load-contacts-list-by-customer-id?customer_id=' + customer_id
        }).done(function (data) {
            $('#edit_task_form_contact_id_{{$task->id}}').empty();
            data = $.parseJSON(data);
            $('#edit_task_form_contact_id_{{$task->id}}').append("<option value=''>Contact</option>");

            for (var i in data) {
                if (data[i].id == '{{$task->contact_id }}') {
                    $('#edit_task_form_contact_id_{{$task->id}}').append("<option value='" + data[i].id + "' selected>" + data[i].full_name + "</option>");
                } else {
                    $('#edit_task_form_contact_id_{{$task->id}}').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                }

            }

            $('#edit_task_form_contact_id_{{$task->id}}').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function checkTaskDates_{{$task->id }}(){

        var edit_task_form_from_date_time = $("#edit_task_form_from_date_time_{{$task->id }}").val();
        var edit_task_form_to_date_time = $("#edit_task_form_to_date_time_{{$task->id }}").val();
        if(compareTime(edit_task_form_to_date_time,edit_task_form_from_date_time)){
            return;
        }else{
            $("#edit_task_form_to_date_time_{{$task->id }}").val('');
            $.notify("Task To date must after From Date.");
        }
    }


</script>