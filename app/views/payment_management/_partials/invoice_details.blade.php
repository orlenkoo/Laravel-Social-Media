<table width="100%">
    <tr>
        <th>
            Item No
        </th>
        <th>
            Item
        </th>
        <th>
            Description
        </th>
        <th>
            Amount
        </th>
    </tr>
    <?php $count = 0; ?>
    @foreach($invoice->web360OrganizationInvoiceDetailRecords as $invoice_details)
    <tr>
        <td>
            {{ ++$count }}
        </td>
        <td>
            {{ $invoice_details->item }}
        </td>
        <td>
            {{ $invoice_details->description }}
        </td>
        <td>
            {{ $invoice_details->amount }}
        </td>
    </tr>
    @endforeach
    <tr>
        <th colspan="2">

        </th>
        <th>
            Gross Total
        </th>
        <th>
            {{ $invoice->gross_total }}
        </th>
    </tr>
    <tr>
        <th colspan="2">

        </th>
        <th>
            Taxes Total
        </th>
        <th>
            {{ $invoice->taxes }}
        </th>
    </tr>
    <tr>
        <th colspan="2">

        </th>
        <th>
            Discount
        </th>
        <th>
            {{ $invoice->discount }}
        </th>
    </tr>
    <tr>
        <th colspan="2">

        </th>
        <th>
            Net Total
        </th>
        <th>
            {{ $invoice->net_total }}
        </th>
    </tr>
</table>