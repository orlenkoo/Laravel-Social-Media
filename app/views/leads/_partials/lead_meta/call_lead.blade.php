<?php $event360_call = $lead->event360Call ?>
@if(is_object($event360_call))
    <table>
        <tr>
            <th>Recording URL</th>
            <td>
                @if($event360_call->recording_url != '')
                    <a href="{{ $event360_call->recording_url }}" target="_blank"
                       class="tiny button">Listen</a>
                @else
                    NA
                @endif

            </td>
        </tr>
        <tr>
            <th>Incoming Call Number</th>
            <td>{{ $event360_call->incoming_call_number }}</td>
        </tr>
        <tr>
            <th>Tracking Number</th>
            <td>{{ $event360_call->number1300 }}</td>
        </tr>
        <tr>
            <th>Termination Number</th>
            <td>{{ $event360_call->transferred_number }}</td>
        </tr>
        <tr>
            <th>Date & Time</th>
            <td>{{ $event360_call->time }}</td>
        </tr>
        <tr>
            <th>Result</th>
            <td>{{ $event360_call->result }}</td>
        </tr>
        <tr>
            <th>Vendor Name / Source</th>
            <td>{{ $event360_call->dealer_name }}</td>
        </tr>
        <tr>
            <th>Call Duration</th>
            <td>{{ $event360_call->duration }}</td>
        </tr>
        <tr>
            <th>Total Duration</th>
            <td>{{ $event360_call->durationof1300 }}</td>
        </tr>
    </table>
@else
    <p>Not Found.</p>
@endif