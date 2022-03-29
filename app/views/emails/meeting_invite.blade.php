<p>Dear <strong>{{ $meeting_invitee->employee->given_name }} {{ $meeting_invitee->employee->surname }},</strong></p>

<p>You have been invited to the following meeting by {{ $activity_log->employee->given_name }} {{ $activity_log->employee->surname }}, Please check the details below and accept it.</p>

<h2>Activity Details</h2>

<h3>Customer Name - {{ $activity_log->customer->account_name }}</h3>

<table>
    <tr><th>Activity Purpose</th><td>{{ $activity_log->activity_purpose }}</td></tr>
    <tr><th>Agenda</th><td>{{ $activity_log->agenda }}</td></tr>
    <tr><th>Date of Activity</th><td>{{ $activity_log->date_of_activity }}</td></tr>
    <tr><th>Task</th><td>{{ $activity_log->task }}</td></tr>
    <tr><th>Scheduled Time</th><td>{{ $activity_log->scheduled_start_time }} to {{ $activity_log->scheduled_end_time }}</td></tr>
    <tr><th>Venue</th><td>{{ $activity_log->venue }}</td></tr>
    <tr><th>Meeting Person (Who your going to meet)</th><td>{{ $activity_log->meeting_person }}</td></tr>
</table>

<p>
    <?php
    $url_accept = route('meeting_attendee.status.update', array('meeting_attendee_id' => $meeting_invitee->id, 'status' => 'Accepted', 'meeting_inviter_employee_id' => $activity_log->employee->id ));
    $url_reject = route('meeting_attendee.status.update', array('meeting_attendee_id' => $meeting_invitee->id, 'status' => 'Declined', 'meeting_inviter_employee_id' => $activity_log->employee->id ));

    ?>
    <a href="{{ $url_accept }}" style="font-size: 14px; color: #fff; background: #117700; padding: 10px; margin: 10px;">Accept</a>
    <a href="{{ $url_reject }}" style="font-size: 14px; color: #fff; background: #aa1111; padding: 10px; margin: 10px;">Decline</a>
</p>


<p>Thank You.</p>