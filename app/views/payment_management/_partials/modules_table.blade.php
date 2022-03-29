<table>
    <tr>
        <th>Module</th>
        <th>No of Users</th>
        <th>Rate</th>
        <th>Total Rate Per Module</th>
        <th>Status</th>
        <th>Enabled On</th>
        <th>Disabled On</th>
        <th>Manage</th>
    </tr>
    @foreach($modules as $module)
        <tr>
            <td>{{ $module['module_name'] }}</td>
            <td>
                <?php
                    $assigned_employee_count = 0;
                    if(is_object($module['last_web360_module_organization_assignment'])) {
                        $assigned_employee_count = $module['last_web360_module_organization_assignment']->assigned_employee_count;
                    }
                ?>
                {{ $assigned_employee_count }}
            </td>
            <td>{{ $module['module_rate'] }} per {{ $module['module_rate_base'] }}</td>
            <td>
                <?php
                    $total_rate_per_module = 0;

                    if($module['module_rate_base'] == 'Per User') {
                        $total_rate_per_module = $assigned_employee_count * $module['module_rate'];
                    } else if ($module['module_rate_base'] == 'Per Module') {
                        $total_rate_per_module = $module['module_rate'];
                    }
                    ?>
                {{ $total_rate_per_module }}
            </td>
            <td>{{ is_object($module['last_web360_module_organization_assignment'])? $module['last_web360_module_organization_assignment']->status: "NA" }}</td>
            <td>{{ is_object($module['last_web360_module_organization_assignment'])? $module['last_web360_module_organization_assignment']->enabled_date_time: "NA" }}</td>
            <td>{{ is_object($module['last_web360_module_organization_assignment'])? $module['last_web360_module_organization_assignment']->disabled_date_time: "NA" }}</td>
            <td>
                <button class="button tiny" type="button" data-open="popup_update_module_assignments_screen_{{ $module['module_id'] }}" onclick="ajaxLoadModuleEmployeesList_{{ $module['module_id'] }}(1)">Update ></button>
                <div class="reveal large" id="popup_update_module_assignments_screen_{{ $module['module_id'] }}" name="popup_update_module_assignments_screen_{{ $module['module_id'] }}" data-reveal>
                    @include('payment_management._partials.update_module_assignment_form')
                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach
</table>

