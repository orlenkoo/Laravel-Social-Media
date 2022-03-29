<table class="leads-list">
    <tr>
        <th>
            Lead Rating
        </th>
        <th>
            Customer Name
        </th>
        <th>
            Meeting
        </th>
        <th>
            Call
        </th>
        <th>
            Email
        </th>
        @if(OrganizationConfiguration::checkIfConfigurationDisabled(Config::get('project_vars.organization_configurations')['disable_quotations_feature_id']))
        <th>
            Quotation
        </th>
        @endif
        <th>
            View Details
        </th>
    </tr>
    @if(count($leads) > 0)
        @foreach($leads as $lead)
            <tr class="panel-list-item" id="leads_list_lead_row_{{ $lead->id }}">
                <td>
                    <span title="{{ $lead->lead_rating }}" class="panel-list-item-status-ball {{ isset($lead->lead_rating) && array_key_exists($lead->lead_rating , Lead::$lead_ratings_classes)? Lead::$lead_ratings_classes[$lead->lead_rating]: '' }}">&nbsp;</span>
                </td>
                <td>
                    @if(is_object($lead->customer))
                        {{ $lead->customer->customer_name }}
                    @else
                        New Lead - Update Customer Name
                    @endif
                </td>
                @if(is_object($lead->customer))
                    <td>
                        <img src="{{ asset('assets/img/icons/icon-dashboard-meeting.png') }}" style="width: 25px;" alt="" data-open="popup_add_new_meeting" onclick="ajaxGetAddNewMeetingForm({{ $lead->customer_id }})">
                    </td>
                    <td>
                        <img src="{{ asset('assets/img/icons/icon-dashboard-call.png') }}" style="width: 25px;" alt="" data-open="popup_add_new_call" onclick="ajaxGetAddNewCallForm({{ $lead->customer_id }})">
                    </td>
                    <td>
                        <img src="{{ asset('assets/img/icons/icon-dashboard-send-email.png') }}" style="width: 25px;" alt="" data-open="popup_add_new_email" onclick="ajaxGetAddNewEmailForm({{ $lead->customer_id }})">
                    </td>
                    @if(OrganizationConfiguration::checkIfConfigurationDisabled(Config::get('project_vars.organization_configurations')['disable_quotations_feature_id']))
                        <td>
                            <img src="{{ asset('assets/img/icons/icon-dashboard-create-quotation.png') }}" style="width: 25px;" alt="" data-open="popup_add_quotation_{{ $lead->id }}">
                            <div class="reveal large panel" id="popup_add_quotation_{{ $lead->id }}" data-reveal>
                                @include('quotations._partials.add_new_quotation_form')
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </td>
                    @else
                        <td></td>
                    @endif
                @else
                    <td colspan="4"></td>

                @endif
                <td onclick="ajaxLoadDashboardLeadDetails({{ $lead->id }})">
                    <img src="{{ asset('assets/img/icons/icon-dashboard-open.png') }}" style="width: 25px;" alt="">
                </td>

            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="7" class="text-center">No Leads Available For the Selected Time Period.</td>
        </tr>
    @endif
</table>
<div class="panel-list-pagination" id="pagination_dashboard_leads_list">
    <?php echo $leads->links(); ?>
</div>