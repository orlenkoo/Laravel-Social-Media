@if(count($activities_efficiencies_array))
    <table>
        <tr>
            <th>Person</th>
            <th>Calls</th>
            <th>Meetings</th>
            <th>Call-To-Meeting Ratio</th>
        </tr>
        @foreach($activities_efficiencies_array as $activities_efficiency)
            <tr>
                <td>{{ $activities_efficiency['full_name'] }}</td>
                <td>{{ $activities_efficiency['call_count'] }}</td>
                <td>{{ $activities_efficiency['meeting_count'] }}</td>
                <td>{{ $activities_efficiency['call_meeting_ratio'] }}%</td>
            </tr>
        @endforeach
    </table>
@else
    <p class="alert-box info radius">No Calls and Meetings Available For The Given Period.</p>
@endif