{{ Form::open(array('route' => 'my_activities.ajax.save_new_call', 'ajax' => 'true', 'id' => 'add_new_call_form','autocomplete' => 'off')) }}
<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Add New Call</h4>
        </div>
    </div>
</div>

<input type="hidden" name="customer_id" id="new_call_form_customer_id" value="{{ $customer_id }}">

<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="agenda">
                Agenda *
                <input type="text" name="agenda" id="new_call_form_agenda" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-6 columns">
            <label for="call_date">
                Date *
                <input type="text" name="call_date" id="new_call_form_call_date" class="datepicker" autocomplete="off" value="" data-validation="required">
            </label>
        </div>
        <div class="large-3 columns">
            <label for="start_time">
                Start Time *
                <input type="text" name="start_time" id="new_call_form_start_time" class="timepicker" autocomplete="off" data-validation="required">
            </label>
        </div>
        <div class="large-3 columns">
            <label for="end_time">
                End Time *
                <input type="text" name="end_time" id="new_call_form_end_time" class="timepicker" autocomplete="off" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="call_with">
                Call With
                <input type="text" name="call_with" id="new_call_form_call_with" value="{{ $contacts }}">
            </label>
        </div>
    </div>


    <div class="row">
        <div class="large-12 columns">
            <label for="summary">
                Summary
                <textarea name="summary" id="new_call_form_summary" cols="30" rows="10"></textarea>
            </label>
        </div>
    </div>


    <div class="row">
        <div class="large-12 columns">
            <legend>Create Task</legend>
            <input type="checkbox" name="create_task" value="1" id="create_task">
        </div>
    </div>


</div>

<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns text-right">
            <input type="button" value="Cancel" class="button tiny alert" onclick="$('#popup_add_new_call').foundation('close');">
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Save', array("class" => "button success tiny")) }}
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
        setUpAjaxForm('add_new_call_form', 'create', '#popup_add_new_call',
            function(){
                clearNewCallForm();
                @if($post_data_to_load == 'dashboard_lead_time_line')
                    ajaxLoadDashboardLeadTimeLine(1);
                @else
                    ajaxLoadCustomerCalls(1);
                @endif
            }
        );
    });

    function clearNewCallForm() {
        document.getElementById("add_new_call_form").reset();
    }

    $('#new_call_form_call_with').selectize({
        delimiter: ', ',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
</script>