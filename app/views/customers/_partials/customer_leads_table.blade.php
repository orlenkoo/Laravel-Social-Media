<table>
    <tr>
        <th>
            Campaign
        </th>
        <th>
            Campaign Auto Tag
        </th>
        <th>
            Assigned To
        </th>
        <th>
            Date Time
        </th>
        <th>
            Lead Source
        </th>
        <th>
            Lead Rating
        </th>
        <th>
            Quotation
        </th>
        <th>
            View
        </th>
    </tr>
    @foreach($customer_leads as $lead)
        <tr>
            <td>{{ is_object($lead->campaign)? $lead->campaign->campaign_name: 'NA' }}</td>
            <td>{{ $lead->auto_tagged_campaign }}</td>
            <td>{{ is_object($lead->assignedTo)? $lead->assignedTo->getEmployeeFullName(): 'NA' }}</td>
            <td>{{ $lead->datetime }}</td>
            <td>{{ array_key_exists($lead->lead_source, Lead::$lead_sources)? Lead::$lead_sources[$lead->lead_source]: 'NA' }}</td>
            <td>
                @if(in_array($lead->lead_rating, Lead::$editable_lead_ratings) || $lead->lead_rating == null)
                    <select name="lead_rating" id="lead_rating" onchange="ajaxUpdateIndividualFieldsOfModel('leads', '{{ $lead->id }}', 'lead_rating', this.value, 'lead_rating', 'LeadRating'); reloadLeadsLists();">
                        @foreach(Lead::$editable_lead_ratings as $key_lead_rating => $value_lead_rating )
                            <option value="{{ $key_lead_rating }}" {{ $lead->lead_rating == $key_lead_rating? "selected": ""  }}>{{ $value_lead_rating }}</option>
                        @endforeach
                    </select>
                @else
                    {{ $lead->lead_rating }}
                @endif
            </td>
            <td>
                <img src="{{ asset('assets/img/icons/icon-dashboard-create-quotation.png') }}" style="width: 25px;" alt="" data-open="popup_add_quotation_{{ $lead->id }}">
                <div class="reveal large panel" id="popup_add_quotation_{{ $lead->id }}" data-reveal>
                    @include('quotations._partials.add_new_quotation_form')
                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </td>
            <td>
                <input type="button" class="tiny button" value="View >" data-open="popup_lead_details" onclick="ajaxLoadLeadDetails({{ $lead->id }})">
            </td>
        </tr>
    @endforeach
</table>

<div class="reveal panel large" id="popup_lead_details" data-reveal>

    @include('leads._partials.popup_lead_details')

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>

</div>