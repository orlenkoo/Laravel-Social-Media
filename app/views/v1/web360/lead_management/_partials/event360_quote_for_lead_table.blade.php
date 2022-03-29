<div class="table-scroll">
    <table id="event360_enquiry_lead_quotes_table_{{ $lead_id }}">

        @foreach($event360_enquiry_lead_quotes as $event360_enquiry_lead_quote)

            <tr>
                <th>Quoted On</th>
                <td>{{ $event360_enquiry_lead_quote->quote_date_time }}</td>
                <th>Quoted By</th>
                <td>{{ is_object($event360_enquiry_lead_quote->quoteUpdatedBy)? $event360_enquiry_lead_quote->quoteUpdatedBy->getEmployeeFullName(): "NA" }}</td>
            </tr>

            <tr>
                <td colspan="4">
                    <table>
                        <th>Sub Service Category</th>
                        <th>Sub Service Category Remarks</th>
                        <th>Your Quote</th>
                        <th>Your Remarks/ Notes</th>
                        <?php
                        $event360_enquiry_lead_quote_items = Event360EnquiryLeadQuoteItem::where('event360_enquiry_lead_quote_id', $event360_enquiry_lead_quote->id)->orderBy('id', 'desc')->get();
                        ?>
                        @foreach($event360_enquiry_lead_quote_items as $event360_enquiry_lead_quote_item)
                            <tr>
                                <td>{{ $event360_enquiry_lead_quote_item->event360EnquiryRequiredSubService->event360SubServiceCategory->service_category }}</td>
                                <td>{{ $event360_enquiry_lead_quote_item->event360EnquiryRequiredSubService->event360_remarks }}</td>
                                <td>{{ $event360_enquiry_lead_quote_item->quote_amount }}</td>
                                <td>{{ $event360_enquiry_lead_quote_item->quote_remarks_notes }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            @if(count($event360_enquiry_lead_quote->event360EnquiryLeadQuoteAttachments) > 0)
                <tr>
                    <td colspan="6">
                        <h2>Attachments</h2>
                        <table>
                            <tr>
                                <th>Title</th>
                                <th>Submitted On</th>
                                <th></th>
                            </tr>
                            @foreach($event360_enquiry_lead_quote->event360EnquiryLeadQuoteAttachments as $event360_enquiry_lead_quote_attachments)
                                <tr>
                                    <td>{{ $event360_enquiry_lead_quote_attachments->title }}</td>
                                    <td>{{ $event360_enquiry_lead_quote_attachments->datetime }}</td>
                                    <td><a href="{{ $event360_enquiry_lead_quote_attachments->image_url }}"
                                           class="button tiny" target="_blank">Open</a></td>
                                </tr>
                            @endforeach
                        </table>

                    </td>
                </tr>
            @endif
            <tr>
                <th colspan="6" style="background-color: #7db9e8;">&nbsp;</th>
            </tr>


        @endforeach
    </table>
</div>