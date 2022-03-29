<?php
    $unique_id = uniqid();
    $employee = Employee::findOrFail(Session::get('user-id'));
    if(!is_null($lead)){
        $customer_id = $lead->customer->id;
    }
    $customer = Customer::findOrFail($customer_id);
?>

{{ Form::open(array('route' => 'quotations.ajax.save_new_quotation', 'ajax' => 'true', 'id' => 'new_quotation_form_'.$unique_id,'autocomplete' => 'off')) }}
<input type="hidden" name="lead_id" id="new_quotation_form_lead_id_{{ $unique_id }}" value="@if(is_null($lead)){{ 0 }}@else{{ $lead->id }}@endif">
<input type="hidden" name="customer_id" id="new_quotation_form_customer_id_{{ $unique_id }}" value="{{ $customer_id }}">

<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Job Quotation</h4>
        </div>
    </div>
</div>

    <div class="panel-content">
        <div class="row">
            <div class="large-12 columns">
                <p><strong>Quotation Details</strong></br>
                    {{ $employee->organization->organization }}</br>
                    {{ date('Y-m-d') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="large-6 columns">
                <label for="">
                    Project Quote*:
                    <input type="text" name="project_quote" id="new_quotation_form_project_quote_{{ $unique_id }}" data-validation="required">
                </label>
            </div>
            <div class="large-6 columns">
                <label for="">
                    Company Name*:
                    <input type="text" name="company_name" id="new_quotation_form_company_name_{{ $unique_id }}" value="{{ $customer->customer_name }}" data-validation="required">
                </label>
            </div>
        </div>

        <div class="row">
            <div class="large-12 columns">
                <label for="">
                    Address*:
                    <textarea name="address" id="new_quotation_form_address_{{ $unique_id }}" cols="30" rows="2" data-validation="required">{{ $customer->getAddress() }}</textarea>
                </label>
            </div>
        </div>

        <?php
            $primary_contact = Contact::getPrimaryContact($customer_id);
        ?>


            <div class="row">
                <div class="large-6 columns">
                    <label for="">
                        Contact Person*:
                        <input type="text" name="contact_person" id="new_quotation_form_contact_person_{{ $unique_id }}" value="{{ $primary_contact != null ? $primary_contact->getContactFullName(): "" }}" data-validation="required">
                    </label>
                </div>
                <div class="large-6 columns">
                    <label for="">
                        Email Address*:
                        <input type="text" name="email_address" id="new_quotation_form_email_address_{{ $unique_id }}" value="{{ $primary_contact != null ? $primary_contact->email: "" }}" data-validation="required">
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-6 columns">
                    <label for="">
                        Tel:
                        <input type="text" name="phone_number" id="new_quotation_form_phone_number_{{ $unique_id }}" value="{{ $primary_contact != null ? $primary_contact->phone_number: "" }}">
                    </label>
                </div>
                <div class="large-6 columns">
                    <label for="">
                        Fax:
                        <input type="text" name="fax" id="new_quotation_form_fax_{{ $unique_id }}">
                    </label>
                </div>
            </div>


        <div class="row">
            <div class="large-12 columns">
                <p><strong>Order Summary</strong></p>

                <p><input type="button" value="Add Item" class="button tiny success" onclick="add_quotation_item_{{ $unique_id }}()"></p>

                <?php
                $tax_percentage = 0;
                if(is_object($employee->organization->organizationPreferences)) {
                    if($employee->organization->organizationPreferences->tax_percentage != '' && $employee->organization->organizationPreferences->tax_percentage != null) {
                        $tax_percentage = $employee->organization->organizationPreferences->tax_percentage;
                    }

                }
                ?>

                <table id="quotation_items_table_{{ $unique_id }}" class="basic-table">
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
                    <tr class="">
                        <td width="10%">

                        </td>
                        <td width="30%">
                            <input type="text" name="descriptions[]" id="descriptions" class="add_new_quotation_form_description_{{ $unique_id }}">
                        </td>
                        <td width="10%">
                            <input type="text" name="unit_of_measures[]" class="add_new_quotation_form_unit_of_measure_{{ $unique_id }}" id="unit_of_measure">
                        </td>
                        <td width="10%">
                            <input type="text" name="no_of_units[]" onchange="add_new_quotation_calculate_quotation_item_cost_{{ $unique_id }}(this);new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="numbersonly add_new_quotation_form_no_of_units_{{ $unique_id }}" id="no_of_units">
                        </td>
                        <td width="10%">
                            <input type="text" name="unit_costs[]" onchange="add_new_quotation_calculate_quotation_item_cost_{{ $unique_id }}(this);new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="numbersonly add_new_quotation_form_unit_costs_{{ $unique_id }}" id="unit_costs">
                        </td>
                        <td width="2%">
                            <input type="hidden" name="taxables[]" class="add_new_quotation_form_taxables_{{ $unique_id }}" value="0">
                            <input type="checkbox" name="taxables_check_box[]" onchange="add_new_quotation_calculate_quotation_item_cost_{{ $unique_id }}(this);new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="add_new_quotation_form_taxables_check_box_{{ $unique_id }}" id="taxables">
                        </td>
                        <td width="8%">
                            <input type="text" name="taxes[]" onchange="add_new_quotation_calculate_quotation_item_cost_{{ $unique_id }}(this);new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="numbersonly add_new_quotation_form_taxes_{{ $unique_id }}" readonly id="taxes">
                        </td>
                        <td width="20%">
                            <input type="text" name="costs[]" onchange="new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="numbersonly add_new_quotation_form_cost_{{ $unique_id }}" id="costs" readonly>
                        </td>
                    </tr>

                </table>





                <table class="basic-table">
                    <tr>
                        <th width="80%" class="text-right">
                            Sub Total
                        </th>
                        <td width="20%">
                            <input type="text" name="sub_total" id="new_quotation_form_sub_total_{{ $unique_id }}" readonly value="0.00">
                        </td>
                    </tr>
                    <tr>
                        <th width="80%" class="text-right">
                            Discount Percentage
                        </th>
                        <td width="20%">
                            <input type="text" name="discount_percentage" class="numbersonly" id="new_quotation_form_discount_percentage_{{ $unique_id }}" value="0.00" onchange="new_quotation_calculateDiscount_{{ $unique_id }}('amount', true);">
                        </td>
                    </tr>
                    <tr>
                        <th width="80%" class="text-right">
                            Discount Value
                        </th>
                        <td width="20%">
                            <input type="text" name="discount_value" class="numbersonly" id="new_quotation_form_discount_value_{{ $unique_id }}" value="0.00" onchange="new_quotation_calculateDiscount_{{ $unique_id }}('percentage', true);">
                        </td>
                    </tr>
                    <tr>
                        <th width="80%" class="text-right">
                            Total Taxes
                        </th>
                        <td width="20%">
                            <input type="text" name="total_taxes" id="new_quotation_form_total_taxes_{{ $unique_id }}" readonly value="0.00">
                        </td>
                    </tr>
                    <tr>
                        <th width="80%" class="text-right">
                            Nett Total
                        </th>
                        <td width="20%">
                            <input type="text" name="net_total" id="new_quotation_form_net_total_{{ $unique_id }}" readonly value="0.00">
                        </td>
                    </tr>
                </table>

                <script>
                    function new_quotation_calculateQuotationTotals_{{ $unique_id }}() {
                        var quotation_item_costs = document.getElementsByClassName("add_new_quotation_form_cost_{{ $unique_id }}");
                        var quotation_item_taxes = document.getElementsByClassName("add_new_quotation_form_taxes_{{ $unique_id }}");
                        var sub_total=0;
                        for(var i=0;i<quotation_item_costs.length;i++){
                            if(quotation_item_costs[i].value)
                                sub_total += +quotation_item_costs[i].value;
                        }
                        document.getElementById('new_quotation_form_sub_total_{{ $unique_id }}').value = parseFloat(sub_total).toFixed(2);

                        var total_tax=0;
                        for(var i=0;i<quotation_item_taxes.length;i++){
                            if(quotation_item_taxes[i].value)
                                total_tax += +quotation_item_taxes[i].value;
                        }
                        document.getElementById('new_quotation_form_total_taxes_{{ $unique_id }}').value = parseFloat(total_tax).toFixed(2);

                        new_quotation_calculateDiscount_{{ $unique_id }}('amount', false);

                        var discount_value = $('#new_quotation_form_discount_value_{{ $unique_id }}').val();




                        console.log('discount_value -- '+discount_value);

                        // because tax is calculated on the discounted total
                        var sub_total_before_tax = +sub_total - +discount_value;

                        var net_total = +sub_total_before_tax + +total_tax;


                        document.getElementById('new_quotation_form_net_total_{{ $unique_id }}').value = parseFloat(net_total).toFixed(2);
                    }

                    function new_quotation_calculateDiscount_{{ $unique_id }}(calculation_type, recalculate_totals) {
                        var sub_total = $('#new_quotation_form_sub_total_{{ $unique_id }}').val();
                        var discount_percentage = $('#new_quotation_form_discount_percentage_{{ $unique_id }}').val();
                        var discount_value = $('#new_quotation_form_discount_value_{{ $unique_id }}').val();

                        if(sub_total != 0) {

                            if(!sub_total) {
                                sub_total = 0;
                            }

                            if(calculation_type == 'percentage') {
                                if(sub_total > 0) {
                                    discount_percentage = +discount_value / sub_total * 100;
                                } else {
                                    discount_percentage = 0;
                                }
                            } else if (calculation_type == 'amount') {
                                discount_value = +sub_total / 100 * +discount_percentage;
                            }

                            $('#new_quotation_form_discount_percentage_{{ $unique_id }}').val(parseFloat(discount_percentage).toFixed(2));
                            $('#new_quotation_form_discount_value_{{ $unique_id }}').val(parseFloat(discount_value).toFixed(2));

                            // to stop the infinite loop happening
                            if(recalculate_totals) {
                                new_quotation_calculateQuotationTotals_{{ $unique_id }}();
                            }
                        }
                    }

                    $(document).on('click', '.remove_quotation_item', function () { // <-- changes
                        $(this).closest('tr').remove();
                        new_quotation_calculateQuotationTotals_{{ $unique_id }}();
                        return false;
                    });

                    function add_quotation_item_{{ $unique_id }}() {
                        $('#quotation_items_table_{{ $unique_id }} tr:last').after('<tr><td width="10%"><input type="button" class="remove_quotation_item button tiny alert" value="X"></td><td width="30%"><input type="text" name="descriptions[]" id="descriptions" class="add_new_quotation_form_description_{{ $unique_id }}"></td><td><input type="text" name="unit_of_measures[]" class="add_new_quotation_form_unit_of_measure_{{ $unique_id }}" id="unit_of_measure"></td><td width="10%"><input type="text" name="no_of_units[]" onchange="add_new_quotation_calculate_quotation_item_cost_{{ $unique_id }}(this);new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="numbersonly add_new_quotation_form_no_of_units_{{ $unique_id }}" id="no_of_units"></td><td width="10%"><input type="text" name="unit_costs[]" onchange="add_new_quotation_calculate_quotation_item_cost_{{ $unique_id }}(this);new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="numbersonly add_new_quotation_form_unit_costs_{{ $unique_id }}" id="unit_costs"></td><td width="2%"><input type="hidden" name="taxables[]" class="add_new_quotation_form_taxables_{{ $unique_id }}" value="0"><input type="checkbox" name="taxables_check_box[]" onchange="add_new_quotation_calculate_quotation_item_cost_{{ $unique_id }}(this);new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="add_new_quotation_form_taxables_check_box_{{ $unique_id }}" id="taxables"></td><td width="8%"><input type="text" name="taxes[]" onchange="add_new_quotation_calculate_quotation_item_cost_{{ $unique_id }}(this);new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="numbersonly add_new_quotation_form_taxes_{{ $unique_id }}" readonly id="taxes"></td><td width="20%"><input type="text" name="costs[]" onchange="new_quotation_calculateQuotationTotals_{{ $unique_id }}()"  class="numbersonly add_new_quotation_form_cost_{{ $unique_id }}" id="costs" readonly></td></tr>');
                        return false;
                    }

                    function add_new_quotation_calculate_quotation_item_cost_{{ $unique_id }}(object) {

                        var no_of_units = $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".add_new_quotation_form_no_of_units_{{ $unique_id }}")     // Gets a descendent with class="nr"
                            .val();

                        if(isNaN(no_of_units)) {
                            no_of_units = 0;
                            $(object).closest("tr")   // Finds the closest row <tr>
                                .find(".add_new_quotation_form_no_of_units_{{ $unique_id }}")     // Gets a descendent with class="nr"
                                .val(no_of_units);
                        }

                        var unit_cost = $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".add_new_quotation_form_unit_costs_{{ $unique_id }}")     // Gets a descendent with class="nr"
                            .val();

                        if(isNaN(unit_cost)) {
                            unit_cost = 0;
                            $(object).closest("tr")   // Finds the closest row <tr>
                                .find(".add_new_quotation_form_unit_costs_{{ $unique_id }}")     // Gets a descendent with class="nr"
                                .val(parseFloat(unit_cost).toFixed(2));
                        }

                        var taxable = $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".add_new_quotation_form_taxables_check_box_{{ $unique_id }}")     // Gets a descendent with class="nr"
                            .prop('checked')? 1: 0;


                        $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".add_new_quotation_form_taxables_{{ $unique_id }}")     // Gets a descendent with class="nr"
                            .val(taxable);

                        var tax_percentage = taxable? '{{ $tax_percentage }}': 0;

                        console.log('no_of_units -- ' +no_of_units);
                        console.log('unit_cost -- ' +unit_cost);



                        var cost = +no_of_units * +unit_cost;


                        console.log('cost -- ' +cost);

                        $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".add_new_quotation_form_cost_{{ $unique_id }}")     // Gets a descendent with class="nr"
                            .val(parseFloat(cost).toFixed(2));

                        var tax = +cost * +tax_percentage / 100;

                        $(object).closest("tr")   // Finds the closest row <tr>
                            .find(".add_new_quotation_form_taxes_{{ $unique_id }}")     // Gets a descendent with class="nr"
                            .val(parseFloat(tax).toFixed(2));

                    }
                </script>


                <p><strong>Payment Terms</strong></p>
                <table class="basic-table">
                    <tr>
                        <td colspan="2">
                            <input type="text" value="" name="payment_terms" id="new_quotation_form_payment_terms_{{ $unique_id }}" placeholder="Enter additional payment terms">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            @if(is_object($employee->organization->organizationPreferences))
                                {{ $employee->organization->organizationPreferences->payment_terms }}
                            @endif
                        </td>
                    </tr>
                </table>

                @if(is_object($employee->organization->organizationPreferences))
                    <p><strong>*Terms & Conditions</strong></p>
                <table>
                        <tr>
                            <td colspan="2">

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                {{ $employee->organization->organizationPreferences->terms_and_conditions }}
                            </td>
                        </tr>
                </table>
                @endif
            </div>
        </div>
    </div>

    <div class="panel-footer">
        <div class="row save_bar">
            <div class="large-8 columns">
                &nbsp;
            </div>
            <div class="large-2 columns text-right">
                <input type="button" value="Cancel" class="button tiny alert" onclick="$('#popup_add_quotation_{{ is_object($lead)? $lead->id: $unique_id }}').foundation('close');">
            </div>
            <div class="large-2 columns text-right">
                <div class="row ">
                    <div class="large-12 columns text-center">
                        {{ Form::submit('Save', array("class" => "button success tiny", "id" => "new_quotation_form_save_button_".$unique_id)) }}
                    </div>
                </div>
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

        @if(!is_null($lead))
            setUpAjaxForm('<?php echo 'new_quotation_form_'.$unique_id; ?>', 'create', '<?php echo '#popup_add_quotation_'.$lead->id; ?>');
        @else
            setUpAjaxForm('<?php echo 'new_quotation_form_'.$unique_id; ?>', 'create', '#popup_add_new_quotation',function(){
                ajaxLoadCustomerQuotations(1);
            });
        @endif
        resetForm('new_quotation_form_{{ $unique_id }}');
    });

</script>