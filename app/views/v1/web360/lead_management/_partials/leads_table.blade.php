<div class="table-scroll">

    <table id="lead_details" class="">
        <thead>
        <tr>
            <th>Date of Submission</th>
            <th>Time of Submission</th>
            <th>Company Name</th>
            <th>Surname</th>
            <th>Given Name</th>
            <th>Lead Rating</th>
            <th>Quote / View Details</th>
            <th>Share</th>
            <th>Notes</th>
        </tr>
        </thead>
        <tbody>
        @foreach($leads as $lead)
            <tr>
                <td>{{ date("Y/m/d", strtotime($lead->datetime)) }}</td>
                <td>{{ date("H:i:s", strtotime($lead->datetime)) }}</td>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->company_name : "" }}</td>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->surname : "" }}</td>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->given_name : "" }}</td>
                <td>
                    <select name="" id="" onchange="updateAjaxLeadRating({{ $lead->id }}, this.value)" <?php echo $lead->status == 'Pending' ? "disabled": ""; ?>>
                        @foreach(Lead::$lead_ratings as $lead_rating)
                            <option value="{{ $lead_rating }}" <?php echo $lead->lead_rating == $lead_rating ? "selected" : ""; ?>>{{ $lead_rating }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    @if($lead->status == 'Pending' && $lead->lead_source == 'event360_enquiries')
                        <a href="#" data-open="quote_for_lead_{{ $lead->id }}"  class="button success tiny">Quote</a>
                        <div id="quote_for_lead_{{ $lead->id }}" class="reveal panel" data-reveal>
                            <div class="panel-heading"><h4>Quote For Lead</h4></div>
                            <div class="panel-content">@include('v1.web360.lead_management._partials.event360_quote_for_lead')</div>


                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @else
                        <a href="#" data-open="view_lead_event360_enquiry_details_{{ $lead->id }}" class="button tiny">View Details ></a>

                        <div id="view_lead_event360_enquiry_details_{{ $lead->id }}" class="reveal panel" data-reveal>
                            <div class="panel-heading">
                                <h4>Lead Details</h4>
                            </div>
                            <div class="panel-content">
                                @if($lead->lead_source == 'event360_enquiries')
                                    @include('v1.web360.lead_management._partials.event360_quote_for_lead')
                                @elseif($lead->lead_source == 'web360_enquiries')
                                    @if(is_object($lead->web360Enquiry))
                                        @include('v1.web360.lead_management._partials.web360_enquiry_details')
                                    @else
                                        No Web Enquiry Found
                                    @endif
                                @elseif($lead->lead_source == 'event360_messenger_threads')
                                    @if(is_object($lead->event360MessengerThread))
                                        @include('v1.web360.lead_management._partials.event360_messenger_thread_details')
                                    @else
                                        No Messenger Thread Found
                                    @endif
                                @endif
                            </div>

                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                </td>
                <td>

                        <a href="#" data-open="forward_lead_event360_enquiry_details_{{ $lead->id }}" class="button tiny">Forward ></a>

                        <div id="forward_lead_event360_enquiry_details_{{ $lead->id }}" class="reveal panel" data-reveal>

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

                        <a href="#" data-open="lead_notes_event360_enquiry_details_{{ $lead->id }}" class="button tiny">Notes ></a>

                        <div id="lead_notes_event360_enquiry_details_{{ $lead->id }}" class="panel reveal" data-reveal>

                            <div class="panel-heading"><h4>Lead Notes</h4></div>
                            <div class="panel-content">@include('v1.web360.lead_management._partials.lead_notes')</div>

                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


