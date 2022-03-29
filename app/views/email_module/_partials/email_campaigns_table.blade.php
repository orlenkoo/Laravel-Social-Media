<table>
    <tr>
        <th>Campaign Name</th>
        <th>Email List</th>
        <th>Start Date Time</th>
        <th>End Date Time</th>
        <th>Status</th>
        <th></th>
    </tr>
    @foreach($email_campaigns as $email_campaign)
        <tr>
            <td><input type="text" value="{{ $email_campaign->campaign_name }}" name="campaign_name" id="campaign_name_{{ $email_campaign->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'campaign_name', this.value, 'campaign_name_{{ $email_campaign->id }}', 'EmailModuleEmailCampaign');"></td>
            <td>{{ is_object($email_campaign->emailModuleEmailList)? $email_campaign->emailModuleEmailList->email_list_name: "NA" }}</td>
            <td><input type="text" name="start_date_time" id="start_date_time_{{ $email_campaign->id }}" value="{{ $email_campaign->start_date_time }}" class="datetimepicker" onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'start_date_time', this.value, 'start_date_time_{{ $email_campaign->id }}', 'EmailModuleEmailCampaign');"></td>
            <td><input type="text" name="end_date_time" id="end_date_time_{{ $email_campaign->id }}" value="{{ $email_campaign->end_date_time }}" class="datetimepicker" onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'end_date_time', this.value, 'end_date_time_{{ $email_campaign->id }}', 'EmailModuleEmailCampaign');"></td>
            <td>
                <select name="status" id="status_{{ $email_campaign->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('email_module_email_campaigns', '{{ $email_campaign->id }}', 'status', this.value, 'status_{{ $email_campaign->id }}' , 'EmailModuleEmailCampaign');">
                    @foreach(EmailModuleEmailCampaign::$status as $key => $value)
                        <option value="{{ $key }}" {{ $key == $email_campaign->status? "selected": "" }}>{{ $value }}</option>
                    @endforeach
                </select>
            </td>
            <td>{{ link_to_route('email_module.email_campaign_management_screen', "View", ['email_campaign_id' => $email_campaign->id], ['target' => '_blank', 'class' => 'button tiny warning']) }}</td>
        </tr>
    @endforeach
</table>


