<div class="large-12 columns text-center">
    <div class="panel">
        <h4>Activity Report</h4>
        <h5>From {{ $filters_array['from_date'] }} - To {{ $filters_array['to_date'] }}</h5>
        <em>Found {{ count($activities) }}</em>
    </div>
</div>

<div class="large-12 columns">
    <div class="panel" style="overflow: auto;">
        <?php setlocale(LC_MONETARY, 'en_US'); ?>
        <table class="">
            <thead>
            <tr align="center">
                <th colspan="2">CUSTOMER DETAILS</th>
                <th>CONTACTS</th>
                <th>CALLS</th>
                <th>MEETINGS</th>
                <th>EMAILS</th>
                <th>QUOTATIONS</th>
            </tr>
            <tr>
                <th>Customer Name</th>
                <th>Tags</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $activity->customer_name }}</td>
                    <td>{{ Customer::getCustomerTagsCommaSeparated($activity->id ) }}</td>
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_contacts_{{ $activity->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Contacts</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $contacts = (is_object($activity)) ? $activity->contacts : null;
                                ?>
                                @include('reports._partials.contact_details', array('contacts'=> $contacts))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Contacts ({{ sizeof($contacts) }})>" data-open="popup_lead_details_contacts_{{ $activity->id }}">
                    </td>
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_calls_{{ $activity->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Calls</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $calls = (is_object($activity)) ? $activity->calls : null;
                                ?>
                                @include('reports._partials.call_details', array('calls'=> $calls))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Calls ({{ sizeof($calls)  }})>" data-open="popup_lead_details_calls_{{ $activity->id }}">
                    </td>
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_meetings_{{ $activity->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Meetings</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $meetings = (is_object($activity)) ? $activity->meetings : null;
                                ?>
                                @include('reports._partials.meeting_details', array('meetings'=> $meetings))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Meetings ({{ sizeof($meetings) }})>" data-open="popup_lead_details_meetings_{{ $activity->id }}">
                    </td>
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_emails_{{ $activity->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Emails</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $emails = (is_object($activity)) ? $activity->emails : null;
                                ?>
                                @include('reports._partials.email_details', array('emails'=> $emails))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Emails ({{ sizeof($emails) }})>" data-open="popup_lead_details_emails_{{ $activity->id }}">
                    </td>
                    <td>
                        <div class="reveal panel large" id="popup_lead_details_quotations_{{ $activity->id }}" data-reveal>
                            <div class="panel-heading">
                                <h4>Quotations</h4>
                            </div>
                            <div class="panel-content">
                                <?php
                                $quotations = (is_object($activity)) ? $activity->quotations : null;
                                ?>
                                @include('reports._partials.quotation_details', array('quotations'=> $quotations))
                            </div>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="button" class="tiny button" value="Quotations ({{ sizeof($quotations) }})>" data-open="popup_lead_details_quotations_{{ $activity->id }}">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

