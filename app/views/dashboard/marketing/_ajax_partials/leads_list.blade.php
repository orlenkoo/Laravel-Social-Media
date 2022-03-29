<table class="leads-list">
    <tr>
        <th>Lead Rating</th>
        <th>Date Time</th>
        <th>Lead Source</th>
        <th>Customer Name</th>
        <th>Lead Rating</th>
        <th>View Details</th>
    </tr>
    @if(count($leads) > 0)
        @foreach($leads as $lead)
            <tr class="panel-list-item" id="leads_list_lead_row_{{ $lead->id }}">
                <td>
                    <span title="{{ $lead->lead_rating }}" class="panel-list-item-status-ball  {{ isset($lead->lead_rating)? Lead::$lead_ratings_classes[$lead->lead_rating]: '' }}">&nbsp;</span>
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
                        New Lead - Assign Customer
                    @endif
                </td>
                <td>
                    <select name="lead_rating" id="lead_rating" onchange="ajaxUpdateIndividualFieldsOfModel('leads', '{{ $lead->id }}', 'lead_rating', this.value, 'lead_rating', 'LeadRating'); reloadLeadsLists();">
                        @foreach(Lead::$lead_ratings as $key_lead_rating => $value_lead_rating )
                            <option value="{{ $key_lead_rating }}" {{ $lead->lead_rating == $key_lead_rating? "selected": ""  }}>{{ $value_lead_rating }}</option>
                        @endforeach
                    </select>
                </td>
                <td onclick="ajaxLoadDashboardLeadDetails({{ $lead->id }})">
                    <img src="{{ asset('assets/img/icons/icon-dashboard-open.png') }}" style="width: 25px;" alt="">
                </td>
            </tr>
            @if(!is_object($lead->customer))
                <tr class="lead-meta-summary">
                    <td><strong>New Lead Summary:</strong></td>
                    <td colspan="5">
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
            <td colspan="6" class="text-center">No Leads Available For the Selected Time Period.</td>
        </tr>
    @endif
</table>
<div class="panel-list-pagination" id="pagination_dashboard_leads_list">
    <?php echo $leads->links(); ?>
</div>

<script>
    function reloadLeadsLists(){
        var pagination_page  = $("#pagination_dashboard_leads_list").find('li.active > span').html();
        ajaxLoadDashboardLeadsList(pagination_page);
    }
</script>