<p>
    Dear {{ $task->assignedTo->getEmployeeFullName() }},
</p>

<p>This is a reminder for the [{{ $task->title }}] set on [{{ $task->created_at }}]. Please find the details below.</p>

<ul>
    <li>On: [{{ $task->from_date_time }} to {{ $task->to_date_time }}]</li>
    <li>Venue: [{{ $task->location }}]</li>
</ul>

<h5>Details</h5>
{{ $task->description }}

<p>
    Regards,<br>
    Web360
</p>
<em>[This is an automatically generated email for a reminder set on a task on Web360.]</em>