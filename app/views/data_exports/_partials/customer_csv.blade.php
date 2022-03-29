<table>
    <tr>
        <th>ID</th>
        <th>Organization</th>
        <th>Account Owner</th>
        <th>Customer Name</th>
        <th>Industry</th>
        <th>Address Line 1</th>
        <th>Address Line 2</th>
        <th>City</th>
        <th>Postal Code</th>
        <th>State</th>
        <th>Country</th>
        <th>Phone Number</th>
        <th>Fax Number</th>
        <th>Website</th>
        <th>Business Registration Number</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Customer Tags</th>
    </tr>
    @foreach($customers as $customer)
        <tr>
            <td>{{$customer->id}}</td>
            <td>
                @if(is_object($customer->organization))
                    {{$customer->organization->organization}}
                @endif
            </td>
            <td>
                @if(is_object($customer->accountOwner))
                    {{$customer->accountOwner->getEmployeeFullName()}}
                @endif
            </td>
            <td>{{$customer->customer_name}}</td>
            <td>
                @if(is_object($customer->industry))
                    {{$customer->industry->industry}}
                @endif
            </td>
            <td>{{$customer->address_line_1}}</td>
            <td>{{$customer->address_line_2}}</td>
            <td>{{$customer->city}}</td>
            <td>{{$customer->postal_code}}</td>
            <td>{{$customer->state}}</td>
            <td>
                @if(is_object($customer->country))
                    {{$customer->country->country}}
                @endif
            </td>
            <td>{{$customer->phone_number}}</td>
            <td>{{$customer->fax_number}}</td>
            <td>{{$customer->website}}</td>
            <td>{{$customer->business_registration_number}}</td>
            <td>{{$customer->created_at}}</td>
            <td>{{$customer->updated_at}}</td>
            <td>
                {{ Customer::getCustomerTagsCommaSeparated($customer->id) }}
            </td>
        </tr>
    @endforeach
</table>