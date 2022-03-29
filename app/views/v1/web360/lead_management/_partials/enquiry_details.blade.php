<div class="row expanded">
    <div class="large-8 columns">
        <h2>Event Planner:</h2>
        <table>
            <tr>
                <th>Company Name</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->company_name : '' }}</td>
            </tr>
            <tr>
                <th>Surname</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->surname : '' }}</td>
            </tr>
            <tr>
                <th>Given Name</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->given_name : '' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->email : '' }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->phone_number : '' }}</td>
            </tr>
        </table>
        <h2>About the Event:</h2>
        <table>
            <tr>
                <th>Event Type</th>
                <td>
                    @if(is_object($lead->event360Enquiry->event360EventType))
                        {{ $lead->event360Enquiry->event360EventType->event_type }}
                    @else
                        NA
                    @endif
                </td>
            </tr>
            <tr>
                <th>Event Pax</th>
                <td>{{ $lead->event360Enquiry->pax_min }} - {{ $lead->event360Enquiry->pax_max }}</td>
            </tr>
            <tr>
                <th>Event Date</th>
                <td>{{ $lead->event360Enquiry->date }}</td>
            </tr>
            <tr>
                <th>Event Start/End Time</th>
                <td>{{ $lead->event360Enquiry->event_start_time }} - {{ $lead->event360Enquiry->event_end_time }}</td>
            </tr>
        </table>
    </div>

    <div class="large-4 columns">
        <h2>Required Services:</h2>
        @if(count($lead->event360Enquiry->event360EnquiryRequiredServices) > 0)
            @foreach($lead->event360Enquiry->event360EnquiryRequiredServices as $event360_enquiry_required_service)
                <p><strong>{{ $event360_enquiry_required_service->event360ServiceCategory->service_category }}</strong></p>
                <ul>
                    @foreach($event360_enquiry_required_service->event360EnquiryRequiredSubServices as $event360_enquiry_required_sub_service)
                        <li> {{ $event360_enquiry_required_sub_service->event360SubServiceCategory->service_category }}</li>
                    @endforeach
                </ul>
            @endforeach
        @endif

        @if($lead->event360Enquiry->food_and_beverage_required)
            <p><strong>Food and Beverage Requirements</strong></p>
            <ul>
                @foreach($lead->event360Enquiry->event360EnquiryFoodAndBeverageRequirements as $event360_enquiry_food_and_beverage_requirement)
                    <li>{{ $event360_enquiry_food_and_beverage_requirement->event360FoodAndBeverageRequirement->requirement }}</li>
                @endforeach
            </ul>
        @endif

        @if($lead->event360Enquiry->event_venue_rental_required)
            <p><strong>Event Venue Type Requirements</strong></p>
            <ul>
                @foreach($lead->event360Enquiry->event360EnquiryVenueTypes as $event360_enquiry_venue_type)
                    <li>{{ $event360_enquiry_venue_type->event360VenueType->venue_type }}</li>
                @endforeach
            </ul>

            <p><strong>Event Venue Location Requirements</strong></p>
            <ul>
                @foreach($lead->event360Enquiry->event360EnquiryVenueLocations as $event360_enquiry_venue_location)
                    <li>{{ $event360_enquiry_venue_location->event360Location->location }}</li>
                @endforeach
            </ul>
        @endif

    </div>
</div>