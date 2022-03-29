<table>
    <tr>
        <th>Leads</th>
        <td>{{ $roi_metrics_details['leads'] }}</td>
    </tr>
    <tr>
        <th>Lead Conversion Rate</th>
        <td>{{ $roi_metrics_details['lead_conversion_rate'] }} %</td>
    </tr>
    <tr>
        <th>Week-to-date Leads</th>
        <td>{{ $roi_metrics_details['week_to_date_leads'] }}</td>
    </tr>
    <tr>
        <th>Month-to-date Leads</th>
        <td>{{ $roi_metrics_details['month_to_date_leads'] }}</td>
    </tr>
    <tr>
        <th>All-Time Leads</th>
        <td>{{  $roi_metrics_details['all_time_leads']  }}</td>
    </tr>
    <tr>
        <th>Latency</th>
        <td>{{  $roi_metrics_details['latency']  }}</td>
    </tr>
</table>