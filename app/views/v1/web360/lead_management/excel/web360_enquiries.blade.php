<table>
    <tr>
        <th>
            <h4>Website Enquiries Leads</h4>
        </th>
    </tr>
    <tr>
        <td>
            <table>
                <thead>
                <tr>
                    <th>Date of Submission</th>
                    <th>Time of Submission</th>
                    <th>Form Field 1</th>
                    <th>Form Field 2</th>
                    <th>Form Field 3</th>
                    <th>Lead Rating</th>
                    <th>Lead Details</th>
                    <th>Lead Forwards</th>
                    <th>Notes</th>
                </tr>
                </thead>
                <tbody>
                @foreach($leads as $lead)
                    <tr>
                        <td>{{ date("Y/m/d", strtotime($lead->datetime)) }}</td>
                        <td>{{ date("H:i:s", strtotime($lead->datetime)) }}</td>

                        @if(is_object($lead->web360Enquiry))
                            <?php $enquiry_details = json_decode($lead->web360Enquiry->enquiry_details) ?>

                            <?php $i = 0;
                            foreach ($enquiry_details as $key => $value) {
                                if( $i < 3 ) {
                                    echo '<td>'. $value .'</td>';
                                }
                                $i++;
                            }
                            ?>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif

                        <td>{{ $lead->lead_rating }}</td>
                        <td>
                                <?php $enquiry_details = json_decode($lead->web360Enquiry->enquiry_details) ?>
                                    @foreach($enquiry_details as $key => $value)
                                            {{ $key }} -  {{ is_array($value)? implode(', ',$value ) : $value }}
                                    @endforeach
                                             --------------------------------- <br>
                        </td>
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
