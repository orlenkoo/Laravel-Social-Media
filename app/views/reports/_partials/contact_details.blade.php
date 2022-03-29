@if(sizeof($contacts))
    <table>
        <tr>
            <th>Given Name</th>
            <th>Surname</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Designation</th>
        </tr>
        @foreach($contacts as $contact)
            <tr>
                <td>{{ $contact->given_name }}</td>
                <td>{{ $contact->surname }}</td>
                <td>{{ $contact->phone_number }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->designation }}</td>
            </tr>
        @endforeach
    </table>
@else
    <p>Not Found.</p>
@endif