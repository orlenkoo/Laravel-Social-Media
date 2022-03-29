<form action="#" name="customer-update-form-{{ $customer->id }}" id="customer-update-form-{{ $customer->id }}" method="post" autocomplete="off">
    <div class="row">
        <div class="large-12 columns">
            <label for="customer_address_line_1">
                Address Line 1
                <input type="text" id="customer_address_line_1" name="customer_address_line_1" value="{{ $customer->address_line_1 }}" onchange="ajaxUpdateIndividualFieldsOfModel('customers', '{{ $customer->id }}', 'address_line_1', this.value, 'customer_address_line_1', 'Customer')" >
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="customer_address_line_2">
                Address Line 2
                <input type="text" id="customer_address_line_2" name="customer_address_line_2" value="{{ $customer->address_line_2 }}" onchange="ajaxUpdateIndividualFieldsOfModel('customers', '{{ $customer->id }}', 'address_line_2', this.value, 'customer_address_line_2', 'Customer')">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="customer_city">
                City
                <input type="text" id="customer_city" name="customer_city" value="{{ $customer->city }}" onchange="ajaxUpdateIndividualFieldsOfModel('customers', '{{ $customer->id }}', 'city', this.value, 'customer_city', 'Customer')" >
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="customer_postal_code">
                Postal Code
                <input type="text" id="customer_postal_code" name="customer_postal_code" value="{{ $customer->postal_code }}" onchange="ajaxUpdateIndividualFieldsOfModel('customers', '{{ $customer->id }}', 'postal_code', this.value, 'customer_postal_code', 'Customer')">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="customer_state">
                State
                <input type="text" id="customer_state" name="customer_state" value="{{ $customer->state }}" onchange="ajaxUpdateIndividualFieldsOfModel('customers', '{{ $customer->id }}', 'state', this.value, 'customer_state', 'Customer')">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="customer_website">
                Website
                <input type="text" id="customer_website" name="customer_website" value="{{ $customer->website }}" onchange="ajaxUpdateIndividualFieldsOfModel('customers', '{{ $customer->id }}', 'website', this.value, 'customer_website', 'Customer')">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-6 columns">
            <label for="customer_phone_number">
                Phone Number
                <input type="text" id="customer_phone_number" name="customer_phone_number" value="{{ $customer->phone_number }}" onchange="ajaxUpdateIndividualFieldsOfModel('customers', '{{ $customer->id }}', 'phone_number', this.value, 'customer_phone_number', 'Customer')">
            </label>
        </div>
        <div class="large-6 columns">
            <label for="customer_fax_number">
                Fax Number
                <input type="text" id="customer_fax_number" name="customer_fax_number" value="{{ $customer->fax_number }}" onchange="ajaxUpdateIndividualFieldsOfModel('customers', '{{ $customer->id }}', 'fax_number', this.value, 'customer_fax_number', 'Customer')">
            </label>
        </div>
    </div>
</form>