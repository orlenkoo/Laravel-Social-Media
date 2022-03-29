<div class="large-12 columns text-center">
    <div class="panel">
        <h4>Leads Report</h4>
        <h5>From {{ $filters_array['from_date'] }} - To {{ $filters_array['to_date'] }}</h5>
        <em>Found {{ count($leads) }}</em>
    </div>

</div>

<div class="large-12 columns">
    <div class="panel" style="overflow: auto;">
        <?php setlocale(LC_MONETARY, 'en_US'); ?>
        <table class="">
            <thead>
            <tr align="center">
                <th colspan="20">LEADS</th>
                <th colspan="9">CUSTOMER DETAILS</th>
                <th>CONTACTS</th>
                <th>CALLS</th>
                <th>MEETINGS</th>
                <th>EMAILS</th>
                <th>QUOTATIONS</th>
            </tr>
            <tr>
                <th>Lead Creation Date & Time</th>
                <th>Lead Source</th>
                <th>Campaign Auto Tag</th>
                <th>Assigned To</th>
                <th>Lead Rating</th>
                <th>Website Form Data</th>
                <th>Call Submission Data</th>
                <th>UTM Source</th>
                <th>UTM Medium</th>
                <th>UTM Term</th>
                <th>UTM Campaign</th>
                <th>UTM COntent</th>
                <th>GCLID</th>
                <th>FBCLID</th>
                <th>Incoming Call Number</th>
                <th>Tracking Number</th>
                <th>Termination NUmber</th>
                <th>Result</th>
                <th>Call Duration</th>
                <th>Total Duration</th>
                <th>Customer Name</th>
                <th>Address Line 1</th>
                <th>Address Line 2</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>State</th>
                <th>Website</th>
                <th>Phone Number</th>
                <th>Fax Number</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($leads as $lead)
                <?php
                $customer = $lead->customer;
                ?>
                <tr>
                    <td>{{ $lead->datetime }}</td>
                    <td>{{ Lead::$lead_sources[$lead->lead_source] }}</td>
                    <td>{{ $lead->auto_tagged_campaign }}</td>
                    <td>
                        @if(is_object($lead->assignedTo))
                            {{ $lead->assignedTo->getEmployeeFullName() }}
                        @else
                            NA
                        @endif
                    </td>
                    <td>{{ $lead->lead_rating }}</td>
                    <td>
                        @if($lead->lead_source == 'web360_enquiries')
                            <div class="reveal panel large" id="popup_lead_details_web360_enquiries_{{ $lead->id }}" data-reveal>
                                <div class="panel-heading">
                                    <h4>Website Form Data</h4>
                                </div>
                                <div class="panel-content">
                                    @include('leads._partials.lead_meta.pixel_lead')
                                </div>
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <input type="button" class="tiny button" value="View >" data-open="popup_lead_details_web360_enquiries_{{ $lead->id }}">
                        @else
                            NA
                        @endif

                    </td>
                    <td>
                        @if($lead->lead_source == 'event360_calls')
                            <div class="reveal panel large" id="popup_lead_details_event360_calls_{{ $lead->id }}" data-reveal>
                                <div class="panel-heading">
                                    <h4>Website Form Data</h4>
                                </div>
                                <div class="panel-content">
                                    @include('leads._partials.lead_meta.call_lead')
                                </div>
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <input type="button" class="tiny button" value="View >" data-open="popup_lead_details_event360_calls_{{ $lead->id }}">
                        @else
                            NA
                        @endif
                    </td>
                    <td>{{  $lead->utm_source }}</td>
                    <td>{{  $lead->utm_medium }}</td>
                    <td>{{  $lead->utm_term }}</td>
                    <td>{{  $lead->utm_campaign }}</td>
                    <td>{{  $lead->utm_content }}</td>
                    <td>{{  $lead->gclid }}</td>
                    <td>{{  $lead->fbclid }}</td>
                    @if($lead->lead_source == 'event360_calls')
                        <?php $event360_call = $lead->event360Call ?>
                        @if(is_object($event360_call))
                            <td>{{ $event360_call->incoming_call_number }}</td>
                            <td>{{ $event360_call->number1300 }}Tracking Number</td>
                            <td>{{ $event360_call->transferred_number }}Termination NUmber</td>
                            <td>{{ $event360_call->result }}Result</td>
                            <td>{{ $event360_call->duration }}Call Duration</td>
                            <td>{{ $event360_call->durationof1300 }}Total Duration</td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                    @if(is_object($lead->customer))
                        <td>{{ $lead->customer->customer_name }}</td>
                        <td>{{ $lead->customer->address_line_1 }}</td>
                        <td>{{ $lead->customer->address_line_2 }}</td>
                        <td>{{ $lead->customer->city }}</td>
                        <td>{{ $lead->customer->postal_code }}</td>
                        <td>{{ $lead->customer->state }}</td>
                        <td>{{ $lead->customer->website }}</td>
                        <td>{{ $lead->customer->phone_number }}</td>
                        <td>{{ $lead->customer->fax_number }}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_contacts_{{ $lead->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Contacts</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $contacts = (is_object($customer)) ? $customer->contacts : null;
                                ?>
                                @include('reports._partials.contact_details', array('contacts'=> $contacts))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Contacts ({{ sizeof($contacts) }})>" data-open="popup_lead_details_contacts_{{ $lead->id }}">
                    </td>
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_calls_{{ $lead->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Calls</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $calls = (is_object($customer)) ? $customer->calls : null;
                                ?>
                                @include('reports._partials.call_details', array('calls'=> $calls))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Calls ({{ sizeof($calls)  }})>" data-open="popup_lead_details_calls_{{ $lead->id }}">
                    </td>
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_meetings_{{ $lead->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Meetings</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $meetings = (is_object($customer)) ? $customer->meetings : null;
                                ?>
                                @include('reports._partials.meeting_details', array('meetings'=> $meetings))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Meetings ({{ sizeof($meetings) }})>" data-open="popup_lead_details_meetings_{{ $lead->id }}">
                    </td>
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_emails_{{ $lead->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Emails</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $emails = (is_object($customer)) ? $customer->emails : null;
                                ?>
                                @include('reports._partials.email_details', array('emails'=> $emails))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Emails ({{ sizeof($emails) }})>" data-open="popup_lead_details_emails_{{ $lead->id }}">
                    </td>
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_quotations_{{ $lead->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Quotations</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $quotations = (is_object($customer)) ? $customer->quotations : null;
                                ?>
                                @include('reports._partials.quotation_details', array('quotations'=> $quotations))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Quotations ({{ sizeof($quotations) }})>" data-open="popup_lead_details_quotations_{{ $lead->id }}">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
