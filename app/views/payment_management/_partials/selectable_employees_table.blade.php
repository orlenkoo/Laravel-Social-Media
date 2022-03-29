<table>
    <tr>
        <th><input type="checkbox" ></th>
        <th>Given Name</th>
        <th>Surname</th>
        <th>User Level</th>
        <th>Email</th>
    </tr>
    @foreach($employees as $employee)
        <tr>
            <td><input type="checkbox" name="employee" value="{{ $employee->id }}" {{ in_array($employee->id, $selected_employees_list)? 'checked': '' }} onchange="ajaxAssignEmployeeToModule_{{ $web360_module_id }}('{{ $employee->id }}', this.checked)"></td>
            <td>{{ $employee->given_name }}</td>
            <td>{{ $employee->surname }}</td>
            <td>{{ $employee->user_level }}</td>
            <td>{{ $employee->email }}</td>
        </tr>
    @endforeach
</table>

<script>
    function ajaxAssignEmployeeToModule_{{ $web360_module_id }}(employee_id, checked) {
        var web360_module_id = '{{ $web360_module_id }}';
        var assigned = "Disabled";
        if(checked) {
            assigned = "Enabled";
        }

        console.log('web360_module_id -- ' + web360_module_id);
        console.log('employee_id -- ' + employee_id);
        console.log('assigned -- ' + assigned);

        $.ajax({
            url: '/payment-management/ajax/assign-employee-to-module?'
            + 'web360_module_id=' + web360_module_id
            + '&employee_id=' + employee_id
            + '&assigned=' + assigned
        }).done(function (data) {
            $('#selected_employees_list_'+web360_module_id).val(data);
            $.notify('Updated Successfully.', "success");

            updateModuleCalculations_{{ $web360_module_id }}();

            ajaxLoadModulesList();
            ajaxLoadOverviewModulesList();

            //ajaxLoadModuleEmployeesList_{{ $web360_module_id }}(1);
        });

    }
</script>