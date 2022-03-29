{{ Form::open(array('route' => 'settings.ajax.save_preference_changes', 'files' => true , 'id' =>'preferences_form_id','autocomplete' => 'off' )) }}
<input type="hidden" id="file_name" name="file_name">

<div class="row expanded">
    <div class="large-12 columns">
        <h5>Company Details</h5>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_organization">Organization Name</label>
        <input type="text" id="preferences_form_organization" name="organization" value="{{ $organization->organization }}" placeholder="Organization Name" required>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_business_registration_number">Business Registration Number</label>
        <input type="text" id="preferences_form_business_registration_number" name="business_registration_number" value="{{ $organization->business_registration_number }}" placeholder="Business Registration Number">
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_address_line_1">Address Line 1</label>
        <input type="text" id="preferences_form_address_line_1" name="address_line_1" value="{{ $organization->address_line_1 }}" placeholder="Address Line 1" >
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_address_line_2">Address Line 2</label>
        <input type="text" id="preferences_form_address_line_2" name="address_line_2" value="{{ $organization->address_line_2 }}" placeholder="Address Line 2" >
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_city">City</label>
        <input type="text" id="preferences_form_city" name="city" value="{{ $organization->city }}" placeholder="City" >
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_postal_code">Postal Code</label>
        <input type="text" id="preferences_form_postal_code" name="postal_code" value="{{ $organization->postal_code }}" placeholder="Postal Code" >
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_state">State</label>
        <input type="text" id="preferences_form_state" name="state" value="{{ $organization->state }}" placeholder="State" >
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('preferences_form_country', 'Country', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::select('country_id', array(), null, array('id' => 'preferences_form_country')) }}</div>
        {{ $errors->first('preferences_form_country', '<p class="callout alert radius">:message</p>') }}
    </div>
</div>

<div class="row expanded">
    <div class="large-4 columns">
        <label for="preferences_form_phone_number_country_code">Phone Number Country Code</label>
        <select name="phone_number_country_code" id="preferences_form_phone_number_country_code">
            <?php
            $country_codes = Employee::$country_codes;
            ?>
            @foreach($country_codes as $key => $value)
                @if(isset($organization->phone_number_country_code))
                    <option value="{{ $key }}" {{ $key == $organization->phone_number_country_code ? "selected" : '' }}>{{ $value }}</option>
                @else
                    <option value="{{ $key }}" {{ $key == "+65" ? "selected" : '' }}>{{ $value }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="large-8 columns">
        <label for="preferences_form_phone_number">Phone Number</label>
        <input type="text" id="preferences_form_phone_number" name="phone_number" value="{{ $organization->phone_number }}" placeholder="Phone Number" >
    </div>
</div>

<div class="row expanded">
    <div class="large-4 columns">
        <label for="preferences_form_fax_number_country_code">Fax Number Country Code</label>
        <select name="fax_number_country_code" id="preferences_form_fax_number_country_code">
            <?php
            $country_codes = Employee::$country_codes;
            ?>
            @foreach($country_codes as $key => $value)
                @if(isset($organization->fax_number_country_code))
                    <option value="{{ $key }}" {{ $key == $organization->fax_number_country_code ? "selected" : '' }}>{{ $value }}</option>
                @else
                    <option value="{{ $key }}" {{ $key == "+65" ? "selected" : '' }}>{{ $value }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="large-8 columns">
        <label for="preferences_form_fax_number">Fax Number</label>
        <input type="text" id="preferences_form_fax_number" name="fax_number" value="{{ $organization->fax_number }}" placeholder="Fax Number" >
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_email">Email</label>
        <input type="email" id="preferences_form_email" name="email" value="{{ $organization->email }}" placeholder="Email" >
    </div>
</div>

<div class="row expanded">
    <hr>
    <div class="large-12 columns">
        <h5>Other Settings</h5>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="send_quotation_for_approval">Send Quotations For Approval</label>
        <?php
        if(isset($organization_preference) && is_object($organization_preference)) {
            $send_quotation_for_approval = $organization_preference->send_quotation_for_approval;
        } else {
            $send_quotation_for_approval = 1;
        }
        ?>
        <input type="radio" name="send_quotation_for_approval" value="1" {{ $send_quotation_for_approval == 1 ? "checked": "" }}> Enabled
        <input type="radio" name="send_quotation_for_approval" value="0" {{ $send_quotation_for_approval == 0 ? "checked": "" }}> Disabled
    </div>
    <div class="large-12 columns">
        <label for="send_quotation_follow_up_email_reminder">Send Quotation Follow Up Email Reminder</label>
        <?php
        if(isset($organization_preference) && is_object($organization_preference)) {
            $send_quotation_follow_up_email_reminder = $organization_preference->send_quotation_follow_up_email_reminder;
        } else {
            $send_quotation_follow_up_email_reminder = 1;
        }
        ?>
        <input type="radio" name="send_quotation_follow_up_email_reminder" value="1" {{ $send_quotation_follow_up_email_reminder == 1 ? "checked": "" }}> Enabled
        <input type="radio" name="send_quotation_follow_up_email_reminder" value="0" {{ $send_quotation_follow_up_email_reminder == 0 ? "checked": "" }}> Disabled
    </div>
</div>

<div class="row expanded">
    <hr>
    <div class="large-12 columns">
        <h5>Other Details</h5>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_payment_terms">Payment Terms</label>
        @if(isset($organization_preference) && is_object($organization_preference))
            <div class="controls">{{ Form::textarea('payment_terms','', array('id' => 'preferences_form_payment_terms','data-validation'=>'','placeholder' => 'Payment Terms')) }}</div>
            <script>

                $( document ).ready(function() {
                    var html_signature = '<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', $organization_preference->payment_terms); ?>';
                    setupCKEditor('preferences_form_payment_terms',html_signature,'Basic');
                });

            </script>
        @else
            <div class="controls">{{ Form::textarea('payment_terms','', array('id' => 'preferences_form_payment_terms','data-validation'=>'','placeholder' => 'Payment Terms')) }}</div>
            <script>

                $( document ).ready(function() {
                    setupCKEditor('preferences_form_payment_terms','','Basic');
                });

            </script>
        @endif
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_terms_and_conditions">Terms and Conditions</label>
        @if(isset($organization_preference) && is_object($organization_preference))
            <div class="controls">{{ Form::textarea('terms_and_conditions','', array('id' => 'preferences_form_terms_and_conditions','data-validation'=>'','placeholder' => 'Terms and Conditions')) }}</div>
            <script>

                $( document ).ready(function() {
                    var html_signature = '<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', $organization_preference->terms_and_conditions); ?>';
                    setupCKEditor('preferences_form_terms_and_conditions',html_signature,'Basic');
                });

            </script>
        @else
            <div class="controls">{{ Form::textarea('terms_and_conditions','', array('id' => 'preferences_form_terms_and_conditions','data-validation'=>'','placeholder' => 'Terms and Conditions')) }}</div>
            <script>

                $( document ).ready(function() {
                    setupCKEditor('preferences_form_terms_and_conditions','','Basic');
                });

            </script>
        @endif
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        <label for="preferences_form_tax_percentage">Tax Percentage</label>
        <input type="text" id="preferences_form_tax_percentage" name="tax_percentage" value="{{ is_object($organization_preference) ? $organization_preference->tax_percentage : '' }}" placeholder="Tax Percentage">
    </div>
