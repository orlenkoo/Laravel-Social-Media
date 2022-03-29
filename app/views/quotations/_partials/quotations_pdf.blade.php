<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Job Quotation - {{ $quotation->project_quote }}</title>

    <style>
        @page {

        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        p {
            font-size: 14px;
        }

        th, td {
            font-size: 14px;
        }

        th {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
    </style>
</head>
<body>
<table style="border: 1px solid #ddd; margin-bottom: 10px;" width="100%" cellspacing="0" cellpadding="10">
    <tr>
        <td style="text-align: center; background: #eee;" colspan="2"><h1>Job Quotation</h1></td>
    </tr>
    <tr>
        <td style="text-align: left">
            <p>{{ $quotation->customer->organization->organization }} {{ $quotation->customer->organization->business_registration_number !=''? '('.$quotation->customer->organization->business_registration_number.')' : '' }}</p>
            <p>{{ $quotation->customer->organization->getAddress() }}</p>
            <p>{{ date('Y-m-d') }}</p>
        </td>
        <td style="text-align: right">
            <?php
            $logo_url = asset('assets/img/web360_logo2x.png');

            if(is_object($quotation->customer->organization->organizationPreferences)) {
                if($quotation->customer->organization->organizationPreferences->logo_image_url != '') {
                    $logo_url = $quotation->customer->organization->organizationPreferences->logo_image_url;
                }
            }
            ?>
            <img src="{{ $logo_url }}" height="50px" style="padding: 30px" >

        </td>
    </tr>
</table>

<table style="border: 1px solid #ddd; margin-bottom: 10px;" width="100%" cellspacing="0" cellpadding="10">
    <tr>
        <td style="text-align: left">
            <p>Project Quote: {{ $quotation->project_quote }}</p>
            <p>Company Name: {{ $quotation->company_name }}</p>
            <p>Address: {{ $quotation->address }}</p>
        </td>
        <td style="text-align: right">
            <p>Contact Person: {{ $quotation->contact_person }}</p>
            <p>Email Address: {{ $quotation->email }}</p>
            <p>Tel: {{ $quotation->phone_number }}</p>
            <p>Fax: {{ $quotation->fax }}</p>
        </td>
    </tr>
</table>

<?php
$tax_percentage = 0;
if (is_object($quotation->customer->organization->organizationPreferences)) {
    if ($quotation->customer->organization->organizationPreferences->tax_percentage != '' && $quotation->customer->organization->organizationPreferences->tax_percentage != null) {
        $tax_percentage = $quotation->customer->organization->organizationPreferences->tax_percentage;
    }

}
?>
<table style=" margin-bottom: 10px;" width="100%" cellspacing="0" cellpadding="10">
    <tr>
        <th width="40%" style="border: 1px solid #ddd; text-align: left;">
            <strong>Description</strong>
        </th>
        <th width="10%" style="border: 1px solid #ddd; text-align: center;">
            <strong>Unit of Measure</strong>
        </th>
        <th width="10%" style="border: 1px solid #ddd; text-align: center;">
            <strong>No Of Units</strong>
        </th>
        <th width="10%" style="border: 1px solid #ddd; text-align: center;">
            <strong>Unit Cost</strong>
        </th>
        <th width="10%" colspan="2" style="border: 1px solid #ddd; text-align: center;">
            Tax ({{ $tax_percentage }}%)
        </th>
        <th width="20%" style="border: 1px solid #ddd; text-align: right;">
            <strong>Cost</strong>
        </th>
    </tr>

    <?php
    $quotation_items = $quotation->quotationItems;
    $i = 0;
    ?>

    @foreach($quotation_items as $quotation_item)
        @if($quotation_item->cost != '')
            <tr>
                <td width="40%" style="border-bottom: 1px solid #ddd;">
                    {{ $quotation_item->description }}
                </td>
                <td width="10%" style="border-bottom: 1px solid #ddd;">
                    {{ $quotation_item->unit_of_measure }}
                </td>
                <td width="10%" style="border-bottom: 1px solid #ddd;">
                    {{ $quotation_item->no_of_units }}
                </td>
                <td width="10%" style="border-bottom: 1px solid #ddd;">
                    {{ NumberUtilities::formatNumberWithComma($quotation_item->unit_cost) }}
                </td>
                <td width="10%" colspan="2">
                    {{ NumberUtilities::formatNumberWithComma($quotation_item->tax) }}
                </td>
                <td width="20%" style="border-bottom: 1px solid #ddd; text-align: right;">
                    {{ NumberUtilities::formatNumberWithComma($quotation_item->cost) }}
                </td>
            </tr>
        @endif
    @endforeach
    <tr>
        <th colspan="6" style="border-bottom: 1px solid #ddd; text-align: right;">
            <strong>Sub Total:</strong>
        </th>
        <td style="border: 1px solid #ddd; text-align: right;">
            {{ NumberUtilities::formatNumberWithComma($quotation->sub_total) }}
        </td>
    </tr>
    <tr>
        <th colspan="6" style="border-bottom: 1px solid #ddd; text-align: right;">
            <strong>Discount Percentage:</strong>
        </th>
        <td style="border: 1px solid #ddd; text-align: right;">
            {{ $quotation->discount_percentage }}
        </td>
    </tr>
    <tr>
        <th colspan="6" style="border-bottom: 1px solid #ddd; text-align: right;">
            <strong>Discount Value:</strong>
        </th>
        <td style="border: 1px solid #ddd; text-align: right;">
            {{ NumberUtilities::formatNumberWithComma($quotation->discount_value) }}
        </td>
    </tr>
    <tr>
        <th colspan="6" style="border-bottom: 1px solid #ddd; text-align: right;">
            <strong>Total Taxes:</strong>
        </th>
        <td style="border: 1px solid #ddd; text-align: right;">
            {{ NumberUtilities::formatNumberWithComma($quotation->total_taxes) }}
        </td>
    </tr>
    <tr>
        <th colspan="6" style="border-bottom: 1px solid #ddd; text-align: right;">
            <strong>Nett Total:</strong>
        </th>
        <td style="border: 1px solid #ddd; text-align: right;">
            {{  NumberUtilities::formatNumberWithComma($quotation->net_total) }}
        </td>
    </tr>
</table>

<h4 style="page-break-before: always;">Payment Terms:</h4>
<p>{{ $quotation->payment_terms }}</p>

@if(is_object($quotation->customer->organization->organizationPreferences))
    <span style="font-size: 12px !important;"><?php echo $quotation->customer->organization->organizationPreferences->payment_terms;  ?></span>
@endif

@if(is_object($quotation->customer->organization->organizationPreferences))
    <h4 style="margin-top: 20px;">*Terms & Conditions:</h4>
    <span style="font-size: 12px !important;margin-bottom: 10px;"><?php echo $quotation->customer->organization->organizationPreferences->terms_and_conditions;  ?></span>
@endif

<h4>Authorisation (Complete & Sign Below)</h4>
<table width="100%" cellspacing="0" cellpadding="10">
    <tr>
        <td width="20%" height="50" style="border-bottom: 1px solid #ccc"></td>
        <td width="5%"></td>
        <td width="20%" style="border-bottom: 1px solid #ccc"></td>
        <td width="5%"></td>
        <td width="20%"style="border-bottom: 1px solid #ccc"></td>
        <td width="5%"></td>
        <td width="20%" style="border-bottom: 1px solid #ccc"></td>
        <td width="5%"></td>
    </tr>
    <tr>
        <td>Signature & Company Stamp</td>
        <td></td>
        <td>Name & Job Title</td>
        <td></td>
        <td>Business Name</td>
        <td></td>
        <td>Date</td>
    </tr>
</table>

</body>
</html>
