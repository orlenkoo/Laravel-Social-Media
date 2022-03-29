<div style="overflow: scroll;">
    <table>
        <tr>
            <th>IncomingCallNumber</th>
            <th>Number1300</th>
            <th>TransferredNumber</th>
            <th>Time</th>
            <th>Result</th>
            <th>DealerName</th>
            <th>Duration</th>
            <th>DurationOf1300</th>
            <th>Recording URL</th>
        </tr>
        @foreach($call_tracking_records as $call_tracking_record)
            <tr>
                <td>{{ $call_tracking_record->IncomingCallNumber }}</td>
                <td>{{ $call_tracking_record->Number1300 }}</td>
                <td>{{ $call_tracking_record->TransferredNumber }}</td>
                <td>{{ $call_tracking_record->Time }}</td>
                <td>{{ $call_tracking_record->Result }}</td>
                <td>{{ $call_tracking_record->DealerName }}</td>
                <td>{{ $call_tracking_record->Duration }}</td>
                <td>{{ $call_tracking_record->DurationOf1300 }}</td>
                <td>
                    @if($call_tracking_record->RecordingUrl != '')
                        <a href="https://pla.delaconcorp.com{{ $call_tracking_record->RecordingUrl }}" target="_blank" class="tiny button">Download</a>
                    @else
                        NA
                    @endif

                </td>

            </tr>
        @endforeach
    </table>
</div>




