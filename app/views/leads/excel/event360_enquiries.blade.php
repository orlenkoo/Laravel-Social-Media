<table>
    <tr>
        <th>
            <h4>Event360 Enquiries Leads</h4>
        </th>
    </tr>
    <tr>
        <td>
            <table>
                <thead>
                <tr>
                    <th>Date of Submission</th>
                    <th>Time of Submission</th>
                    <th>Company Name</th>
                    <th>Surname</th>
                    <th>Given Name</th>
                    <th>Lead Rating</th>
                    <th>Details</th>
                    <th>Lead Forwards</th>
                    <th>Notes</th>
                </tr>
                </thead>
                <tbody>
                @foreach($leads as $lead)
                    <tr>
                        <td>{{ date("Y/m/d", strtotime($lead->datetime)) }}</td>
                        <td>{{ date("H:i:s", strtotime($lead->datetime)) }}</td>
                        <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->company_name : '' }}</td>
                        <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->surname : '' }}</td>
                        <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->given_name : '' }}</td>
                        <td>{{ $lead->lead_rating }}</td>
                        @if($lead->status == 'Accepted' )
                            <td>
                                  <pre>

                                            Event Planner : <br>
                                            Surname - {{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->surname : '' }} <br>
                                            Given Name - {{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->given_name : '' }}<br>
                                            Job Title - {{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->job_title : '' }} <br>
                                            Company name - {{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->company_name : '' }}<br>
                                            Phone Number - {{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->phone_number : '' }}<br>
                                            Email - {{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->email : '' }}<br>
                                            --------------------------------- <br>


                                  </pre>
                                  <pre>
                                            About the Event : <br>
                                            Event Type -
                                                        @if(is_object($lead->event360Enquiry->event360EventType))
                                                            {{ $lead->event360Enquiry->event360EventType->event_type }}
                                                        @else
                                                            NA
                                                        @endif
                                                    <br>
                                            Event Pax - {{ $lead->event360Enquiry->pax_min }} - {{ $lead->event360Enquiry->pax_max }}<br>
                                            Event Date - {{ $lead->event360Enquiry->event_date }}<br>
                                            Event Start/End Time  - {{ $lead->event360Enquiry->event_start_time }} - {{ $lead->event360Enquiry->event_end_time }}<br>
                                  </pre>
                            </td>
                        @else
                            <td> </td>
                        @endif
                        <td>
                                @foreach($lead->leadForwards as $leadForward)
                                    <pre>
                                            Email - {{ $leadForward->email }} <br>
                                            Message - {{ $leadForward->message }} <br>
                                            Lead Forwarded On - {{ $leadForward->lead_forwarded_on }} <br>
                                            Lead Forwarded By - {{ $leadForward->lead_forwarded_by }} <br>
                                            --------------------------------- <br>
                                    </pre>
                                @endforeach
                        </td>
                        <td>
                                @foreach($lead->leadNotes as $leadnote)
                                    <pre>
                                                Note - {{ $leadnote->note }} <br>
                                                Date Time - {{ $leadnote->datetime }} <br>
                                                Created By - {{ $leadnote->leadNoteCreatedBy->given_name }} <br>
                                                --------------------------------- <br>
                                        </pre>
                                @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
</table>
