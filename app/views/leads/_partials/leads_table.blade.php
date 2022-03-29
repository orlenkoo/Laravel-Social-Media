<table class="hover">
    <tr>
        <th>
            Date
        </th>
        <th>
            Source
        </th>
        <th>
            Customer
        </th>
        <th>
            Lead Rating
        </th>
        <th>
            Assigned To
        </th>
        <th>
            Tags
        </th>
        <th>
            Details
        </th>
    </tr>
    @foreach($leads as $lead)
        <tr>
            <td>
                {{ $lead->datetime }}
            </td>
            <td>
                {{ array_key_exists($lead->lead_source, Lead::$lead_sources)? Lead::$lead_sources[$lead->lead_source]: "NA"  }}
            </td>
            <td>
                @if(is_object($lead->customer))
                    <a href="{{ route('customers.view', array('customer_id' => $lead->customer->id)) }}" target="_blank">{{ $lead->customer->customer_name }}</a>
                @else
                    New Lead - Update Customer Name
                @endif
            </td>
            <td>
                {{ $lead->lead_rating }}
            </td>
            <td>
                {{ is_object($lead->assignedTo) ? $lead->assignedTo->getEmployeeFullName(): "NA" }}
            </td>
            <td>
                <select name="lead_tags" id="lead_tags_{{$lead->id}}" class="lead_tags" onchange="assignLeadTags('lead_tags_{{$lead->id}}','{{$lead->id}}')" multiple>
                    <?php
                    $assigned_tags = LeadsController::getTagsForLead($lead->id);
                    foreach($assigned_tags as $key => $value){
                        echo "<option selected value='$value'>$value</option>";
                    }
                    ?>
                </select>
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