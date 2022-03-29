<?php
if ($lead->status == 'Pending') {
    $show_contact_details = 'none';
} else {
    $show_contact_details = 'block';
}
?>
<div class="row expanded">
    <div class="large-6 columns">
        <h2>Job Number - {{$lead->lead_source_id}}</h2>
    </div>
</div>


<div class="row expanded">
    <div class="large-6 columns">
        <h2>Event Planner:</h2>
        <table>
            <tr>
                <th>Surname</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->surname : '' }}</td>
            </tr>
            <tr>
                <th>Given Name</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->given_name : '' }}</td>
            </tr>
            <tr>
                <th>Job Title</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->job_title : '' }}</td>
            </tr>


        </table>
        <table style="display: {{ $show_contact_details }};" class="contact_details_{{ $lead->id }}">

            <tr>
                <th>Company name</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->company_name : '' }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->phone_number : '' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ is_object($lead->event360Enquiry->event360EventPlannerProfile) ? $lead->event360Enquiry->event360EventPlannerProfile->email : '' }}</td>
            </tr>

        </table>
    </div>
    <div class="large-6 columns">
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
                <td>{{ $lead->event360Enquiry->event_date }}</td>
            </tr>
            <tr>
                <th>Event Start/End Time</th>
                <td>{{ $lead->event360Enquiry->event_start_time }} - {{ $lead->event360Enquiry->event_end_time }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row expanded">

    <div class="large-12 columns">
        <ul class="tabs" data-tab>
            <li class="tab-title active"><a href="#panel-new-quotation">New Quotation</a></li>
            <li class="tab-title"><a href="#panel-previous-quotation"
                                     onclick="loadPreviousQuotations_{{ $lead->id }}(1)">Previous
                    Quotations</a></li>
        </ul>

        <div class="tabs-content">
            <div class="content active" id="panel-new-quotation">
                <h2>Required Services:</h2>
                @if(count($lead->event360Enquiry->event360EnquiryRequiredServices) > 0)
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('style' => 'display: none;' ,'class' => '', 'id' => 'loader_lead_quote_form_'.$lead->id)) }}


                    {{ Form::open(array('route' => 'event360_enquiry_lead_quote.ajax.save_quotation', 'files' => true, 'id' => 'lead_quote_form_'.$lead->id,'autocomplete' => 'off' )) }}
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                    <div class="row expanded">
                        <div class="large-12 columns">
                            <div class="row expanded">
                                <div class="large-12 columns">

                                    @foreach($lead->event360Enquiry->event360EnquiryRequiredServices as $event360_enquiry_required_service)
                                        @if($event360_enquiry_required_service->event360_service_category_id == 12)
                                            {{--event venue selected--}}
                                        <?php

                                            $event360_enquiry_required_sub_service = Event360EnquiryRequiredSubService::where('event360_enquiry_required_service_id', $event360_enquiry_required_service->id)
                                                ->where('event360_sub_service_category_id', 50)->first();
                                            ?>
                                            @if(is_object($event360_enquiry_required_sub_service))
                                                @if(Event360Enquiry::checkIfVenueOwnerConditionsAreMet($lead))
                                                    <table>
                                                        <tr><th colspan="2"><strong>Event Venue Required</strong></th></tr>
                                                        <tr><td colspan="2">
                                                                @if($lead->event360Enquiry->food_and_beverage_required)

                                                                    <p><label>Food and Beverage Required: </label> Yes</p>

                                                                    @if(is_object($lead->event360Enquiry->event360EnquiryFoodAndBeverageRequirements))
                                                                        <label>Food and Beverage Requirements: </label>
                                                                        <ul>
                                                                            @foreach($lead->event360Enquiry->event360EnquiryFoodAndBeverageRequirements as $event360_enquiry_food_and_beverage_requirement)
                                                                                <li>{{ $event360_enquiry_food_and_beverage_requirement->event360FoodAndBeverageRequirement->requirement }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif

                                                                @endif

                                                                @if($lead->event360Enquiry->event_venue_rental_required)

                                                                    {{--<p><label>Event Venue Required: </label> Yes</p>--}}

                                                                    @if(is_object($lead->event360Enquiry->event360EnquiryVenueTypes))
                                                                        <label>Event Venue Type Requirements: </label>
                                                                        <ul>
                                                                            @foreach($lead->event360Enquiry->event360EnquiryVenueTypes as $event360_enquiry_venue_type)
                                                                                <li>{{ $event360_enquiry_venue_type->event360VenueType->venue_type }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif

                                                                    @if(is_object($lead->event360Enquiry->event360EnquiryVenueLocations))
                                                                        <label>Event Venue Location Requirements: </label>
                                                                        <ul>
                                                                            @foreach($lead->event360Enquiry->event360EnquiryVenueLocations as $event360_enquiry_venue_location)
                                                                                <li>{{ $event360_enquiry_venue_location->event360Location->location }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif

                                                                @endif
                                                            </td></tr>
                                                        <tr>
                                                            <th>Your Quote</th>
                                                            <th>Your Remarks/ Notes</th>
                                                        </tr>
                                                        <input type="hidden"
                                                               name="event360_enquiry_required_sub_service_ids[]"
                                                               value="{{ $event360_enquiry_required_sub_service->id }}">
                                                        <tr>

                                                                <td><input type="number" placeholder="Enter Your Quote"
                                                                           name="quote_amounts[]" class="numbersonly">
                                                                </td>
                                                                <td><textarea rows="3" type="text"
                                                                              placeholder="Enter Your Remarks/ Notes"
                                                                              name="quote_remarks_notes[]"></textarea>
                                                                </td>

                                                        </tr>
                                                    </table>
                                                @endif {{-- end of checkIfVenueOwnerConditionsAreMet  --}}
                                            @endif {{-- end of check if venue is a required sub service --}}

                                        @else
                                            <table>
                                                {{--other types--}}
                                                <?php
                                                $event360_enquiry_required_sub_services = $event360_enquiry_required_service->event360EnquiryRequiredSubServices;

                                                ?>
                                                @if(Event360Enquiry::checkIfVendorSubServiceSelected($event360_enquiry_required_sub_services, $vendor_sub_service_categories))
                                                    <tr>
                                                        <th>Service:</th>
                                                        <td colspan="3">{{ $event360_enquiry_required_service->event360ServiceCategory->service_category }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Sub Service Category</th>
                                                        <th>Sub Service Category Remarks</th>
                                                        <th>Your Quote</th>
                                                        <th>Your Remarks/ Notes</th>
                                                    </tr>
                                                    @foreach($event360_enquiry_required_sub_services as $event360_enquiry_required_sub_service)
                                                        @if(in_array($event360_enquiry_required_sub_service->event360SubServiceCategory->id, $vendor_sub_service_categories))
                                                            <input type="hidden"
                                                                   name="event360_enquiry_required_sub_service_ids[]"
                                                                   value="{{ $event360_enquiry_required_sub_service->id }}">
                                                            <tr>
                                                                <td>{{ $event360_enquiry_required_sub_service->event360SubServiceCategory->service_category }}</td>
                                                                <td>{{ $event360_enquiry_required_sub_service->event360_remarks }}</td>
                                                                <td><input type="number" placeholder="Enter Your Quote"
                                                                           name="quote_amounts[]" class="numbersonly">
                                                                </td>
                                                                <td><textarea rows="3" type="text"
                                                                              placeholder="Enter Your Remarks/ Notes"
                                                                              name="quote_remarks_notes[]"></textarea>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </table>
                                        @endif
                                    @endforeach

                                </div>
                            </div>

                            <div class="row expanded">
                                <div class="large-12 columns">
                                    <h5>Quote Attachments</h5>


                                    <table id="lead_quote_attachments_table_{{ $lead->id }}">
                                        <thead>
                                        <tr>
                                            <th>Attachment Title</th>
                                            <th>Attachment File</th>
                                            <th>Remove</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><input type="text" name="quote_attachment_titles[]"></td>
                                            <td><input type="file" name="attachment_files[]"
                                                       onchange="getAttachmentFileName(this)"></td>
                                            <td><input type="button" class="button tiny alert" value="X"
                                                       onclick="javascript:removeAttachment_{{ $lead->id }}(this);">
                                            </td>
                                        </tr>


                                        </tbody>
                                    </table>


                                    <div class="row expanded">
                                        <div class="large-12 columns">
                                            <hr>
                                            <input type="button" class="button tiny success right"
                                                   value="Add More Attachments +"
                                                   onclick="addLeadQuoteAttachment_{{ $lead->id }}()">
                                        </div>
                                    </div>

                                    <script>
                                        function addLeadQuoteAttachment_{{ $lead->id }}() {


                                            var table = document.getElementById('lead_quote_attachments_table_{{ $lead->id }}');

                                            var row = table.insertRow();


                                            var cell1 = row.insertCell(0);
                                            var cell2 = row.insertCell(1);
                                            var cell3 = row.insertCell(2);


                                            cell1.innerHTML = '<input type="text" name="quote_attachment_titles[]">';
                                            cell2.innerHTML = '<input type="file" name="attachment_files[]" onchange="getAttachmentFileName(this)">';
                                            cell3.innerHTML = '<input type="button" class="button tiny alert" value="X" onclick="javascript:removeAttachment_{{ $lead->id }}(this)">';


                                        }

                                        function removeAttachment_{{ $lead->id }}(row) {
                                            //console.log(row);
                                            var i = row.parentNode.parentNode.rowIndex;
                                            //var i = $(row).parent().parent().index();
                                            document.getElementById("lead_quote_attachments_table_{{ $lead->id }}").deleteRow(i);
                                        }

                                        function getAttachmentFileName(obj) {
                                            var fullPath = obj.value;
                                            if (fullPath) {
                                                var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
                                                var filename = fullPath.substring(startIndex);
                                                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                                                    filename = filename.substring(1);
                                                }
                                                //alert(filename);

                                                $(obj).closest('tr').find("input[name='quote_attachment_titles[]']").each(function () {
                                                    this.value = filename;
                                                });

                                            }
                                        }


                                    </script>
                                </div>
                            </div>


                        </div>
                    </div>
                    <hr>
                    <div class="row expanded">
                        <div class="large-12 columns">
                            <input type="submit" class="button tiny success right" value="Submit Quote">
                            <input type="button" class="button tiny alert right" value="Clear"
                                   onclick="clearQuotation_{{ $lead->id }}()" style="margin-right: 10px;">
                        </div>
                    </div>
                    {{ Form::close() }}

                @else
                    <p>No Services Found</p>
                @endif


                <script>
                    // wait for the DOM to be loaded
                    // prepare the form when the DOM is ready 
                    $(document).ready(function () {
                        // bind form using ajaxForm 
                        $('#lead_quote_form_<?php echo $lead->id; ?>').ajaxForm({
                            // dataType identifies the expected content type of the server response 
                            dataType: 'json',
                            beforeSubmit: beforeSavingQuote_{{ $lead->id }},
                            // success identifies the function to invoke when the server response 
                            // has been received 
                            success: afterSavingLeadQuote_{{ $lead->id }}
                        });
                    });

                    function beforeSavingQuote_{{ $lead->id }}() {
                        $('#loader_lead_quote_form_<?php echo $lead->id; ?>').show();
                        $('#lead_quote_form_<?php echo $lead->id; ?>').hide();


                    }

                    function afterSavingLeadQuote_{{ $lead->id }}(data) {

                        //alert('message  -- ' +data.message);

                        loadPreviousQuotations_{{ $lead->id }}(1);
                        clearQuotation_{{ $lead->id }} ();
                        $('#loader_lead_quote_form_<?php echo $lead->id; ?>').hide();
                        $('#lead_quote_form_<?php echo $lead->id; ?>').show();
                        $('.contact_details_<?php echo $lead->id; ?>').show();

                        // reload the leads list
                        getAjaxEvent360EnquiryLeadsList(1);
                    }
                </script>

                <script>
                    function saveQuotation_{{ $lead->id }}() {

                        var r = confirm("Would you like to publish this quotation? *Please note quotations are not editable.");
                        if (r == true) {

                            $('#loader_lead_quote_form_<?php echo $lead->id; ?>').show();
                            $('#lead_quote_form_<?php echo $lead->id; ?>').hide();


                            //alert('lead id -- {{ $lead->id }}');
                            var lead_id = '{{ $lead->id }}';
                            var event360_enquiry_required_sub_service_ids = $('#form_new_quotation_{{ $lead->id }} :input[name="event360_enquiry_required_sub_service_id[]"]').map(function (_, el) {
                                return $(el).val();
                            }).get();
                            var quote_amounts = $('#form_new_quotation_{{ $lead->id }} :input[name="quote_amount[]"]').map(function (_, el) {
                                return $(el).val();
                            }).get();
                            var quote_remarks_notes = $('#form_new_quotation_{{ $lead->id }} :input[name="quote_remarks_notes[]"]').map(function (_, el) {
                                return $(el).val();
                            }).get();

//                        alert('event360_enquiry_required_sub_service_ids -- ' + event360_enquiry_required_sub_service_ids);
//                        alert('quote_amounts -- ' + quote_amounts);
//                        alert('quote_remarks_notes -- ' + quote_remarks_notes);

                            $.post("/event360-enquiry-lead-quote/ajax/save-quotation/",
                                    {
                                        lead_id: lead_id,
                                        event360_enquiry_required_sub_service_ids: event360_enquiry_required_sub_service_ids,
                                        quote_amounts: quote_amounts,
                                        quote_remarks_notes: quote_remarks_notes
                                    }, function (data) {
                                        $.notify(data, "success");
                                        loadPreviousQuotations_{{ $lead->id }}(1);
                                        clearQuotation_{{ $lead->id }} ();
                                        $('#loader_lead_quote_form_<?php echo $lead->id; ?>').hide();
                                        $('#lead_quote_form_<?php echo $lead->id; ?>').show();
                                        $('.contact_details_<?php echo $lead->id; ?>').show();

                                        // reload the leads list
                                        getAjaxEvent360EnquiryLeadsList(1);
                                    });
                        } else {
                            return false;
                        }


                    }

                    function clearQuotation_{{ $lead->id }} () {
                        //$('#lead_quote_form_<?php echo $lead->id ?> :input[type=text]').val('');
                        $('#lead_quote_form_<?php echo $lead->id ?>').resetForm();
                    }
                </script>
            </div><!--end panel-new-quotation-->
            <div class="content" id="panel-previous-quotation">


                {{ HTML::image('assets/img/loading.gif', 'Loading', array('style' => 'display: none;' ,'class' => '', 'id' => 'loader_previous_quotations_list_'.$lead->id)) }}
                <div id="previous_quotations_list_{{ $lead->id }}">

                </div>
                <script>


                    /*==================== PAGINATION =========================*/

                    $(document).on('click', '#pagination_event360_enquiry_lead_quotes_list a', function (e) {
                        e.preventDefault();
                        var page = $(this).attr('href').split('page=')[1];
                        //location.hash = page;
                        loadPreviousQuotations_{{ $lead->id }}(page);
                    });

                    function loadPreviousQuotations_{{ $lead->id }}(page) {
                        $('#loader_previous_quotations_list_<?php echo $lead->id; ?>').show();
                        $('#previous_quotations_list_<?php echo $lead->id; ?>').hide();
                        $('#previous_quotations_list_<?php echo $lead->id; ?>').html('');


                        $.ajax({
                            url: '/event360-enquiry-lead-quote/ajax/load-previous-quotations?' +
                            'page=' + page +
                            '&lead_id=<?php echo $lead->id; ?>'
                        }).done(function (data) {
                            $('#previous_quotations_list_<?php echo $lead->id; ?>').html(data);
                            $('#loader_previous_quotations_list_<?php echo $lead->id; ?>').hide();
                            $('#previous_quotations_list_<?php echo $lead->id; ?>').show();
                            $("#event360_enquiry_lead_quotes_table_<?php echo $lead->id; ?>").tablesorter();
                            $(document).foundation();
                        });
                    }



                </script>
            </div><!--end panel-previous-quotation-->
        </div>


    </div>
</div>