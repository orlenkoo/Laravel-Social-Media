<table>
    <tr>
        <th>Given Name *</th>
        <th>Surname *</th>
        <th>User Level</th>
        <th>Designation</th>
        <th>Country Code</th>
        <th>Contact No</th>
        <th>Email *</th>
        <th>Project Contact Person</th>
        <th>Receive SMS Alert</th>
        <th>Status</th>
    </tr>
    @foreach($employees as $employee)
        <tr>
            <td><input id="employee_given_name_{{ $employee->id }}" name="employee_given_name_{{ $employee->id }}" type="text" value="{{ $employee->given_name }}" onchange="ajaxUpdateIndividualFieldsOfModel('employees', '{{ $employee->id }}', 'given_name', this.value, 'employee_given_name_{{ $employee->id }}', 'Employee', true)"></td>
            <td><input id="employee_surname_{{ $employee->id }}" name="employee_surname_{{ $employee->id }}" type="text" value="{{ $employee->surname }}" onchange="ajaxUpdateIndividualFieldsOfModel('employees', '{{ $employee->id }}', 'surname', this.value, 'employee_surname_{{ $employee->id }}', 'Employee', true)"></td>
            <td>
                 <select name="employee_user_level_{{ $employee->id }}" id="employee_user_level_{{ $employee->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('employees', '{{ $employee->id }}', 'user_level', this.value, 'employee_user_level_{{ $employee->id }}', 'Employee')">
                    <?php
                        $user_levels = Employee::$user_levels;
                    ?>
                    @foreach($user_levels as $key => $value )
                        @if( $key == $employee->user_level)
                            <option value='{{ $key }}' selected >{{ $value }}</option>
                        @else
                            <option value='{{ $key }}'>{{ $value }}</option>
                        @endif
                    @endforeach
                </select>
            </td>
            <td><input id="employee_designation_{{ $employee->id }}" name="employee_designation_{{ $employee->id }}" type="text" value="{{ $employee->designation }}" onchange="ajaxUpdateIndividualFieldsOfModel('employees', '{{ $employee->id }}', 'designation', this.value, 'employee_designation_{{ $employee->id }}', 'Employee')"></td>

            <td>
                <select name="employee_country_code_{{ $employee->id }}" id="employee_country_code_{{ $employee->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('employees', '{{ $employee->id }}', 'country_code', this.value, 'employee_country_code_{{ $employee->id }}', 'Employee')">
                    <?php
                    $country_codes = Employee::$country_codes;
                    ?>
                    @foreach($country_codes as $key => $value)
                        @if(isset($employee->country_code))
                            <option value="{{ $key }}" {{ $key == $employee->country_code ? "selected" : '' }}>{{ $value }}</option>
                        @else
                            <option value="{{ $key }}" {{ $key == "+65" ? "selected" : '' }}>{{ $value }}</option>
                        @endif
                    @endforeach
                </select>
            </td>

            <td><input id="employee_contact_no_{{ $employee->id }}" name="employee_contact_no_{{ $employee->id }}" type="text" value="{{ $employee->contact_no }}" class="numbersonly" onchange="ajaxUpdateIndividualFieldsOfModel('employees', '{{ $employee->id }}', 'contact_no', this.value, 'employee_contact_no_{{ $employee->id }}', 'Employee')"></td>
            <td><input id="employee_email_{{ $employee->id }}" name="employee_email_{{ $employee->id }}" type="text" value="{{ $employee->email }}" onchange="ajaxUpdateEmployeeEmail('{{ $employee->id }}', this.value)"></td>
            
            <td>
                <select name="project_contact_person_{{ $employee->id }}" id="project_contact_person_{{ $employee->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('employees', '{{ $employee->id }}', 'project_contact_person', this.value, 'project_contact_person_{{ $employee->id }}', 'Employee')">
                    <option value="0" {{ $employee->project_contact_person == 0? "selected": "" }}>No</option>
                    <option value="1" {{ $employee->project_contact_person == 1? "selected": "" }}>Yes</option>
                </select>
            </td>
            <td>
                <select name="receive_sms_alert_{{ $employee->id }}" id="receive_sms_alert_{{ $employee->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('employees', '{{ $employee->id }}', 'receive_sms_alert', this.value, 'receive_sms_alert_{{ $employee->id }}', 'Employee')">
                    <option value="0" {{ $employee->receive_sms_alert == 0? "selected": "" }}>No</option>
                    <option value="1" {{ $employee->receive_sms_alert == 1? "selected": "" }}>Yes</option>
                </select>
            </td>
            <td>
                <select name="employee_status_{{ $employee->id }}" id="employee_status_{{ $employee->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('employees', '{{ $employee->id }}', 'status', this.value, 'employee_status_{{ $employee->id }}', 'Employee')">
                    <option value="0" {{ $employee->status == 0? "selected": "" }}>Disabled</option>
                    <option value="1" {{ $employee->status == 1? "selected": "" }}>Enabled</option>
                </select>
            </td>
        </tr>
    @endforeach
</table>

<div class="panel-list-pagination" id="pagination_employees_list">
    <?php echo $employees->links(); ?>
</div>

<script>

    function ajaxUpdateEmployeeEmail(employee_id, email) {

        $('#employee_email_'+employee_id).prop('disabled', true);

        $.post("/employees/ajax/update-email",
                {
                    employee_id: employee_id,
                    email: email
                },
                function (response, status) {

                    var status = response.status;
                    var message = response.message;

                    if(status == 'fail'){
                        $.notify(message);
                    }else{
                        $.notify(message,"success");
                    }

                    $('#employee_email_'+employee_id).prop('disabled', false);
                });
    }

    

</script>