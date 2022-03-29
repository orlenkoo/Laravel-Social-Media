@if(sizeof($calls))
    <table>
        <tr>
            <th>Created By</th>
            <th>Created On</th>
            <th>Call Date</th>
        </tr>
        @foreach($calls as $call)
            <tr>
                <td>{{ $call->employee->getEmployeeFullName() }}</td>
                <td>{{ $call->created_datetime }}</td>
                <td>{{ $call->call_date }}</td>
            </tr>
        @endforeach
    </table>
@else
    <p>Not Found.</p>
@endif