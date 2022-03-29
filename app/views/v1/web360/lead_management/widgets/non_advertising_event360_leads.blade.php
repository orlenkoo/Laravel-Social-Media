<table id="non_advertising_event360_leads_details" class="">
    <thead>
    <tr>
        <th>Date of Lead</th>
        <th>Requirement Type</th>
    </tr>
    </thead>
    <tbody>
    @if(count($leads)>0)
        @foreach($leads as $lead)
           <tr>
                <td>{{ $lead->datetime }}</td>
               <td>{{ Event360Enquiry::getRequiredServicesForTheEnquiry($lead->event360Enquiry->id) }}</td>
           </tr>
        @endforeach
    @else
        <tr>
            <td colspan="2" >
                <p class="alert-box info radius">
                    No Leads Available For the Selected Time Period.
                </p>
            </td>
        </tr>
    @endif
    </tbody>
</table>

