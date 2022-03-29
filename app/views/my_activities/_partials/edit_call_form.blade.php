{{ Form::open(array('route' => 'my_activities.ajax.update_call', 'ajax' => 'true', 'id' => 'edit_call_form','autocomplete' => 'off')) }}
<input type="hidden" name="call_id" id="edit_call_form_call_id" value="{{ $call->id }}">
<input type="hidden" name="customer_id" id="edit_call_form_customer_id" value="{{ $call->customer_id }}">

<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Edit Call</h4>
        </div>
    </div>
</div>
<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="agenda">
                Agenda *
                <input type="text" name="agenda" id="edit_call_form_agenda" value="{{ $call->agenda }}" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-6 columns">
            <label for="call_date">
                Date *
                <input type="text" name="call_date" id="edit_call_form_call_date" class="datepicker" autocomplete="off" value="{{ $call->call_date }}" data-validation="required">
            </label>
        </div>
        <div class="large-3 columns">
            <label for="start_time">
                Start Time *
                <input type="text" name="start_time" id="edit_call_form_start_time" class="timepicker" autocomplete="off" value="{{ $call->scheduled_start_time }}" data-validation="required">
            </label>
        </div>
        <div class="large-3 columns">
            <label for="end_time">
                End Time *
                <input type="text" name="end_time" id="edit_call_form_end_time" class="timepicker" autocomplete="off" value="{{ $call->scheduled_end_time }}" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="call_with">
                Call With
                <input type="text" name="call_with" id="edit_call_form_call_with" value="{{ $call->call_with }}">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="summary">
                Summary
                <textarea name="summary" id="edit_call_form_summary" cols="30" rows="10">{{ $call->summary }}</textarea>
            </label>
        </div>
    </div>
</div>

<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-5 columns">
            &nbsp;
        </div>
        <div class="large-2 columns">
            <input type="button" value="Cancel" class="button tiny alert" onclick="$('#popup_edit_call').foundation('close');">
        </div>
        <div class="large-3 columns">
            <button class="button tiny float-left" type="button" data-open="reveal_add_new_task_edit_call_form_{{ $call->id }}">Create Task</button>
        </div>
        <div class="large-2 columns">
            {{ Form::submit('Save', array("class" => "button success tiny", "id" => "edit_call_form_save_button_".$call->id)) }}
        </div>
    </div>

    <div class="row loading_animation" style="display: none;">
        <div class="large-12 columns text-center">
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
        </div>
    </div>
</div>

<div class="reveal large reveal_add_new_task" id="reveal_add_new_task_edit_call_form_{{ $call->id }}" name="reveal_add_new_task_edit_call_form" data-reveal>
    <div class="panel-content">
        <div class="row">
            <div class="large-12 columns">
                @include('tasks._partials.add_new_task_form', [
                    'activity_type' => 'Call',
                    'activity_object' => $call
                    ])
            </div>
        </div>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

{{ Form::close() }}

<script>
    $(document).foundation();
    $(document).ready(function() {
        setUpAjaxForm('edit_call_form', 'create', '#popup_edit_call',
                function(){

                    @if($post_data_to_load == 'dashboard_lead_time_line')
                        ajaxLoadDashboardLeadTimeLine(1);
                    @elseif($post_data_to_load == 'my_activities')
                        var pagination_page  = $("#pagination_calls_list").find('li.active > span').html();
                        ajaxLoadCallsList(pagination_page);
                    @elseif($post_data_to_load == 'customer-activity-list')
                        var pagination_page  = $("#pagination_calls_list").find('li.active > span').html();
                        ajaxLoadCustomerCalls(pagination_page);
                    @endif

                }
        );
    });
</script>