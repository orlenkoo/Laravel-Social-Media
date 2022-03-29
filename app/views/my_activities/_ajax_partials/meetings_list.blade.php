<table>
    <tr>
        <th>Created By</th>
        <th>Customer</th>
        <th>Created</th>
        <th>Agenda</th>
        <th>Venue</th>
        <th>Summary</th>
        <th>Meeting Date</th>
        <th>Task</th>
        <th>Scheduled Start Time</th>
        <th>Scheduled End Time</th>
        <th>Meeting With</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($meetings as $meeting)
        <tr>
            <td>
                {{ $meeting->employee->given_name .' '. $meeting->employee->surname }}
            </td>
            <td>
                {{ $meeting->customer->customer_name }}
            </td>
            <td>{{ $meeting->created_datetime }}</td>
            <td>{{ $meeting->agenda }}</td>
            <td>{{ $meeting->venue }}</td>
            <td>{{ $meeting->summary }}</td>
            <td>{{ $meeting->meeting_date }}</td>
            <td>{{ $meeting->task }}</td>
            <td>{{ $meeting->scheduled_start_time }}</td>
            <td>{{ $meeting->scheduled_end_time }}</td>
            <td>{{ $meeting->meeting_person }}</td>
            <td>
                <button class="button tiny float-left" type="button" data-open="reveal_add_new_task_{{ $meeting->id }}">Create Task</button>
                <div class="reveal large reveal_add_new_task" id="reveal_add_new_task_{{ $meeting->id }}" name="reveal_add_new_task" data-reveal>
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
            </td>
            <td>
                @if(Employee::checkAuthorizedAccessMyActivities(array($meeting->assigned_to)))
                <button class="button tiny float-right" type="button" data-open="popup_edit_meeting" onclick="ajaxGetEditMeetingForm({{ $meeting->id }})">Edit</button>
                @else
                    <button class="button tiny float-right" type="button" disabled>Edit</button>
                @endif
            </td>
        </tr>
    @endforeach
</table>

<div class="panel-list-pagination" id="pagination_meetings_list">
    <?php echo $meetings->links(); ?>
</div>