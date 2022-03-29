<table>
    <tr>
        <th>Total Visits</th>
        <td>{{ $engagement_metric_report_details['total_visits'] }}</td>
    </tr>
    <tr>
        <th>Unique Visits</th>
        <td>{{ $engagement_metric_report_details['unique_visitor'] }}</td>
    </tr>
    {{-- Removed upon Julies request 1/12/2016--}}
    {{--<tr>--}}
        {{--<th>Bounce Rate</th>--}}
        {{--<td>{{ $engagement_metric_report_details['bounce_rate'] }}</td>--}}
    {{--</tr>--}}
    <tr>
        <th>Average Time Spent Per User (mins)</th>
        <td>{{ $engagement_metric_report_details['time_spent'] }}</td>
    </tr>
</table>