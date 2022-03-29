<table>
    <tr>
        <th></th>
        <th>Title</th>
        <th>From</th>
        <th>to</th>
        <th>Location</th>
        <th>Assigned To</th>
        <th>Created By</th>
        <th>Created Date & time</th>
        <th>Customer / Contact</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($tasks as $task)
        <tr>
            <td style="text-align: center; vertical-align: middle;">
                @if($task->status)
                    <input type="checkbox" name="mark_as_done" id="mark_as_done" value="{{$task->id}}" onclick="" @if(Session::get('user-id') != $task->assigned_to) {{ "disabled" }} @endif>
                @else
                    <input type="checkbox" name="mark_as_done" id="mark_as_done" value="{{$task->id}}" checked="checked" onclick="ajaxUpdateMarkAsDoneStatus('{{$task->id}}')" @if(Session::get('user-id') != $task->assigned_to) {{ "disabled" }} @endif>
                @endif
            </td>
            <td>{{ $task->title }}</td>
            <td>{{ $task->from_date_time }}</td>
            <td>{{ $task->to_date_time }}</td>
            <td>{{ $task->location }}</td>
            <td>{{ is_object($task->assignedTo)? $task->assignedTo->getEmployeeFullName(): 'NA' }}</td>
            <td>{{ is_object($task->createdBy)? $task->createdBy->getEmployeeFullName(): 'NA' }}</td>
            <td>{{ $task->created_at }}</td>
            <td>{{ is_object($task->customer)? $task->customer->customer_name: 'NA' }} / {{ is_object($task->contact)? $task->contact->getContactFullName(): 'NA' }}</td>
            <td>
                <button title="Add to Calendar" class="addeventatc button tiny float-right" type="button" data-styling="none">
                    Add to Calendar
                    <span class="arrow">&nbsp;</span>
                    <span class="start">{{ $task->from_date_time }}</span>
                    <span class="end">{{ $task->to_date_time }}</span>
                    <span class="title">{{ $task->title }}</span>
                    <span class="description">{{$task->description }}</span>
                    <span class="location">{{ $task->location }}</span>
                    <span class="organizer">{{ is_object($task->createdBy)? $task->createdBy->getEmployeeFullName(): 'NA' }}</span>
                </button>
            </td>
            <td>
                <button class="button tiny float-right" type="button" data-open="reveal_edit_new_task_{{$task->id}}">View ></button>
                <div class="reveal large" id="reveal_edit_new_task_{{$task->id}}" name="reveal_edit_new_tast" data-reveal>
                    <div class="panel-content">
                        <div class="row">
                            <div class="large-12 columns">
                                @include('tasks._partials.edit_task_form')
                            </div>
                        </div>
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
</table>
<script>
    addeventatc.refresh();
</script>
