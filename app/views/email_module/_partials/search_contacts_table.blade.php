<table>
    <tr>
        <th><input type="checkbox" name="select_all_contacts_search_list_<?php echo $unique_id; ?>" id="select_all_contacts_search_list_<?php echo $unique_id; ?>"></th>
        <th>Customer</th>
        <th>Given Name</th>
        <th>Surname</th>
        <th>Email</th>
        <th>Phone Number</th>
    </tr>
    @foreach($contacts as $contact)
        <tr>
            <td><input type="checkbox" name="contacts_search_list_contact_<?php echo $unique_id; ?>[]" class="contacts_search_list_contact_<?php echo $unique_id; ?>" value='{{ $contact->id }}'></td>
            <td>{{ $contact->customer->customer_name }}</td>
            <td>{{ $contact->given_name }}</td>
            <td>{{ $contact->surname }}</td>
            <td>{{ $contact->email }}</td>
            <td>{{ $contact->phone_number }}</td>
        </tr>
    @endforeach
</table>
<div class="row">
    <div class="large-12 columns">
        <input type="button" id="btn_add_to_selected_contacts_<?php echo $unique_id; ?>" value="Add Contacts To Email List" class="button tiny success float-right">
    </div>
</div>




<script>
    $("#select_all_contacts_search_list_<?php echo $unique_id; ?>").change(function () {
        $(".contacts_search_list_contact_<?php echo $unique_id; ?>").prop('checked', $(this).prop("checked"));
    });

    $(function(){
        $('#btn_add_to_selected_contacts_<?php echo $unique_id; ?>').click(function(){

            var selected_contacts_ids = JSON.parse($('#<?php echo $selected_contents_list_id; ?>').val());

            if(!selected_contacts_ids) {
                selected_contacts_ids = new Array();
            }

            console.log('selected_contacts_ids -- before -- ' + selected_contacts_ids);

            var val = [];
            $('.contacts_search_list_contact_<?php echo $unique_id; ?>:checkbox:checked').each(function(i){
                val[i] = $(this).val();
            });
            console.log(val);

            selected_contacts_ids = selected_contacts_ids.concat(val);

            console.log('selected_contacts_ids -- middle -- ' + selected_contacts_ids);

            selected_contacts_ids = removeDuplicatesFromArray(selected_contacts_ids);

            console.log('selected_contacts_ids -- after -- ' + selected_contacts_ids);

            $('#<?php echo $selected_contents_list_id; ?>').val(JSON.stringify(selected_contacts_ids));

            <?php echo $selected_contents_reload_function.'(1);'; ?>

        });
    });
</script>