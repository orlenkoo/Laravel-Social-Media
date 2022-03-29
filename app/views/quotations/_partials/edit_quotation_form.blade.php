{{ Form::open(array('route' => 'quotations.ajax.update_quotation', 'ajax' => 'true', 'id' => 'edit_quotation_form_'.$quotation->id ,'autocomplete' => 'off')) }}

    <input type="hidden" name="quotation_id" id="update_quotation_form_quotation_id_{{ $quotation->id }}" value="{{ $quotation->id }}">
    <input type="hidden" name="customer_id" id="update_quotation_form_customer_id_{{ $quotation->id }}" value="{{ $quotation->customer_id }}">
    <input type="hidden" name="lead_id" id="update_quotation_form_lead_id_{{ $quotation->id }}" value="{{ $quotation->lead_id }}">

    <div class="panel-heading">
        <div class="row">
            <div class="large-12 columns">
                <h4>Edit Job Quotation</h4>
            </div>
        </div>
    </div>

    <div class="panel-content">
        <div class="row">
            <div class="large-12 columns">
                <p><strong>Quotation Details</strong></p>
                <p>{{ $quotation->customer->organization->organization }}</p>
                <p>{{ date('Y-m-d') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="large-6 columns">
                <label for="">
                    Project Quote*:
                    <input type="text" name="project_quote" id="update_quotation_form_project_quote_{{ $quotation->id }}" value="{{ $quotation->project_quote }}" data-validation="required">
                </label>
            </div>
            <div class="large-6 columns">
                <label for="">
                    Company Name*:
                    <input type="text" name="company_name" id="update_quotation_form_company_name_{{ $quotation->id }}" value="{{ $quotation->company_name }}" data-validation="required">
                </label>
            </div>
        </div>

        <div class="row">
            <div class="large-12 columns">
                <label for="">
                    Address:
                    <textarea name="address" id="update_quotation_form_address_{{ $quotation->id }}" cols="30" rows="2">{{ $quotation->address }}</textarea>
                </label>
            </div>
        </div>

        <div class="row">
            <div class="large-6 columns">
                <label for="">
                    Contact Person*:
                    <input type="text" name="contact_person" id="update_quotation_form_contact_person_{{ $quotation->id }}" value="{{ $quotation->contact_person }}" data-validation="required">
                </label>
            </div>
            <div class="large-6 columns">
                <label for="">
                    Email Address*:
                    <input type="text" name="email_address" id="update_quotation_form_email_address_{{ $quotation->id }}" value="{{ $quotation->email }}" data-validation="required">
                </label>
            </div>
        </div>
        <div class="row">
            <div class="large-6 columns">
                <label for="">
                    Tel:
                    <input type="text" name="phone_number" id="update_quotation_form_phone_number_{{ $quotation->id }}" value="{{ $quotation->phone_number }}">
                </label>
            </div>
            <div class="large-6 columns">
                <label for="">
                    Fax:
                    <input type="text" name="fax" id="update_quotation_form_fax_{{ $quotation->id }}" value="{{ $quotation->fax }}">
                </label>
            </div>
        </div>


        <div class="row">

            <div class="large-12 columns">
                <p><strong>Order Summary</strong></p>
                <p><input type="button" value="Add Item" class="button tiny success" onclick="add_quotation_item_for_update_{{ $quotation->id }}()"></p>
                <?php
                $tax_percentage = 0;
                if(is_object($quotation->customer->organization->organizationPreferences)) {
                    if($quotation->customer->organization->organizationPreferences->tax_percentage != '' && $quotation->customer->organization->organizationPreferences->tax_percentage != null) {
                        $tax_percentage = $quotation->customer->organization->organizationPreferences->tax_percentage;
                    }

                }
                ?>
                <table id="update_quotation_items_table_{{ $quotation->id }}" class="basic-table">
                    <tr>
                        <th width="10%">
                            &nbsp;
                        </th>
                        <th width="30%">
                            Description
                        </th>
                        <th width="10%">
                            Unit of Measure
                        </th>
                        <th width="10%">
                            No Of Units
                        </th>
                        <th width="10%">
                            Unit Cost
                        </th>
                        <th width="10%" colspan="2">
                            Taxable ({{ $tax_percentage }}%)
                        </th>
                        <th width="20%">
                            Cost
                        </th>
                    </tr>


                    <?php
                    $quotation_items = $quotation->quotationItems;
                    $i = 0;
                    ?>

                    @foreach($quotation_items as $quotation_item)
                        @if($quotation_item->cost != '')
                            <tr class="">
                                <td width="10%">
                                    @if($i != 0)
                                        <input type="button" class="remove_quotation_item_for_update_{{ $quotation->id }} button tiny alert" value="X">
                                    @endif
                                </td>
                                <td width="30%">
                                    <input type="text" name="descriptions[]" value="{{ $quotation_item->description }}" id="descriptions">
                                </td>
                                <td width="10%">
                                    <input type="text" name="unit_of_measures[]" value="{{ $quotation_item->unit_of_measure }}" class="update_quotation_form_unit_of_measure_{{ $quotation->id }}" id="unit_of_measure">
                                </td>
                                <td width="10%">
                                    <input type="text" name="no_of_units[]" value="{{ $quotation_item->no_of_units }}" onchange="update_quotation_calculate_quotation_item_cost_{{ $quotation->id }}(this);update_quotation_calculateQuotationTotals_{{ $quotation->id }}()"  class="numbersonly update_quotation_form_no_of_units_{{ $quotation->id }}" id="no_of_units">
                                </td>
                                <td width="10%">
                                    <input type="text" name="unit_costs[]" value="{{ $quotation_item->unit_cost }}" onchange="update_quotation_calculate_quotation_item_cost_{{ $quotation->id }}(this);update_quotation_calculateQuotationTotals_{{ $quotation->id }}()"  class="numbersonly update_quotation_form_unit_costs_{{ $quotation->id }}" id="unit_costs">
                                </td>
                                <td width="2%">
                                    <input type="hidden" name="taxables[]" class="update_quotation_form_taxables_{{ $quotation->id }}" value="{{ $quotation_item->taxable }}">
                                    <input type="checkbox" name="taxables_check_box[]" onchange="update_quotation_calculate_quotation_item_cost_{{ $quotation->id }}(this);update_quotation_calculateQuotationTotals_{{ $quotation->id }}()"  class="update_quotation_form_taxables_check_box_{{ $quotation->id }}" id="taxables" {{ $quotation_item->taxable? 'checked': '' }}>
                                </td>
                                <td width="8%">
                                    <input type="text" name="taxes[]" onchange="update_quotation_calculate_quotation_item_cost_{{ $quotation->id }}(this);update_quotation_calculateQuotationTotals_{{ $quotation->id }}()"  class="numbersonly update_quotation_form_taxes_{{ $quotation->id }}" readonly id="taxes" value="{{ $quotation_item->tax }}">
                                </td>
                                <td width="20%">
                                    <input type="text" name="costs[]" value="{{ $quotation_item->cost }}" onchange="update_quotation_calculateQuotationTotals_{{ $quotation->id }}()" class="numbersonly update_quotation_form_costs_{{ $quotation->id }}" id="costs" readonly>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endif
                    @endforeach


                </table>





                <table class="basic-table">
                    <tr>
                        <th width="80%" class="text-right">
                            Sub Total
                        </th>
                        <td width="20%">
                            <input type="text" name="sub_total" id="update_quotation_form_sub_total_{{ $quotation->id }}" value="{{ $quotation->sub_total }}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th width="80%" class="text-right">
                            Discount Percentage
                        </th>
                        <td width="20%">
                            <input type="text" name="discount_percentage" class="numbersonly" id="update_quotation_form_discount_percentage_{{ $quotation->id }}" value="{{ $quotation->discount_percentage }}" onchange="update_quotation_calculateDiscount_{{ $quotation->id }}('amount', true);">
                        </td>
                    </tr>
                    <tr>
                        <th width="80%" class="text-right">
                            Discount Value
                        </th>
                        <td width="20%">
                            <input type="text" name="discount_value" class="numbersonly" id="update_quotation_form_discount_value_{{ $quotation->id }}" value="{{ $quotation->discount_value }}" onchange="update_quotation_calculateDiscount_{{ $quotation->id }}('percentage', true);">
                        </td>
                    </tr>
                    <tr>
                        <th width="80%" class="text-right">
                            Total Taxes
                        </th>
                        <td width="20%">
                            <input type="text" name="total_taxes" id="update_quotation_form_total_taxes_{{ $quotation->id }}" value="{{ $quotation->total_taxes }}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th width="80%" class="text-right">
                            Nett Total
                        </th>
                        <td width="20%">
                            <input type="text" name="net_total" id="update_quotation_form_net_total_{{ $quotation->id }}" value="{{ $quotation->net_total }}" readonly>
                        </td>
                    </tr>
                </table>

                <script>

                    function update_quotation_calculateQuotationTotals_{{ $quotation->id }}() {
                        var quotation_item_costs = document.getElementsByClassName("update_quotation_form_costs_{{ $quotation->id }}");
                        var quotation_item_taxes = document.getElementsByClassName("update_quotation_form_taxes_{{ $quotation->id }}");
                        var sub_total=0;
                        for(var i=0;i<quotation_item_costs.length;i++){
                            if(quotation_item_costs[i].value)
                                sub_total += +quotation_item_costs[i].value;
                        }
                        document.getElementById('update_quotation_form_sub_total_{{ $quotation->id }}').value = parseFloat(sub_total).toFixed(2);

                        var total_tax=0;
                        for(var i=0;i<quotation_item_taxes.length;i++){
                            if(quotation_item_taxes[i].value)
                                total_tax += +quotation_item_taxes[i].value;
                        }
                        document.getElementById('update_quotation_form_total_taxes_{{ $quotation->id }}').value = parseFloat(total_tax).toFixed(2);


                        update_quotation_calculateDiscount_{{ $quotation->id }}('amount', false);

                        var discount_value = $('#update_quotation_form_discount_value_{{ $quotation->id }}').val();




                        console.log('discount_value_ -- '+discount_value);

                        // because tax is calculated on the discounted total
                        var sub_total_before_tax = +sub_total - +discount_value;

                        var net_total = +sub_total_before_tax + +total_tax;

                        document.getElementById('update_quotation_form_net_total_{{ $quotation->id }}').value = parseFloat(net_total).toFixed(2);
                    }

                    function update_quotation_calculateDiscount_{{ $quotation->id }}(calculation_type, recalculate_totals) {
                        var sub_total = $('#update_quotation_form_sub_total_{{ $quotation->id }}').val();
                        var discount_percentage = $('#update_quotation_form_discount_percentage_{{ $quotation->id }}').val();
                        var discount_value = $('#update_quotation_form_discount_value_{{ $quotation->id }}').val();

                        if(sub_total != 0) {

                            if(!sub_total) {
                                sub_total = 0;
                            }

                            if(calculation_type == 'percentage') {
                                if(sub_total > 0) {
                                    discount_percentage = +discount_value / +sub_total * 100;
                                }
                            } else if (calculation_type == 'amount') {
                                discount_value = +sub_total / 100 * +discount_percentage;
                            }

                            $('#update_quotation_form_discount_percentage_{{ $quotation->id }}').val(parseFloat(discount_percentage).toFixed(2));
                            $('#update_quotation_form_discount_value_{{ $quotation->id }}').val(parseFloat(discount_value).toFixed(2));

                            // to stop the infinite loop happening
                            if(recalculate_totals) {
                                update_quotation_calculateQuotationTotals_{{ $quotation->id }}();
                            }

                        }
                    }

                    $(".remove_quotation_item_for_update_{{ $quotation->id }}").click(function() {
                        $(this).closest('tr').remove();
                        update_quotation_calculateQuotationTotals_{{ $quotation->id }}();
                        return false;
                    });

                    function add_quotation_item_for_update_{{ $quotation->id }}() {
                        $('#update_quotation_items_table_{{ $quotation->id }} tr:last').after('<tr class=""><td width="10%"><input type="button" class="remove_quotation_item_for_update_{{ $quotation->id }} button tiny alert" value="X"></td><td width="30%"><input type="text" name="descriptions[]" id="descriptions"></td><td width="10%"><input type="text" name="unit_of_measures[]" class="update_quotation_form_unit_of_measure_{{ $quotation->id }}" id="unit_of_measure"></td><td width="10%"><input type="text" name="no_of_units[]" onchange="update_quotation_calculate_quotation_item_cost_{{ $quotation->id }}(this);update_quotation_calculateQuotationTotals_{{ $quotation->id }}()"  class="numbersonly update_quotation_form_no_of_units_{{ $quotation->id }}" id="no_of_units"></td><td width="10%"><input type="text" name="unit_costs[]" onchange="update_quotation_calculate_quotation_item_cost_{{ $quotation->id }}(this);update_quotation_calculateQuotationTotals_{{ $quotation->id }}()"  class="numbersonly update_quotation_form_unit_costs_{{ $quotation->id }}" id="unit_costs"></td> <td width="2%"> <input type="hidden" name="taxables[]" class="update_quotation_form_taxables_{{ $quotation->id }}" value="0"> <input type="checkbox" name="taxables_check_box[]" onchange="update_quotation_calculate_quotation_item_cost_{{ $quotation->id }}(this);update_quotation_calculateQuotationTotals_{{ $quotation->id }}()"  class="update_quotation_form_taxables_check_box_{{ $quotation->id }}" id="taxables"> </td> <td width="8%"> <input type="text" name="taxes[]" onchange="update_quotation_calculate_quotation_item_cost_{{ $quotation->id }}(this);update_quotation_calculateQuotationTotals_{{ $quotation->id }}()"  class="numbersonly update_quotation_form_taxes_{{ $quotation->id }}" readonly id="taxes"></td><td width="20%"><input type="text" name="costs[]" onchange="update_quotation_calculateQuotationTotals_{{ $quotation->id }}()" class="numbersonly update_quotation_form_costs_{{ $quotation->id }}" id="costs" readonly></td></tr>');
                        return false;
                    }

                    function update_quotation_calculate_quotation_item_cost_{{ $quotation->id }}(object) {

                        var no_of_units = $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".update_quotation_form_no_of_units_{{ $quotation->id }}")     // Gets a descendent with class="nr"
                            .val();

                        if(isNaN(no_of_units)) {
                            no_of_units = 0;
                            $(object).closest("tr")   // Finds the closest row <tr>
                                .find(".update_quotation_form_no_of_units_{{ $quotation->id }}")     // Gets a descendent with class="nr"
                                .val(no_of_units);
                        }

                        var unit_cost = $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".update_quotation_form_unit_costs_{{ $quotation->id }}")     // Gets a descendent with class="nr"
                            .val();

                        if(isNaN(unit_cost)) {
                            unit_cost = 0;
                            $(object).closest("tr")   // Finds the closest row <tr>
                                .find(".update_quotation_form_unit_costs_{{ $quotation->id }}")     // Gets a descendent with class="nr"
                                .val(parseFloat(unit_cost).toFixed(2));
                        }

                        var taxable = $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".update_quotation_form_taxables_check_box_{{ $quotation->id }}")     // Gets a descendent with class="nr"
                            .prop('checked')? 1: 0;


                        $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".update_quotation_form_taxables_{{ $quotation->id }}")     // Gets a descendent with class="nr"
                            .val(taxable);

                        var tax_percentage = taxable? '{{ $tax_percentage }}': 0;

                        console.log('no_of_units -- ' +no_of_units);
                        console.log('unit_cost -- ' +unit_cost);

                        var cost = +no_of_units * +unit_cost;


                        console.log('cost -- ' +cost);

                        $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".update_quotation_form_costs_{{ $quotation->id }}")     // Gets a descendent with class="nr"
                            .val(parseFloat(cost).toFixed(2));

                        var tax = +cost * +tax_percentage / 100;

                        $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".update_quotation_form_taxes_{{ $quotation->id }}")     // Gets a descendent with class="nr"
                            .val(parseFloat(tax).toFixed(2));

                    }


                </script>


                <p><strong>Payment Terms</strong></p>
                <table class="basic-table">
                    <tr>
                        <td colspan="2">
                            <input type="text" value="100% upon confirmation." name="payment_terms" id="update_quotation_form_payment_terms_{{ $quotation->id }}" value="{{ $quotation->payment_terms }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            @if(is_object($quotation->customer->organization->organizationPreferences))
                                {{ $quotation->customer->organization->organizationPreferences->payment_terms }}
                            @endif
                        </td>
                    </tr>
                </table>

                @if(is_object($quotation->customer->organization->organizationPreferences))
                <p><strong>*Terms & Conditions</strong></p>
                <table>
                        <tr>
                            <td colspan="2">
                                {{ $quotation->customer->organization->organizationPreferences->terms_and_conditions }}
                            </td>
                        </tr>
                </table>
                @endif
            </div>
        </div>
    </div>

    <div class="panel-footer">
        <div class="row save_bar">
            <div class="large-6 columns">
                &nbsp;
            </div>
            <div class="large-2 columns text-right">
                <button class="button tiny alert" data-close aria-label="Cancel" type="button">
                    Cancel
                </button>
            </div>
            <div class="large-2 columns text-right">
                {{  link_to_route('quotations.generate_pdf', 'Generate PDF >
                ', array('quotation_id' => $quotation->id), array('class' => 'button tiny warning', 'target' => '_blank')) }}
            </div>
            <div class="large-2 columns text-right">
                {{ Form::submit('Edit and submit new', array("class" => "button success tiny", "id" => "update_quotation_form_save_button_".$quotation->id)) }}
            </div>
        </div>
        <div class="row loading_animation" style="display: none;">
            <div class="large-12 columns text-center">
                {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
            </div>
        </div>
    </div>



{{ Form::close() }}

<script>

    $(document).ready(function() {
        setUpAjaxForm('<?php echo 'edit_quotation_form_'.$quotation->id; ?>', 'update', 'reveal_edit_quotation_{{ $quotation->id }}', function(){
            @if($quotation_use_for == 'leads_time_line')
                 ajaxLoadDashboardLeadTimeLine(1);
            @else
                  ajaxLoadQuotationsList(1);
            @endif
            }
        );
    });



</script>