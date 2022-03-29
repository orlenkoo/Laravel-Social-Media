<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 31/05/2016
 * Time: 09:23
 */
class PermissionsController extends BaseController
{
    /**
     * Display Screens Permission
     *
     * @return Response
     */
    public function index()
    {
        $organization_id = Session::get('user-organization-id');
        $user_levels = UserLevel::where('organization_id', $organization_id)->get();
        $employees = Employee::where('organization_id', $organization_id)->get();
        $screens = Screen::all();
        //$employee_screen_permissions = EmployeeScreenPermissions::where('organization_id', Session::get('user-organization-id'))->get();
        $employee_screen_permissions = DB::table('employee_screen_permissions')->where('organization_id', $organization_id)->select(DB::raw('concat (employee_id,"~",screen_id) as permission'))->get();
        $user_level_screen_permissions = DB::table('user_level_screen_permissions')->where('organization_id', $organization_id)->select(DB::raw('concat (user_level_id,"~",screen_id) as permission'))->get();

        $employee_screen_permissions_array = [];
        foreach ($employee_screen_permissions as $employee_screen_permission) {
            array_push($employee_screen_permissions_array, $employee_screen_permission->permission);
        }

        $user_level_screen_permissions_array = [];
        foreach ($user_level_screen_permissions as $user_level_screen_permission) {
            array_push($user_level_screen_permissions_array, $user_level_screen_permission->permission);
        }

        return View::make('permissions.index', compact('user_levels', 'employees', 'screens', 'employee_screen_permissions_array', 'user_level_screen_permissions_array'));
    }

    public function ajaxUpdateEmployeeScreenPermissions()
    {
        $update_string = Input::get('update_string');
        $employee_id = Input::get('employee_id');
        $organization_id = Session::get('user-organization-id');
        $screen_permissions = json_decode($update_string);
        // delete the records for this particular employee + organization
        EmployeeScreenPermissions::where('employee_id', $employee_id)->where('organization_id', $organization_id)->delete();

        // insert new records set
        foreach ($screen_permissions as $permission) {
            $permission_data = array(
                'employee_id' => $permission->employee_id,
                'screen_id' => $permission->screen_id,
                'organization_id' => $organization_id,
            );
            EmployeeScreenPermissions::create($permission_data);
        }

        return "Successfully Updated";
    }

    public function ajaxUpdateUserLevelScreenPermissions()
    {
        $update_string = Input::get('update_string');
        $user_level_id = Input::get('user_level_id');
        $organization_id = Session::get('user-organization-id');
        $screen_permissions = json_decode($update_string);
        // delete the records for this particular employee + organization
        UserLevelScreenPermissions::where('user_level_id', $user_level_id)->where('organization_id', $organization_id)->delete();
        // insert new records set
        foreach ($screen_permissions as $permission) {
            $permission_data = array(
                'user_level_id' => $permission->user_level_id,
                'screen_id' => $permission->screen_id,
                'organization_id' => $organization_id,
            );
            UserLevelScreenPermissions::create($permission_data);
        }

        return "Successfully Updated";
    }




}