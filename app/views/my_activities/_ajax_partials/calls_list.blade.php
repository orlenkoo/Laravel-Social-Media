<table>
    <tr>
        <th>Created By</th>
        <th>Customer</th>
        <th>Created</th>
        <th>Agenda</th>
        <th>Summary</th>
        <th>Call Date</th>
        <th>Task</th>
        <th>Scheduled Start Time</th>
        <th>Scheduled End Time</th>
        <th>Call With</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($calls as $call)
        <tr>
            <td>
             {{ $call->employee->given_name .' '. $call->employee->surname }}
            </td>
            <td>
                {{ $call->customer->customer_name }}
            </td>
            <td>{{ $call->created_datetime }}</td>
            <td>{{ $call->agenda }}</td>
            <td>{{ $call->summary }}</td>
            <td>{{ $call->call_date }}</td>
            <td>{{ $call->task }}</td>
            <td>{{ $call->scheduled_start_time }}</td>
            <td>{{ $call->scheduled_end_time }}</td>
            <td>{{ $call->call_with }}</td>
            <td>
                <button class="button tiny float-left" type="button" data-open="reveal_add_new_task_{{ $call->id }}">Create Task</button>
                <div class="reveal large reveal_add_new_task" id="reveal_add_new_task_{{ $call->id }}" name="reveal_add_new_task" data-reveal>
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
            </td>
            <td>
                @if(Employee::checkAuthorizedAccessMyActivities(array($call->assigned_to)))
                    <button class="button tiny float-right" type="button" data-open="popup_edit_call" onclick="ajaxGetEditCallForm({{ $call->id }})">Edit</button>
                @else
                    <button class="button tiny float-right" type="button" disabled>Edit</button>
                @endif
            </td>
        </tr>
    @endforeach
</table>

<div class="panel-list-pagination" id="pagination_calls_list">
    <?php echo $calls->links(); ?>
</div>