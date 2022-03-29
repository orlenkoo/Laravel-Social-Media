@if(sizeof($meetings))
    <table>
        <tr>
            <th>Created By</th>
            <th>Created On</th>
            <th>Meeting Date</th>
        </tr>
        @foreach($meetings as $meeting)
            <tr>
                <td>{{ $meeting->employee->getEmployeeFullName() }}</td>
                <td>{{ $meeting->created_datetime }}</td>
                <td>{{ $meeting->meeting_date }}</td>
            </tr>
        @endforeach
    </table>
@else
    <p>Not Found.</p>
@endif