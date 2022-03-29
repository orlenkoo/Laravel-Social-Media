<table>
    <tr>
        <th>ID</th>
        <th>Organization</th>
        <th>Customer</th>
        <th>Campaign</th>
        <th>Assigned To</th>
        <th>Last Assignment Datetime</th>
        <th>Datetime</th>
        <th>Lead Capture Method</th>
        <th>Source</th>
        <th>Status Updated Datetime</th>
        <th>Status Updated By</th>
        <th>Lead Rating</th>
        <th>lead Rating Last Updated Datetime</th>
        <th>lead Rating Last Updated By</th>
        <th>UTM Source</th>
        <th>UTM Medium</th>
        <th>UTM Term</th>
        <th>UTM Campaign</th>
        <th>UTM Content</th>
        <th>Source URL</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Lead Details</th>
    </tr>
    @foreach($leads as $lead)
        <tr>
            <td>{{$lead->id}}</td>
            <td>{{$lead->organization->organization}}</td>
            <td>
                @if(is_object($lead->customer))
                    {{$lead->customer->customer_name}}
                @endif
            </td>
            <td>
                @if(is_object($lead->campaign))
                    {{$lead->campaign->campaign_name}}
                @endif
            </td>
            <td>
                @if(is_object($lead->assignedTo))
                    {{$lead->assignedTo->getEmployeeFullName()}}
                @endif
            </td>
            <td>{{$lead->last_assignment_datetime}}</td>
            <td>{{$lead->datetime}}</td>
            <td>{{$lead->lead_capture_method}}</td>
            <td>{{$lead->status}}</td>
            <td>{{$lead->status_updated_datetime}}</td>
            <td>
                @if(is_object($lead->statusUpdatedBy))
                    {{$lead->statusUpdatedBy->getEmployeeFullName()}}
                @endif
            </td>
            <td>{{$lead->lead_rating}}</td>
            <td>{{$lead->lead_rating_last_updated_datetime}}</td>
            <td>
                @if(is_object($lead->leadRatingUpdatedBy))
                    {{$lead->leadRatingUpdatedBy->getEmployeeFullName()}}
                @endif
            </td>
            <td>{{$lead->utm_source}}</td>
            <td>{{$lead->utm_medium}}</td>
            <td>{{$lead->utm_term}}</td>
            <td>{{$lead->utm_campaign}}</td>
            <td>{{$lead->utm_content}}</td>
            <td>{{$lead->source_url}}</td>
            <td>{{$lead->created_at}}</td>
            <td>{{$lead->updated_at}}</td>
            <td>
                {{ Lead::getLeadDetailsCommaSeparated($lead) }}
            </td>
        </tr>
    @endforeach
</table>