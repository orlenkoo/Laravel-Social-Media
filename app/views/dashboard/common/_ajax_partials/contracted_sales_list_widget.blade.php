<table width="100%">
    <tr>
        <th>S.No</th>
        <th>Lead Source</th>
        <th>Campaign Auto Tag</th>
        <th>Company</th>
        <th>Sales Closed By</th>
        <th>Achieved Sales</th>
    </tr>
    <?php $id = 1; $total_achieved_sales = 0;?>
    @foreach($contracted_sales_list as $contracted_sale)
        <tr>
            <td>
                {{ $id++ }}
            </td>
            <td>
                {{ array_key_exists($contracted_sale->lead_source, Lead::$lead_sources)? Lead::$lead_sources[$contracted_sale->lead_source]: "NA"  }}
            </td>
            <td>
                {{ $contracted_sale->auto_tagged_campaign }}
            </td>
            <td>
                {{ $contracted_sale->company_name }}
            </td>
            <td>
                {{ $contracted_sale->sales_closed_by }}
            </td>
            <td>
                {{ NumberUtilities::formatNumberWithComma($contracted_sale->achieved_sales) }}
            </td>
        </tr>
        <?php $total_achieved_sales += $contracted_sale->achieved_sales; ?>
    @endforeach
    <tr>
        <th>
            Total
        </th>
        <td>
        </td>
        <td>
        </td>
        <td>
        </td>
        <td>
        </td>
        <th>
            {{ NumberUtilities::formatNumberWithComma($total_achieved_sales) }}
        </th>
    </tr>
</table>