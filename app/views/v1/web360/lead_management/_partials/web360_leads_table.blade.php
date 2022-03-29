<div class="table-scroll">

    <table id="lead_details" class="">
        <thead>
        <tr>
            <th>Date of Submission</th>
            <th>Time of Submission</th>
            <th>Form Field 1</th>
            <th>Form Field 2</th>
            <th>Form Field 3</th>
            <th>Form Field 4</th>
            <th>Source</th>
            <th>Medium</th>
            <th>Keyword</th>
            <th>Campaign</th>
            <th>Lead Rating</th>
            <th>Lead View</th>
            <th>Share</th>
            <th>Notes</th>
            <th>ID</th>
        </tr>
        </thead>
        <tbody>
        @foreach($leads as $lead)
            <tr>
                <td>{{ date("Y/m/d", strtotime($lead->datetime)) }}</td>
                <td>{{ date("H:i:s", strtotime($lead->datetime)) }}</td>

                @if(is_object($lead->web360Enquiry))
                    <?php
                    $enquiry_details = json_decode($lead->web360Enquiry->enquiry_details);

                    if (is_object($enquiry_details)) {
                    $i = 0;
                    foreach ($enquiry_details as $key => $value) {
                    if ($i < 4) {
                    ?>
                    <td> {{ ucwords(str_replace('_', ' ', $key)) }} : {{ is_array($value)? implode(",", $value) : $value }} </td>
                    <?php }
                    $i++;
                    }

                    if($i<=3) {
                        for($j = $i; $j < 4; $j++) {
                            echo "<td></td>";
                        }
                    }
                    } else {
                    ?>
                    <td colspan="3">{{ $enquiry_details }}</td>
                    <?php
                    }
                    ?>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @endif

                <td>
                    {{ $lead->utm_source }}
                </td>
                <td>
                    {{ $lead->utm_medium }}
                </td>
                <td>
                    {{ $lead->utm_term }}
                </td>
                <td>
                    {{ $lead->utm_campaign }}
                </td>

                <td>
                    <select name="" id="" onchange="updateAjaxLeadRating({{ $lead->id }}, this.value)">
                        @foreach(Lead::$lead_ratings as $lead_rating)
                            <option value="{{ $lead_rating }}" <?php echo $lead->lead_rating == $lead_rating ? "selected" : ""; ?>>{{ $lead_rating }}</option>
                        @endforeach
                    </select>
                </td>
                <td>

                    <a href="#" data-open="view_lead_event360_enquiry_details_{{ $lead->id }}" class="button tiny">View ></a>

                    <div class="reveal panel" id="view_lead_event360_enquiry_details_{{ $lead->id }}" data-reveal>
                        <div class="panel-heading"><h4>Lead Details</h4></div>
                        <div class="panel-content">
                            @if(is_object($lead->web360Enquiry))
                                @include('v1.web360.lead_management._partials.web360_enquiry_details')
                            @else
                                No Web Enquiry Found
                            @endif
                        </div>
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </td>
                <td>

                    <a href="#" data-open="forward_lead_event360_enquiry_details_{{ $lead->id }}" class="button tiny">Forward ></a>

                    <div class="reveal panel" id="forward_lead_event360_enquiry_details_{{ $lead->id }}" data-reveal>
                        <div class="panel-heading"><h4>Forward Lead Details</h4></div>
                        <div class="panel-content">
                            @include('v1.web360.lead_management._partials.forward_lead')
                        </div>
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </td>
                <td>

                    <a href="#" data-open="lead_notes_event360_enquiry_details_{{ $lead->id }}" class="button tiny">Notes
                        ></a>

                    <div class="reveal panel" id="lead_notes_event360_enquiry_details_{{ $lead->id }}" data-reveal>
                        <div class="panel-heading"><h4>Lead Notes</h4></div>
                        <div class="panel-content">@include('v1.web360.lead_management._partials.lead_notes')</div>
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </td>
                <td>
                    {{ $lead->id }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

