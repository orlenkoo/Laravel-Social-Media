@if(sizeof($emails))
    <table>
        <tr>
            <th>Created By</th>
            <th>Sent On</th>
            <th>Status</th>
        </tr>
        @foreach($emails as $email)
            <tr>
                <td>{{ $email->sentBy->getEmployeeFullName() }}</td>
                <td>{{ $email->sent_on }}</td>
                <td>{{ $email->status }}</td>
            </tr>
        @endforeach
    </table>
@else
    <p>Not Found.</p>
@endif