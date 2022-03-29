{{ Form::open(array('route' => 'employees.ajax.save_employee', 'ajax' => 'true', 'id' => 'add_new_employee','autocomplete' => 'off')) }}
<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h5>Add Employee</h5>
        </div>
    </div>
</div>

<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="given_name">
                Given Name *
                <input type="text" id="employee_form_given_name" name="given_name" data-validation="required">
            </label>
        </div>

        <div class="large-12 columns">
            <label for="surname">
                Surname *
                <input type="text" id="employee_form_surname" name="surname" data-validation="required">
            </label>
        </div>

        <div class="large-12 columns">
            <label for="user_level">
                User Level *
                {{Form::select('user_level',Employee::$user_levels,null,array('id' => 'employee_form_user_level', 'data-validation' => 'required'))}}
            </label>
        </div>

        <div class="large-12 columns">
            <label for="designation">
                Designation *
                <input type="text" id="employee_form_designation" name="designation" data-validation="required">
            </label>
        </div>

        <div class="large-12 columns">
            <label for="country_code">
                Country Code *
                <select id="employee_form_country_code" name="country_code" class="form-control" data-validation="required">
                    <?php
                    $country_codes = Employee::$country_codes;
                    ?>
                    @foreach($country_codes as $key => $value)
                        <option value="{{ $key }}" {{ $key == "+65" ? "selected" : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="large-12 columns">
            <label for="contact_no">
                Contact No *
                <input type="text" id="employee_form_contact_no" name="contact_no" class="numbersonly" data-validation="required">
            </label>
        </div>

        <div class="large-12 columns">
            <label for="email">
                Email *
                <input type="text" id="employee_form_email" name="email" data-validation="required">
            </label>
        </div>

        <div class="large-12 columns">
            <label for="password">
                Password *
                <input type="text" id="employee_form_password" name="password" data-validation="required">
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
            <input type="button" value="Clear" class="button tiny alert float-right" onclick="clearEmployeeForm()">
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Save', array("class" => "button success tiny float-right", "id" => "employee_form_save_button")) }}
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
        setUpAjaxForm('add_new_employee', 'create', '#popup_add_employee_form',
            function(){
                clearEmployeeForm();
                ajaxLoadEmployeesList(1);
            }
        );
    });

    function clearEmployeeForm() {

        $('#employee_form_given_name').val('');
        $('#employee_form_surname').val('');
        $('#employee_form_country_code').val('');
        $('#employee_form_contact_no').val('');
        $('#employee_form_email').val('');
        $('#employee_form_password').val('');
        $('#employee_form_designation').val('');

    }

</script>