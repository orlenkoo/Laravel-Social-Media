{{ Form::open(array('route' => 'contacts.ajax.save_new_customer_contact', 'ajax' => 'true', 'id' => 'new_contact_form_'.$customer->id,'autocomplete' => 'off')) }}
<input type="hidden" name="customer_id" id="new_call_form_customer_id_{{ $customer->id }}" value="{{ $customer->id }}">
<div class="row">
    <div class="large-6 columns">
        <label for="given_name">
            Given Name *
            <select class="lead_meta_loader" name="given_name" id="given_name" data-validation="required"></select>
        </label>
    </div>
    <div class="large-6 columns">
        <label for="new_contact_surname">
            Surname *
            <select class="lead_meta_loader" name="surname" id="surname" data-validation="required"></select>
        </label>
    </div>
</div>
<div class="row">
    <div class="large-4 columns">
        <label for="new_contact_phone_number">
            Phone Number *
            <select class="lead_meta_loader" name="phone_number" id="phone_number" data-validation="required"></select>
        </label>
    </div>
    <div class="large-4 columns">
        <label for="email">
            Email
            <select class="lead_meta_loader" name="email" id="email"></select>
        </label>
    </div>
    <div class="large-4 columns">
        <label for="designation">
            Designation
            <select class="lead_meta_loader" name="designation" id="designation"></select>
        </label>
    </div>
</div>
<div class="row save_bar">
    <div class="large-8 columns">
        &nbsp;
    </div>
    <div class="large-2 columns text-right">
        <input type="button" value="Clear" class="button tiny alert"
               onclick="clearSaveContactForm()">
    </div>
    <div class="large-2 columns text-right">
        {{ Form::submit('Save', array("class" => "button success tiny", "id" => "new_contact_form_save_button_".$customer->id)) }}
    </div>
</div>

<div class="row loading_animation" style="display: none;">
    <div class="large-12 columns text-center">
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
    </div>
</div>

{{ Form::close() }}
<script>
    $(document).ready(function() {
        setUpAjaxForm('<?php echo 'new_contact_form_'.$customer->id; ?>', 'create',null, function(){
            clearSaveContactForm();
            ajaxLoadContactsList(1);
            @if(Route::currentRouteName() != 'customers.view')
                updateCustomerPrimaryContactDetail();
            @endif
        });

        @if(!isset($lead))
            $('.lead_meta_loader').selectize({
                create: true,
                sortField: 'text'
            });
        @endif

    });


    function clearSaveContactForm() {

        var given_name = $('#given_name').selectize();
        var control_given_name = given_name[0].selectize;
        control_given_name.clear();

        var surname = $('#surname').selectize();
        var control_surname = surname[0].selectize;
        control_surname.clear();

        var phone_number = $('#phone_number').selectize();
        var control_phone_number = phone_number[0].selectize;
        control_phone_number.clear();

        var email = $('#email').selectize();
        var control_email = email[0].selectize;
        control_email.clear();

        var designation = $('#designation').selectize();
        var control_designation = designation[0].selectize;
        control_designation.clear();

    }
</script>