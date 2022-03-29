<p>
    <strong>Date of Request:</strong> {{ $lead->datetime }}<br>
    <strong>Request ID:</strong> {{ $call_id }}
</p>

<p>Dear {{ $organization->organization }}</p>

<p>You have a new phone enquiry from Event360.</p>

<p>The call came from {{ $call->incoming_call_number }} with duration {{ $call->duration }}  and was received at {{ $call->datetime }} and was {{ $call->result }}.</p>

<p>Please <a href="https://www.web360.asia/leads" target="_blank">login</a> to web360 to view details of ths call or click
    <a href="{{ $call->recording_url }}" target="_blank">here</a> to listen to this call recording.</p>

<p>Please feel free to contact us at <a href="mailto:support@event360.asia" target="_blank">support@event360.asia</a> should you require any assistance/ support.</p>

<p>Yours Sincerely,<br>
    Event360 Team</p>