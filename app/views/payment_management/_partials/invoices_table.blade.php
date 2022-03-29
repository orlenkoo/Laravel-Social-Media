<table>
    <tr>
        <th>Invoice Number</th>
        <th>Date</th>
        <th>Description</th>
        <th>Gross Total</th>
        <th>Taxes</th>
        <th>Discount</th>
        <th>Net Total</th>
        <th>Payment Status</th>
        <th>Details</th>
    </tr>
    @foreach($invoices as $invoice)
        <tr>
            <td>{{ $invoice->invoice_number }}</td>
            <td>{{ $invoice->invoice_date }}</td>
            <td>{{ $invoice->title }}</td>
            <td>{{ $invoice->gross_total }}</td>
            <td>{{ $invoice->taxes }}</td>
            <td>{{ $invoice->discount }}</td>
            <td>{{ $invoice->net_total }}</td>
            <td>{{ $invoice->status }}</td>
            <td>
                <button class="button tiny" type="button" data-open="popup_invoice_details_{{ $invoice->id }}">View ></button>
                <div class="reveal small" id="popup_invoice_details_{{ $invoice->id }}" name="popup_invoice_details_{{ $invoice->id }}" data-reveal>
                    <div class="panel-content">
                        <div class="row">
                            <div class="large-12 columns">
                                @include('payment_management._partials.invoice_details')
                            </div>
                        </div>
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
</table>

