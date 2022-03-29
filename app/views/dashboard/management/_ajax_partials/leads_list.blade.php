<table class="leads-list">
    <thead>
    <tr>
        <th>Lead Rating</th>
        <th>Date Time</th>
        <th>Lead Source</th>
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
        @else
            <th></th>
        @endif
        <th>
            View Details
        </th>
    </tr>
    </thead>
    <tbody style="max-height: 300px; overflow-y: scroll;">
    @if(count($leads) > 0)
        @foreach($leads as $lead)
            <tr class="panel-list-item" id="leads_list_lead_row_{{ $lead->id }}">
                <td>
                    <span title="{{ $lead->lead_rating }}" class="panel-list-item-status-ball {{ isset($lead->lead_rating) && array_key_exists($lead->lead_rating , Lead::$lead_ratings_classes)? Lead::$lead_ratings_classes[$lead->lead_rating]: '' }}">&nbsp;</span>
                </td>
                <td>
                    {{ $lead->datetime }}
                </td>
                <td>
                    {{ array_key_exists($lead->lead_source, Lead::$lead_sources)? Lead::$lead_sources[$lead->lead_source]: "NA"  }}
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

            @if(!is_object($lead->customer))
                <tr class="lead-meta-summary">
                    <td><strong>New Lead Summary:</strong></td>
                    <td colspan="8">
                        @if($lead->lead_source == "web360_enquiries")
                            <?php $enquiry_details = json_decode($lead->web360Enquiry->enquiry_details); ?>
                            @if (is_object($enquiry_details))


                                <?php $i = 0; ?>
                                @foreach($enquiry_details as $key => $value)
                                    @if ($i < 4)
                                        {{ ucwords(str_replace('_', ' ', $key)) }} : {{ is_array($value)? implode(",", $value) : StringUtilities::trimText($value, 100, '...') }} |
                                    @endif
                                    <?php $i++; ?>
                                @endforeach

                            @else
                                    <p>{{ StringUtilities::trimText($enquiry_details, 100, '...') }}</p>
                            @endif
                        @elseif($lead->lead_source == "event360_calls")
                            <?php $event360_call = $lead->event360Call ?>
                            @if($event360_call->recording_url != '')
                                <a href="{{ $event360_call->recording_url }}" target="_blank"
                                   class="tiny button">Listen To Call</a>
                            @else
                                NA
                            @endif
                        @endif
                    </td>
                </tr>
            @endif

        @endforeach
    @else
        <tr>
            <td colspan="9" class="text-center">No Leads Available For the Selected Time Period.</td>
        </tr>
    @endif
    </tbody>
</table>
<div class="panel-list-pagination" id="pagination_dashboard_leads_list">
    <?php echo $leads->links(); ?>
</div>