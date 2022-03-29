<p>Dear
    {{ $event360_event_planner_profile->getEventPlannerName() }},
</p>


<p>You have received a new message from
    {{ $vendor_name }}. Please <a href="{{ Config::get('project_vars.event360_website_url') }}event-planners">login</a> to Event360 to view the message.
</p>

<p>Please feel free to contact us at <a href="mailto:support@event360.asia" target="_blank">support@event360.asia</a> should you require any assistance/ support.</p>


<p>Yours Sincerely,<br>
    Event360 Team</p>




