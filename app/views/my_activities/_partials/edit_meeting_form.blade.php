{{ Form::open(array('route' => 'my_activities.ajax.update_meeting', 'ajax' => 'true', 'id' => 'edit_meeting_form','autocomplete' => 'off')) }}
<input type="hidden" name="meeting_id" id="update_meeting_form_meeting_id" value="{{ $meeting->id }}">
<input type="hidden" name="customer_id" id="update_meeting_form_customer_id" value="{{ $meeting->customer_id }}">

<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Edit Meeting</h4>
        </div>
    </div>
</div>
<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="agenda">
                Agenda *
                <input type="text" name="agenda" id="update_meeting_form_agenda" value="{{ $meeting->agenda }}" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-6 columns">
            <label for="meeting_date">
                Date *
                <input type="text" name="meeting_date" id="update_meeting_form_meeting_date" class="datepicker" autocomplete="off" value="{{ $meeting->meeting_date }}" data-validation="required">
            </label>
        </div>
        <div class="large-3 columns">
            <label for="start_time">
                Start Time *
                <input type="text" name="start_time" id="update_meeting_form_start_time" class="timepicker" autocomplete="off" value="{{ $meeting->scheduled_start_time }}" data-validation="required">
            </label>
        </div>
        <div class="large-3 columns">
            <label for="end_time">
                End Time *
                <input type="text" name="end_time" id="update_meeting_form_end_time" class="timepicker" autocomplete="off" value="{{ $meeting->scheduled_end_time }}" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="meeting_person">
                Meeting Person
                <input type="text" name="meeting_person" id="update_meeting_form_meeting_person" value="{{ $meeting->meeting_person }}">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="venue">
                Venue
                <textarea name="venue" id="update_meeting_form_venue" cols="30" rows="10" >{{ $meeting->venue }}</textarea>
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="summary">
                Summary
                <textarea name="summary" id="update_meeting_form_summary" cols="30" rows="10" >{{ $meeting->summary }}</textarea>
            </label>
        </div>
    </div>

    <br>
    <br>

    <?php $meeting_details = MeetingStatusLog::getMeetingDetails($meeting->id); ?>

    <div class="row">
        <div class="large-12 columns">
            <label for="summary">
                <b>Meeting Details</b>
            </label>
            <table>
                <tr>
                    <td>Start Time - </td><td><?php if($meeting_details) echo $meeting_details['start_time']; ?></td>
                </tr>
                <tr>
                    <td>End Time - </td><td><?php if($meeting_details) echo $meeting_details['end_time']; ?></td>
                </tr>
                <tr>
                    <td>Start Address - </td><td><?php if($meeting_details) echo $meeting_details['start_address']; ?></td>
                </tr>
                <tr>
                    <td>End Address - </td><td><?php if($meeting_details) echo $meeting_details['end_address']; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="summary">
                <b>Meeting Status Update Logs</b>
            </label>
            <table>
                <tr>
                    <th>Status Updated On</th>
                    <th>Meeting Status</th>
                    <th>Time</th>
                    <th>Address</th>
                </tr>
                @foreach($meeting_status_logs as $meeting_status_log)
                    <tr>
                        <td>{{ $meeting_status_log->status_updated_on }}</td>
                        <td>{{ $meeting_status_log->status }}</td>
                        <td>{{ date('h:i:s a ', strtotime($meeting_status_log->status_updated_on)) }}</td>
                        <td>{{ $meeting_status_log->address }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <br><br>



</div>
<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-5 columns">
            &nbsp;
        </div>
        <div class="large-2 columns text-right">
            <input type="button" value="Cancel" class="button tiny alert" onclick="$('#popup_edit_meeting').foundation('close');">
        </div>
        <div class="large-3 columns text-right">
            <button class="button tiny float-left" type="button" data-open="reveal_add_new_task_edit_meeting_form_{{ $meeting->id }}">Create Task</button>
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Save', array("class" => "button success tiny", "id" => "update_meeting_form_save_button_".$meeting->id)) }}
        </div>
    </div>

    <div class="row loading_animation" style="display: none;">
        <div class="large-12 columns text-center">
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
        </div>
    </div>
</div>

<div class="reveal large reveal_add_new_task" id="reveal_add_new_task_edit_meeting_form_{{ $meeting->id }}" name="reveal_add_new_task_edit_meeting_form" data-reveal>
    <div class="panel-content">
        <div class="row">
            <div class="large-12 columns">
                @include('tasks._partials.add_new_task_form', [
                                                          'activity_type' => 'Meeting',
                                                          'activity_object' => $meeting
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
        setUpAjaxForm('edit_meeting_form', 'update','#popup_edit_email', function(){

                console.log('{{$post_data_to_load}}');

                @if($post_data_to_load == 'dashboard_lead_time_line')
                    ajaxLoadDashboardLeadTimeLine(1);
                @elseif($post_data_to_load == 'my_activities')
                    var pagination_page  = $("#pagination_meetings_list").find('li.active > span').html();
                    ajaxLoadMeetingsList(pagination_page);
                @elseif($post_data_to_load == 'customer-activity-list')
                    var pagination_page  = $("#pagination_meetings_list").find('li.active > span').html();
                    ajaxLoadCustomerMeetings(pagination_page);
                @endif
            }
        );
    });
</script>