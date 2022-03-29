@extends('layouts.default')

@section('content')
    <div class="row page-title-bar">
        <div class="large-12 columns">
            <h1>Screen Permissions</h1>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <h5>Employees</h5>

                <table>

                    <tr>
                        <th>Employee</th>
                        @foreach($screens as $screen)
                            <th>{{ $screen->screen_name }}</th>
                        @endforeach
                        <th></th>
                    </tr>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->given_name }} {{ $employee->surname }}</td>
                            @foreach($screens as $screen)
                                <td>
                                    <input type="checkbox" name="employee_screen_permissions_{{ $employee->id }}" class="employee_screen_permissions"
                                           id="employee_screen_permissions_{{ $employee->id }}_{{ $screen->id }}" value="{{ $employee->id }}~{{$screen->id}}" <?php echo in_array($employee->id.'~'.$screen->id, $employee_screen_permissions_array) ? 'checked' : ''; ?>>
                                </td>
                            @endforeach
                            <td>
                                <input type="button" value="Save" class="button tiny success" onclick="updateEmployeeScreenPermissions('employee_screen_permissions_{{ $employee->id }}', '{{ $employee->id }}')">
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <div class="panel">
                <h5>User Levels</h5>

                <table>

                    <tr>
                        <th>User Level</th>
                        @foreach($screens as $screen)
                            <th>{{ $screen->screen_name }}</th>
                        @endforeach
                        <th></th>
                    </tr>
                    @foreach($user_levels as $user_level)
                        <tr>
                            <td>{{ $user_level->user_level }}</td>
                            @foreach($screens as $screen)
                                <td>
                                    <input type="checkbox" name="user_level_screen_permissions_{{ $user_level->id }}" class="user_level_screen_permissions"
                                           id="user_level_screen_permissions_{{ $user_level->id }}_{{ $screen->id }}" value="{{ $user_level->id }}~{{$screen->id}}" <?php echo in_array($user_level->id.'~'.$screen->id, $user_level_screen_permissions_array) ? 'checked' : ''; ?>>
                                </td>
                            @endforeach
                            <td>
                                <input type="button" value="Save" class="button tiny success" onclick="updateUserLevelScreenPermissions('user_level_screen_permissions_{{ $user_level->id }}', '{{ $user_level->id }}')">
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>

    <script>
        function updateEmployeeScreenPermissions(checkbox_name, employee_id) {


            var r = confirm("WARNING!!! Are sure you want to update this record? This is permanent, please double check.");
            if (r == true) {

                var update_array = [];
                $("input:checkbox[name="+checkbox_name+"]:checked").each(function(){
                    var values = $(this).val().split('~');
                    //console.log(values);
                    //console.log(values[0]);
                    //console.log(values[1]);

                    var array_employee_screen = {};
                    array_employee_screen.employee_id = values[0];
                    array_employee_screen.screen_id = values[1];
                    //console.log(array_employee_screen);
                    update_array.push(array_employee_screen);
                });

                console.log(update_array);
                var update_string = JSON.stringify(update_array);
                console.log(update_string);

                $.post("/permissions/ajax/update-employee-screen-permissions/", {update_string: update_string, employee_id: employee_id}, function (data) {
                    $.notify(data, "success");
                    showLoadingWorkingArea();
                    location.reload();
                });
            } else {
                return false;
            }

        }

        function updateUserLevelScreenPermissions(checkbox_name, user_level_id) {


            var r = confirm("WARNING!!! Are sure you want to update this record? This is permanent, please double check.");
            if (r == true) {

                var update_array = [];
                $("input:checkbox[name="+checkbox_name+"]:checked").each(function(){
                    var values = $(this).val().split('~');
                    //console.log(values);
                    //console.log(values[0]);
                    //console.log(values[1]);

                    var array_user_level_screen = {};
                    array_user_level_screen.user_level_id = values[0];
                    array_user_level_screen.screen_id = values[1];
                    //console.log(array_user_level_screen);
                    update_array.push(array_user_level_screen);
                });

                //console.log(update_array);
                var update_string = JSON.stringify(update_array);
                //console.log(update_string);

                $.post("/permissions/ajax/update-user-level-screen-permissions/", {update_string: update_string, user_level_id: user_level_id}, function (data) {
                    $.notify(data, "success");
                    showLoadingWorkingArea();
                    location.reload();
                });
            } else {
                return false;
            }

        }


    </script>


@stop