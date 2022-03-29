@if($lead->lead_source == "web360_enquiries")
    @include('leads._partials.lead_meta.pixel_lead')
@elseif($lead->lead_source == "event360_calls")
    @include('leads._partials.lead_meta.call_lead')
@elseif($lead->lead_source == "novocall_leads")
    @include('leads._partials.lead_meta.novocall_lead')
@endif

<hr>

<h6>Other Details</h6>
<table>
    <tr>
        <th>UTM Source:</th><td>{{ $lead->utm_source }}</td>
    </tr>
    <tr>
        <th>UTM Medium:</th><td>{{ $lead->utm_medium }}</td>
    </tr>
    <tr>
        <th>UTM Term:</th><td>{{ $lead->utm_term }}</td>
    </tr>
    <tr>
        <th>UTM Campaign:</th><td>{{ $lead->utm_campaign }}</td>
    </tr>
    <tr>
        <th>UTM Content:</th><td>{{ $lead->utm_content }}</td>
    </tr>
    <tr>
        <th>GCLID:</th><td>{{ $lead->gclid }}</td>
    </tr>
    <tr>
        <th>FBCLID:</th><td>{{ $lead->fbclid }}</td>
    </tr>
</table>