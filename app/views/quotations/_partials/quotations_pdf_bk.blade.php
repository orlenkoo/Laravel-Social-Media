@extends('layouts.print_pdf')

@section('content')


    <table style="border: 1px solid #000;">
        <tr>
            <td>
                <h1>Job Quotation</h1>
                <p>{{ $quotation->customer->organization->organization }}</p>
                <p>{{ date('Y-m-d') }}</p>
            </td>
            <td>
                <img src="{{asset('assets/img/web360_logo2x.png')}}" height="300px" width="300px" >
            </td>
        </tr>
    </table>

    <table style="border: 1px solid #000;">
        <tr>
            <td>
                <p>Project Quote: {{ $quotation->project_quote }}</p>
                <p>Company Name: {{ $quotation->company_name }}</p>
                <p>Address: {{ $quotation->address }}</p>
            </td>
            <td>
                <p>Contact Person: {{ $quotation->contact_person }}</p>
                <p>Email Address: {{ $quotation->email }}</p>
                <p>Tel: {{ $quotation->phone_number }}</p>
                <p>Fax: {{ $quotation->fax }}</p>
            </td>
        </tr>
    </table>





    <table>
        <tr>
            <th width="80%" style="border: 1px solid #000;">
                Project Description
            </th>
            <th width="20%" style="border: 1px solid #000;">
                Cost
            </th>
        </tr>


        <?php
        $quotation_items = $quotation->quotationItems;
        $i = 0;
        ?>

        @foreach($quotation_items as $quotation_item)
            @if($quotation_item->cost != '')
                <tr>
                    <td width="80%" style="border-bottom: 1px solid #000;">
                        {{ $quotation_item->description }}
                    </td>
                    <td width="20%" style="border-bottom: 1px solid #000;">
                        {{ $quotation_item->cost }}
                    </td>
                </tr>
            @endif
        @endforeach
        <?php
        $tax_percentage = 0;
        if (is_object($quotation->customer->organization->organizationPreferences)) {
            if ($quotation->customer->organization->organizationPreferences->tax_percentage != '' && $quotation->customer->organization->organizationPreferences->tax_percentage != null) {
                $tax_percentage = $quotation->customer->organization->organizationPreferences->tax_percentage;
            }

        }
        ?>
        <tr>
            <th style="border-bottom: 1px solid #000;">
                Sub Total:
            </th>
            <td style="border: 1px solid #000;">
                {{ $quotation->sub_total }}
            </td>
        </tr>
        <tr>
            <th style="border-bottom: 1px solid #000;">
                Discount Percentage:
            </th>
            <td style="border: 1px solid #000;">
                {{ $quotation->discount_percentage }}
            </td>
        </tr>
        <tr>
            <th style="border-bottom: 1px solid #000;">
                Discount Value:
            </th>
            <td style="border: 1px solid #000;">
                {{ $quotation->discount_value }}
            </td>
        </tr>
        <tr>
            <th style="border-bottom: 1px solid #000;">
                Taxes ({{ $tax_percentage }}%):
            </th>
            <td style="border: 1px solid #000;">
                {{ $quotation->taxes }}
            </td>
        </tr>
        <tr>
            <th style="border-bottom: 1px solid #000;">
                Net Total:
            </th>
            <td style="border: 1px solid #000;">
                {{ $quotation->net_total }}
            </td>
        </tr>
    </table>






    <h2>Payment Terms:</h2>
    <p>{{ $quotation->payment_terms }}</p>

    @if(is_object($quotation->customer->organization->organizationPreferences))
        <p>{{ $quotation->customer->organization->organizationPreferences->payment_terms }}</p>
    @endif

    @if(is_object($quotation->customer->organization->organizationPreferences))
        <h2>*Terms & Conditions(Initial Box Below):</h2>
        <p>  {{ $quotation->customer->organization->organizationPreferences->terms_and_conditions }}  </p>
        <table width="20%">
            <tr>
                <td style="border: 1px solid #ccc" height="50"></td>
            </tr>
        </table>
    @endif





    <h2>Authorisation(Complete & Sign Below)</h2>
    <table>
        <tr>
            <td height="50" style="border: 1px solid #ccc"></td>
            <td></td>
            <td style="border: 1px solid #ccc"></td>
            <td></td>
            <td style="border: 1px solid #ccc"></td>
            <td></td>
            <td style="border: 1px solid #ccc"></td>
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




@stop