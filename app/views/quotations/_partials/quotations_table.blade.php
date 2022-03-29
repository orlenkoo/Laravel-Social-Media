<table>
    <tr>
        <th>Project Quote</th>
        <th>Customer Name</th>
        <th>Generated By</th>
        <th>Status</th>
        <th>Generated Date</th>
        {{--<th>Sub Total</th>--}}
        {{--<th>Discount Percentage</th>--}}
        {{--<th>Discount Value</th>--}}
        {{--<th>Tax</th>--}}
        <th>Nett Total</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    @foreach($quotations as $quotation)
        <tr>
            <td>{{ $quotation->project_quote }}</td>
            <td>
                @if(is_object($quotation->customer))
                    <a href="{{ route('customers.view', array('customer_id' => $quotation->customer->id)) }}" target="_blank" class="button tiny">{{ $quotation->customer->customer_name }}</a>
                @else
                    NA
                @endif
            </td>
            <td>
                @if(is_object($quotation->quotedBy))
                        {{ $quotation->quotedBy->getEmployeeFullName() }}
                @endif
            </td>
            <td>
                {{--@if(in_array($quotation->quotation_status, array('New','Updated'--}}
                {{--)))--}}
                    {{--{{ $quotation->quotation_status }}--}}
                {{--@else--}}
                    <select name="quotation_status" id="quotation_status_{{ $quotation->id }}" onchange="ajaxUpdateQuotationStatus('{{ $quotation->id }}', this.value);;">
                        @foreach(Quotation::$quotation_status as $quotation_status )
                            @if($quotation_status != 'Updated')
                                <option value="{{ $quotation_status }}" {{ $quotation->quotation_status == $quotation_status? "selected": ""  }}>{{ $quotation_status }}</option>
                            @endif
                        @endforeach
                    </select>
                {{--@endif--}}
            </td>
            <td>{{ $quotation->quoted_datetime }}</td>
            {{--<td>{{ $quotation->sub_total }}</td>--}}
            {{--<td>{{ $quotation->discount_percentage }}</td>--}}
            {{--<td>{{ $quotation->discount_value }}</td>--}}
            {{--<td>{{ $quotation->taxes }}</td>--}}
            <td>{{ NumberUtilities::formatNumberWithComma($quotation->net_total) }}</td>
            @if(Quotation::checkIfAuthorizedToEditQuotation())
                <td>
                    @if($quotation->quotation_status != 'Updated')
                        <button class="button tiny float-right" type="button" data-open="reveal_edit_quotation_{{ $quotation->id }}">Edit</button>
                    @endif
                    <div class="reveal large" id="reveal_edit_quotation_{{ $quotation->id }}" name="reveal_edit_quotation" data-reveal>
                                    <div class="panel-content">
                                        <div class="row">
                                            <div class="large-12 columns">
                                                @include('quotations._partials.edit_quotation_form', [
                                                                 'quotation' => $quotation,
                                                                 'quotation_use_for' => 'edit_quotation'
                                                         ])
                                            </div>
                                        </div>
                                        <button class="close-button" data-close aria-label="Close modal" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                    </div>
                </td>
            @endif

            @if(Quotation::checkIfAuthorizedToSendQuotation($quotation))
                <td>
                    @if($quotation->quotation_status == 'New')
                        <a data-open="quotation_send_for_approval_{{ $quotation->id }}" class="button tiny success">Send for Approval</a>
                        <div class="reveal tiny" id="quotation_send_for_approval_{{ $quotation->id }}" data-reveal>
                            @include('quotations._partials.send_quotation_for_approval')
                        </div>
                    @else
                        <p>Sent.</p>
                    @endif
                </td>
            @else
                <td></td>
            @endif

            <td>
                {{  link_to_route('quotations.generate_pdf', 'Generate PDF >', array('quotation_id' => $quotation->id), array('class' => 'button tiny warning', 'target' => '_blank')) }}
            </td>
            <td>
                 <button class="button tiny float-right" type="button" data-open="reveal_attachment_{{ $quotation->id }}">Attachments</button>
                <div class="reveal large" id="reveal_attachment_{{ $quotation->id }}" name="reveal_attachment" data-reveal>
                                <div class="panel-heading">
                                        <h3>Attachments</h3>
                                        <div class="row">
                                                <div class="large-12 columns">
                                                            @include('quotations._ajax_partials.quotation_attachments_list')
                                                </div>
                                        </div>
                                </div>
                                <div class="panel-content">
                                        <button class="close-button" data-close aria-label="Close modal" type="button">
                                                <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                </div>
            </td>
        </tr>
    @endforeach
</table>

<script>
    function ajaxUpdateQuotationStatus(quotation_id, status) {
        $.post("/quotations/ajax/update-quotation-status",
            {
                quotation_id: quotation_id,
                status: status
            },
            function (data, status) {

                $.notify(data, "success");

            });
    }
</script>

