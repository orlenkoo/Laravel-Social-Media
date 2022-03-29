<div class="table-scroll">

    <table>
        <tr>
            <th>On</th>
            <th>By</th>
            <th>To</th>
            <th>Message</th>
        </tr>
        @foreach($lead_forwards as $lead_forward)
            <tr>
                <td>{{ $lead_forward->lead_forwarded_on }}</td>
                <td>
                    @if(is_object($lead_forward->leadForwardedBy))
                        {{ $lead_forward->leadForwardedBy->getEmployeeFullName() }}
                    @else
                        NA
                    @endif
                </td>
                <td>{{ $lead_forward->email }}</td>
                <td>{{ $lead_forward->message }}</td>
            </tr>
    @endforeach
    </table>
</div>
