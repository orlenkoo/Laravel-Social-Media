<table id="event360_website_form_submission_details" class="">
    <thead>
    <tr>
        <th>Date</th>
        <th>Company</th>
        <td>Contact Surname</td>
        <td>Contact Given Name</td>
        <td>Contact Job Title</td>
        <td>Contact Email</td>
        <td>Contact Phone Number</td>
        <th>Last 3 visited pages</th>
        <th>ID</th>
    </tr>
    </thead>
    <tbody>
    @foreach($leads as $lead)
        <tr>
                <td>{{ $lead->event360Enquiry->event_date}}</td>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->company_name : '' }}</td>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->surname : '' }}</td>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->given_name : '' }}</td>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->job_title : '' }}</td>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->email : '' }}</td>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->phone_number : '' }}</td>

                <td>
                    <a href="#" data-reveal-id="event360_website_form_table_{{ $lead->event360Enquiry->id }}"
                       class="button tiny">View</a>

                    <div id="event360_website_form_table_{{ $lead->event360Enquiry->id }}" class="reveal-modal" data-reveal
                         aria-labelledby="modalTitle" aria-hidden="true" role="dialog">

                        <h2>Last 3 visited pages</h2>

                        @include('webtics_product.web360.leads_detail._partials.event360_website_visits_table')

                    </div>
                </td>
                <td>{{ $lead->event360Enquiry->id}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

