@if(count($sales_efficiencies_array))
    <table>
        <tr>
            <th>Person</th>
            <th>Quotes($)</th>
            <th>Quotes(#)</th>
            <th>Contracts($)</th>
            <th>Contracts(#)</th>
            <th>Closing Ratio (%)</th>
            <th>Revenue / Contract</th>
        </tr>
        @foreach($sales_efficiencies_array as $sales_efficiency)
            <tr>
                <td>{{ $sales_efficiency['full_name'] }}</td>
                <td>{{ $sales_efficiency['quoted_amount'] }}</td>
                <td>{{ $sales_efficiency['quoted_count'] }}</td>
                <td>{{ $sales_efficiency['contract_amount'] }}</td>
                <td>{{ $sales_efficiency['contract_count'] }}</td>
                <td>{{ $sales_efficiency['closing_ratio'] }}</td>
                <td>{{ $sales_efficiency['revenue_contract'] }}</td>
            </tr>
        @endforeach
    </table>
@else
    <p class="alert-box info radius">No Quotations and Contracts Available For The Given Period.</p>
@endif