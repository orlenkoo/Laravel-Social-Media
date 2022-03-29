<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Manage Module: {{ $module['module_name'] }}</h4>
        </div>
    </div>
</div>

<div class="panel-content">
    <div class="row">
        <div class="large-3 columns">
            <label for="">
                <?php
                    $last_web360_module_organization_assignment = $module['last_web360_module_organization_assignment'];
                    if(is_object($last_web360_module_organization_assignment)) {

                        $module_status_checked = $last_web360_module_organization_assignment->status == 'Enabled'? "checked": "";
                    } else {
                        $module_status_checked = '';
                    }

                ?>
                <input type="checkbox" name="module_status_{{ $module['module_id'] }}" id="module_status_{{ $module['module_id'] }}" {{ $module_status_checked }} onclick="ajaxUpdateModuleOrganizationAssignment_{{ $module['module_id'] }}(this.checked)" value="Enabled">Enable Module
                    <script>
                        function ajaxUpdateModuleOrganizationAssignment_{{ $module['module_id'] }}(checked) {
                            var module_id = '{{ $module['module_id'] }}';
                            var module_status = 'Disabled'
                            if(checked) {
                                module_status = 'Enabled';
                            }

                            $.ajax({
                                url: '/payment-management/ajax/update-module-organization-assignment?'
                                + 'module_id=' + module_id
                                + '&module_status=' + module_status
                            }).done(function (data) {
                                $.notify(data, "success");
                                ajaxLoadModulesList();

                            });
                        }
                    </script>
            </label>
        </div>
        <div class="large-9 columns">
            &nbsp;
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="large-9 columns">
            <h4>Search / Select Employees</h4>
            <div class="row">
                <div class="large-12 columns">
                    <label for="">
                        Search By Name
                        <input type="text" value="" >
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <input type="hidden" name="selected_employees_list_{{ $module['module_id'] }}" id="selected_employees_list_{{ $module['module_id'] }}" value="{{ $module['assigned_employees_list'] }}">

                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_module_employees_list_'.$module['module_id'], 'class' => 'float-center', 'style' => 'display:none')) }}
                    <div id="response_module_employees_list_{{ $module['module_id'] }}">

                    </div><!--end response_modules_list-->
                    <script>

                        $(document).on('click', '#pagination_selectable_employees_list a', function (e) {
                            e.preventDefault();
                            var page = $(this).attr('href').split('page=')[1];
                            ajaxLoadModuleEmployeesList_{{ $module['module_id'] }}(page);
                        });

                        function ajaxLoadModuleEmployeesList_{{ $module['module_id'] }}(page) {
                            $('#loader_module_employees_list_{{ $module['module_id'] }}').show();
                            $('#response_module_employees_list_{{ $module['module_id'] }}').hide();

                            var selected_employees_list = $('#selected_employees_list_{{ $module['module_id'] }}').val();

                            $.ajax({
                                url: '/payment-management/ajax/load-module-employees-list?' +
                                'page=' + page +
                                '&selected_employees_list=' + selected_employees_list +
                                '&web360_module_id={{ $module['module_id'] }}'
                            }).done(function (data) {
                                $('#response_module_employees_list_{{ $module['module_id'] }}').html(data);
                                $('#loader_module_employees_list_{{ $module['module_id'] }}').hide();
                                $('#response_module_employees_list_{{ $module['module_id'] }}').show();
                            });
                        }
                    </script>
                </div>
            </div>
        </div>

        <div class="large-3 columns">
            <table>
                <tr>
                    <th>Number of selected users:</th>
                    <td>
                        <?php
                        $assigned_employee_count = 0;
                        if(is_object($module['last_web360_module_organization_assignment'])) {
                            $assigned_employee_count = $module['last_web360_module_organization_assignment']->assigned_employee_count;
                        }
                        ?>

                        <span id="web360_module_assigned_employee_count_{{ $module['module_id'] }}">{{ $assigned_employee_count }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Rate Per {{ $module['module_rate_base'] }}:</th>
                    <td>$ <span id="web360_module_rate_{{ $module['module_id'] }}">{{ $module['module_rate'] }}</span></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <th>Total Rate For Module Per Month:</th>
                    <th>
                        <?php
                        $total_rate_per_module = 0;

                        if($module['module_rate_base'] == 'Per User') {
                            $total_rate_per_module = $assigned_employee_count * $module['module_rate'];
                        } else if ($module['module_rate_base'] == 'Per Module') {
                            $total_rate_per_module = $module['module_rate'];
                        }
                        ?>
                        $ <span id="web360_total_rate_per_module_{{ $module['module_id'] }}">{{ $total_rate_per_module }}</span>
                    </th>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="panel-footer">

</div>

<script>
    function updateModuleCalculations_{{ $module['module_id'] }}() {
        var number_of_assigned_employees = $('#selected_employees_list_{{ $module['module_id'] }}').val().split(',').length;
        var total_rate_per_module = '{{ $module['module_rate'] }}';


        if('Per User' == '{{ $module['module_rate_base'] }}') {
            total_rate_per_module = +number_of_assigned_employees * +total_rate_per_module;
        }

        console.log('total_rate_per_module -- ' + total_rate_per_module);
        console.log('number_of_assigned_employees -- ' + number_of_assigned_employees);

        $('#web360_total_rate_per_module_{{ $module['module_id'] }}').text(total_rate_per_module);
        $('#web360_module_assigned_employee_count_{{ $module['module_id'] }}').text(number_of_assigned_employees);
    }
</script>

