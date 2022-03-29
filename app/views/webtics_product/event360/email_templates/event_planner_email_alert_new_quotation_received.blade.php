<p><strong>Request ID:</strong> {{ $event360_enquiry->id }} <br>
    <strong>Date of Request:</strong> {{ $event360_enquiry->created_at }}</p>


<p>Dear
    {{ $event360_enquiry->event360EventPlannerProfile->getEventPlannerName() }},
</p>


<p>
    {{ $vendor_name }} has responded to your request for quotation for your requirement<!-- for
        <main service category that the vendor has quoted for>. -->
</p>

<p>Please <a href="{{ Config::get('project_vars.event360_website_url') }}event-planners">login</a> to Event360 to review this quote.</p>

<p>Please feel free to contact us at <a href="mailto:support@event360.asia" target="_blank">support@event360.asia</a> should you require any assistance/ support.</p>


<p>Yours Sincerely,<br>
    Event360 Team</p>