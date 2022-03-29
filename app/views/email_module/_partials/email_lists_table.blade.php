<table>
    <tr>
        <th>Email List Name</th>
        <th>No Of Contacts</th>
        <th>Created Date</th>
        <th>View</th>
        <th>Select</th>
    </tr>
    @foreach($email_lists as $email_list)
        <tr>
            <td>{{ $email_list->email_list_name }}</td>
            <td>{{ count($email_list->emailModuleEmailListContactAssignments) }}</td>
            <td>{{ $email_list->created_at }}</td>
            <td>
                <input type="button" class="tiny button" value="View >" data-open="popup_edit_email_list_form_{{ $email_list->id }}" onclick="ajaxLoadSelectedContactsList_{{ $email_list->id }}(1)">
                <div class="large panel reveal" id="popup_edit_email_list_form_{{ $email_list->id }}" data-reveal>
                    @include('email_module._partials.edit_email_list_form')
                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </td>
            <td><input type="radio" name="selected_email_list" value="{{ $email_list->id }}" onchange="updateSelectedEmailListForEmailCampaign(this.value)" {{ $selected_email_id == $email_list->id? "checked": "" }}></td>
        </tr>
    @endforeach
</table>

<script>
    function updateSelectedEmailListForEmailCampaign(email_list_id) {
        console.log(email_list_id);
        var email_campaign_id = $('#email_campaign_id').val();

        ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', email_campaign_id, 'email_module_email_list_id', email_list_id, '', 'EmailModuleEmailCampaign');

        ajaxLoadEmailLists(1);
    }
</script>