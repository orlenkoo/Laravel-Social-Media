<table>
    <tr>
        <th>Incoming Call Number</th>
        <th>Tracking Number</th>
        <th>Termination Number</th>
        {{--<th>Source</th>--}}
        <th>Date & Time</th>
        <th>Result</th>
        <th>Vendor Name / Source</th>
        <th>Call Duration</th>
        <th>Total Duration</th>
        <th>Call Play Back</th>
        <th>Recording URL</th>
    </tr>

        <?php $event360_call = $lead->event360Call ?>
        <tr>
            <td>{{ $event360_call->incoming_call_number }}</td>
            <td>{{ $event360_call->number1300 }}</td>
            <td>{{ $event360_call->transferred_number }}</td>
            {{--<td>{{ $event360_call->keyword }}</td>--}}
            <td>{{ $event360_call->time }}</td>
            <td>{{ $event360_call->result }}</td>
            <td>{{ $event360_call->dealer_name }}</td>
            <td>{{ $event360_call->duration }}</td>
            <td>{{ $event360_call->durationof1300 }}</td>
            <td></td>
            <td>
                @if($event360_call->recording_url != '')
                    <a href="https://pla.delaconcorp.com{{ $event360_call->recording_url }}" target="_blank"
                       class="tiny button">Download</a>
                @else
                    NA
                @endif

            </td>

        </tr>

</table>