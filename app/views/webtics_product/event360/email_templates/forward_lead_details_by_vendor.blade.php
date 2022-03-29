<p>{{ $lead_forward->leadForwardedBy->getEmployeeFullName() }} has shared Event360 Lead details with you. Please check below.</p>

@if($lead->lead_source == "event360_enquiries")
    <h2>Event360 Enquiry Details</h2>

    @include('v1.web360.lead_management._partials.enquiry_details')

@endif

@if($lead->lead_source == "web360_enquiries")
    <h2>Website Enquiry Details</h2>

    @include('v1.web360.lead_management._partials.web360_enquiry_details')

@endif

@if($lead->lead_source == "event360_calls")
    <h2>Event360 Call Details</h2>

    @include('webtics_product.third_party_api.delacon_call_tracking.call_details')

@endif

<p>Thank You <br>
Event 360 Team.
</p>