</div>

<div class="row expanded">

    <div class="large-6 columns">
        <label for="logo_image">Logo Image</label>
        <input type="file" id="logo_image" name="logo_image" accept="image/*" onchange="logoImageFileName()">
        <div class="callout alert" id="logo_image_size_alert" style="display: none;">
            <p>The Logo Image size shouldn't exceed 200kb.</p>
        </div>
    </div>

    <div class="large-6 columns">
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_organization_preference_logo_image_div', 'style' => 'display:none')) }}
        <div id="organization_preference_logo_image_div" >
        </div>
    </div>

</div>


<div class="row expanded save_bar">
    <div class="large-12 columns">
        {{ Form::submit('Save Changes', array("class" => "button tiny", "id" => "preferences_form__save_preference_changes_button")) }}
    </div>
</div>
<div class="row loading_animation" style="display: none;">
    <div class="large-12 columns">
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
    </div>
</div>

{{ Form::close() }}

<script>
    function getFile(filePath) {
        return filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[0];
    }

    function logoImageFileName()
    {
        file_name.value = getFile(logo_image.value);
    }

    $(document).ready(function() {
        setUpAjaxForm('preferences_form_id', 'create', '',
            function(){
                loadOrganizationPreferenceLogoImage();
            },
            function(){
                var logo_image_value = $("#logo_image").val();
                var logo_image = $("#logo_image")[0];

                if(logo_image_value != '' && logo_image.files[0].size*0.001 > 200){
                    return false;
                }
            }
        );
    });


    $('#logo_image').bind('change', function() {

        if(this.files[0].size*0.001 > 200){
            $("#logo_image_size_alert").show();
            $("#preferences_form__save_preference_changes_button").hide();
        }else{
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#logo-image-img')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(this.files[0]);
            }
            $("#logo_image_size_alert").hide();
            $("#preferences_form__save_preference_changes_button").show();
        }

    });

    function getCustomerExportCountriesList() {
        $.ajax({
            url: '/countries/ajax/countries'
        }).done(function (data) {
            $('#preferences_form_country').empty();
            data = $.parseJSON(data);
            $('#preferences_form_country').append("<option value=''>Select</option>");

            for (var i in data) {
                if(data[i].id == '{{ $organization->country_id }}') {
                    $('#preferences_form_country').append("<option value='" + data[i].id + "' selected>" + data[i].country + "</option>");
                } else {
                    $('#preferences_form_country').append("<option value='" + data[i].id + "'>" + data[i].country + "</option>");
                }

            }

            $('#preferences_form_country').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    getCustomerExportCountriesList();

    function loadOrganizationPreferenceLogoImage(){

        var organization_preference_id = '';
        @if($organization_preference)
            organization_preference_id = '{{ $organization_preference->id; }}';
        @endif

        $('#loader_organization_preference_logo_image_div').show();
        $('#organization_preference_logo_image_div').hide();

        $.ajax({
            url: '/settings/ajax/load-organization-preference-logo-image?' +
            'organization_preference_id=' + organization_preference_id
        }).done(function (data) {
            $('#organization_preference_logo_image_div').html(data);
            $('#loader_organization_preference_logo_image_div').hide();
            $('#organization_preference_logo_image_div').show()
            $(document).foundation();
        });
    }

    loadOrganizationPreferenceLogoImage();

</script>