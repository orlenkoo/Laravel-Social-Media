<table>
    <thead>
    <tr>
        <th>S.no</th>
        <th>Campaign</th>
        <th>Content</th>
        <th>Start/End</th>
        <th>Cost</th>
        <th>Lead</th>
        <th>Quotes</th>
        <th>Contracts #</th>
        <th>Contracts $</th>
        <th>ROI</th>
    </tr>
    </thead>
    <tbody>
    @foreach($campaign_performance_data as $campaign_performance)
        <tr>
            <td>{{ $campaign_performance['s.no'] }}</td>
            <td>{{ $campaign_performance['campaign_name'] }}</td>
            <td>{{ $campaign_performance['campaign_content'] }}</td>
            <td>{{ $campaign_performance['start_end_date'] }}</td>
            <td>{{ $campaign_performance['cost'] }}</td>
            <td>{{ $campaign_performance['lead_count'] }}</td>
            <td>{{ $campaign_performance['quotes_count'] }}</td>
            <td>{{ $campaign_performance['contract_count'] }}</td>
            <td>${{ $campaign_performance['contract_amount'] }}</td>
            <td>{{ $campaign_performance['roi'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>