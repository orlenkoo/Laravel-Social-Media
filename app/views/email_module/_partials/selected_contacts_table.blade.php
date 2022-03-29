<table>
    <tr>
        <th><input type="checkbox" name="select_all_selected_contacts_list_<?php echo $unique_id; ?>" id="select_all_selected_contacts_list_<?php echo $unique_id; ?>"></th>
        <th>Customer</th>
        <th>Given Name</th>
        <th>Surname</th>
        <th>Email</th>
        <th>Phone Number</th>
    </tr>
    @foreach($contacts as $contact)
        <tr>
            <td><input type="checkbox" name="selected_contacts_list_contact_<?php echo $unique_id; ?>[]" class="selected_contacts_list_contact_<?php echo $unique_id; ?>" value='{{ $contact->id }}'></td>
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
        <input type="button" id="btn_remove_contacts_form_email_list_<?php echo $unique_id; ?>" value="Remove Contacts From Email List" class="button tiny alert float-right">
    </div>
</div>




<script>
    $("#select_all_selected_contacts_list_<?php echo $unique_id; ?>").change(function () {
        $(".selected_contacts_list_contact_<?php echo $unique_id; ?>").prop('checked', $(this).prop("checked"));
    });

    $(function(){
        $('#btn_remove_contacts_form_email_list_<?php echo $unique_id; ?>').click(function(){

            var remove_contacts = [];
            $('.selected_contacts_list_contact_<?php echo $unique_id; ?>:checkbox:checked').each(function(i){
                remove_contacts[i] = $(this).val();
            });

            console.log(remove_contacts);

            var selected_contacts_ids = [];
            $('.selected_contacts_list_contact_<?php echo $unique_id; ?>:checkbox').each(function(i){
                selected_contacts_ids[i] = $(this).val();
            });

            console.log(selected_contacts_ids);

            var new_selected_contacts = $(selected_contacts_ids).not(remove_contacts).get();

            $('#<?php echo $selected_contents_list_id; ?>').val(JSON.stringify(new_selected_contacts));

            <?php echo $selected_contents_reload_function.'(1);'; ?>

        });
    });
</script>