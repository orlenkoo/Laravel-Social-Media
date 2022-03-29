<table>
    <tr>
        <th>Customer Name</th>
        <th>Address</th>
        <th>Phone Number</th>
        <th>Fax Number</th>
        <th>Website</th>
        <th>Tags</th>
        <th>View</th>
    </tr>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->customer_name }}</td>
            <td>{{ $customer->getAddress() }}</td>
            <td>{{ $customer->phone_number }}</td>
            <td>{{ $customer->fax_number }}</td>
            <td>{{ $customer->website }}</td>
            <td>

                <select name="customer_tags" id="customer_tags_{{$customer->id}}" class="customer_tags" onchange="assignCustomerTags('customer_tags_{{$customer->id}}','{{$customer->id}}')" multiple>
                    <?php
                    $assigned_tags = CustomersController::getTagsForCustomer($customer->id);
                    foreach($assigned_tags as $assigned_tag){
                        $id = $assigned_tag['id'];
                        $tag = $assigned_tag['tag'];
                        $assigned = $assigned_tag['assigned'];
                        $selected = '';
                        $selected = ($assigned)? 'selected' : '';
                        echo "<option $selected value='$id'>$tag</option>";
                    }
                    ?>
                </select>

            </td>
            <td>
                <a href="{{ route('customers.view', array('customer_id' => $customer->id)) }}" target="_blank" class="button tiny">
                    View >
                </a>
            </td>
        </tr>
    @endforeach
</table>