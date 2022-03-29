{{ Form::open(array('route' => 'payment_management.ajax.update_payment_method', 'ajax' => 'true', 'id' => 'update_payment_method_form_'. $payment_method->id,'autocomplete' => 'off')) }}
<input type="hidden" name="payment_method_id" value="{{ $payment_method->id }}">
<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Update Payment Method</h4>
        </div>
    </div>
</div>

<div class="panel-content">
    <div class="row">
        <div class="large-3 columns">
            <label for="credit_card_type">
                Card Type*:
                <select name="credit_card_type" id="credit_card_type">
                    @foreach(OrganizationPaymentMethod::$credit_card_types as $credit_card_type)
                        <option value="{{ $credit_card_type }}" {{ $payment_method->credit_card_type == $credit_card_type? "selected": "" }}>{{ $credit_card_type }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <div class="large-6 columns">
            <label for="credit_card_number">
                Card Number*:
                <input type="text" name="credit_card_number" id="credit_card_number" value="{{ $payment_method->credit_card_number }}" data-validation="required">
            </label>
        </div>
        <div class="large-3 columns">
            <label for="cvv">
                CVV*:
                <input type="text" name="cvv" id="cvv" value="{{ $payment_method->cvv }}" placeholder="" data-validation="required">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-6 columns">
            <label for="">
                Expire Date*:
            </label>
        </div>
        <div class="large-3 columns">
            <label for="expiry_month">
                Month
                <select name="expiry_month" id="expiry_month">
                    @foreach(GeneralCommonFunctionHelper::getAdvanceNumbersDropDown(1, 12) as $key => $value)
                        <option value="{{ $value }}" {{ $payment_method->expiry_month == $value? "selected": "" }}>{{ $key }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <div class="large-3 columns">
            <label for="expiry_year">
                Year
                <select name="expiry_year" id="expiry_year">
                    @foreach(GeneralCommonFunctionHelper::getAdvanceNumbersDropDown(date('Y'), date('Y')+10) as $key => $value)
                        <option value="{{ $value }}" {{ $payment_method->expiry_year == $value? "selected": "" }}>{{ $key }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="card_owner_name">
                Card Owner Name*:
                <input type="text" name="card_owner_name" id="card_owner_name" value="{{ $payment_method->card_owner_name }}" placeholder="" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="card_owner_address">
                Card Owner Address*:
                <textarea name="card_owner_address" id="card_owner_address" cols="30" rows="3">{{ $payment_method->card_owner_address }}</textarea>
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="primary_card">
                Primary Card?:
                <input type="checkbox" id="primary_card" name="primary_card" value="1" {{ $payment_method->primary_card == 1? "checked": "" }}>
            </label>
        </div>
    </div>

</div>

<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns text-right">
            <input type="button" value="Cancel" class="button tiny alert" >
        </div>
        <div class="large-2 columns text-right">
            <div class="row ">
                <div class="large-12 columns text-center">
                    {{ Form::submit('Save', array("class" => "button success tiny")) }}
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
        setUpAjaxForm('update_payment_method_form_{{ $payment_method->id }}', 'create', '#popup_update_payment_method_form_{{ $payment_method->id }}',
            function(){
                clearNewPaymentMethodForm_{{ $payment_method->id }}();
                ajaxLoadPaymentMethodsList(1);
            }
        );
    });

    function clearNewPaymentMethodForm_{{ $payment_method->id }}() {
        document.getElementById("update_payment_method_form_{{ $payment_method->id }}").reset();
    }


</script>