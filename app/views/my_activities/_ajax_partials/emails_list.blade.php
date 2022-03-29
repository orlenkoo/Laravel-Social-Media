<table>
    <tr>
        <th>Created By</th>
        <th>Customer</th>
        <th>To</th>
        <th>CC</th>
        <th>BCC</th>
        <th>Subject</th>
        <th>Sent On</th>
        <th>Status</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($emails as $email)
        <tr>
            <td>
                {{ $email->sentBy->given_name .' '. $email->sentBy->surname }}
            </td>
            <td>
                {{ $email->customer->customer_name }}
            </td>
            <td>{{ $email->to }}</td>
            <td>{{ $email->cc }}</td>
            <td>{{ $email->bcc }}</td>
            <td>{{ $email->subject }}</td>
            <td>{{ $email->sent_on }}</td>
            <td>{{ $email->status }}</td>
            <td>
                <button class="button tiny float-left" type="button" data-open="reveal_add_new_task_{{ $email->id }}">Create Task</button>
                <div class="reveal large reveal_add_new_task" id="reveal_add_new_task_{{ $email->id }}" name="reveal_add_new_task" data-reveal>
                    <div class="panel-content">
                        <div class="row">
                            <div class="large-12 columns">
                                @include('tasks._partials.add_new_task_form', [
                                                                  'activity_type' => 'Email',
                                                                  'activity_object' => $email
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
                @if(Employee::checkAuthorizedAccessMyActivities(array($email->sent_by_id)))
                    <button class="button tiny float-right" type="button" data-open="popup_edit_email" onclick="ajaxGetEditEmailForm({{ $email->id }})">Edit</button>
                @else
                    <button class="button tiny float-right" type="button" disabled>Edit</button>
                @endif
            </td>
        </tr>
    @endforeach
</table>

<div class="panel-list-pagination" id="pagination_emails_list">
    <?php echo $emails->links(); ?>
</div>