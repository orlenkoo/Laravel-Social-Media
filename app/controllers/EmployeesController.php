<?php

class EmployeesController extends \BaseController
{

    public function ajaxLoadEmployeesList()
    {
        $organization_id = Session::get('user-organization-id');
        $search_query = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('search_query'));
        $search_user_level = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('search_user_level'));

        $build_query = Employee::where('organization_id', $organization_id);

        if ($search_query != '') {

            $build_query->where(function ($build_query) use ($search_query) {
                $build_query->where('given_name', 'LIKE', '%' . $search_query . '%')
                    ->orWhere('surname', 'LIKE', '%' . $search_query . '%')
                    ->orWhere('designation', 'LIKE', '%' . $search_query . '%')
                    ->orWhere('contact_no', 'LIKE', '%' . $search_query . '%')
                    ->orWhere('email', 'LIKE', '%' . $search_query . '%');
            });
        }

        if ($search_user_level != '') {
            $build_query->where('user_level', 'LIKE', '%' . $search_user_level . '%');
        }

        $employees = $build_query->paginate(10);

        return View::make('employees._ajax_partials.employees_list', compact('employees'))->render();
    }

    public function ajaxSaveEmployee()
    {
        $organization_id = Session::get('user-organization-id');

        $given_name = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('given_name'));
        $surname = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('surname'));
        $user_level = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('user_level'));
        $designation = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('designation'));
        $country_code = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('country_code'));
        $contact_no = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('contact_no'));
        $email = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('email'));
        $password = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('password'));

        $response = array(
            'status'  => '',
            'message' => ''
        );

        if (count(Employee::getEmployeeByEmail($email)) != 0) {
            $response['status'] = 'fail';
            $response['message'] = 'Employee with email already exists.';

            return Response::json($response);
        }

        $data_employee = array(
            'organization_id' => $organization_id,
            'given_name' => $given_name,
            'surname' => $surname,
            'user_level' => $user_level,
            'designation' => $designation,
            'country_code' => $country_code,
            'contact_no' => $contact_no,
            'email' => $email,
            'password' => Hash::make($password),
            'status' => 1
        );

        $employee = Employee::create($data_employee);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $employee->id,
            'data' => $data_employee
        );
        AuditTrail::addAuditEntry("Employee", json_encode($audit_action));

        $response['status'] = 'success';
        $response['message'] = 'Employee added Successfully.';
        return Response::json($response);
    }

    public function ajaxUpdatePassword()
    {
        $employee_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('employee_id'));
        $password = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('new_password'));

        // update password
        $employee = Employee::find($employee_id);

        if (is_object($employee)) {
            $data_password = array('password' => Hash::make($password));
            $employee->update($data_password);

            $audit_action = array(
                'action' => 'update',
                'model-id' => $employee->id,
                'data' => $data_password
            );
            AuditTrail::addAuditEntry("Employee", json_encode($audit_action));
            return "Successfully Updated the Password.";
        } else {
            return "Employee cannot be found.";
        }
    }

    public function ajaxGetEmployeesList()
    {
        if (Session::has('employees_list') && Session::get('employees_list') != '') {
            //syslog(LOG_INFO, 'Employees_controller -- ajaxGetEmployeesList -- get from session -- ');
            $employees_json = Session::get('employees_list');
        } else {
            //syslog(LOG_INFO, 'Employees_controller -- ajaxGetEmployeesList -- get from database -- ');
            //$employees = Employee::select(DB::raw('concat (given_name," ",surname) as full_name,id'))->orderBy('full_name', 'asc')->lists('full_name', 'id');
            $employees = DB::table('employees')
                ->select(DB::raw('concat (given_name," ",surname, " | ", UCASE(user_level)) as full_name,id'))
                ->where('status', 1)
                ->where('organization_id', Session::get('user-organization-id'))
                ->orderBy('full_name', 'asc')
                ->get();

            $queries = DB::getQueryLog();
            $last_query = end($queries);

            syslog(LOG_INFO, 'last query -- ' . json_encode($last_query));

            $employees_json = json_encode($employees);

            syslog(LOG_INFO, 'employees list -- ' . $employees_json);

            Session::set('employees_list', $employees_json);
        }

        return Response::make($employees_json);
    }

    public function ajaxCheckEmailExists()
    {
        $email = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('email'));
        $employee = Employee::where('email', $email)->first();

        if (is_object($employee)) {
            Employee::sendPasswordresetEmail($email);
            return "success";
        } else {
            return "fail";
        }
    }

    public function showPasswordResetForm()
    {

        $_token = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('_token'));
        if ($_token != '' && $_token != null && $this->checkValidToken($_token)) {
            $employee_password_reset_token = EmployeePasswordResetToken::where('token', $_token)->first();
            $user_id = $employee_password_reset_token->user_id;
            return View::make('password_reset_form', compact('_token', 'user_id'));
        }
        return Redirect::route('home');
    }

    public function passwordReset()
    {
        $_token = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('_token'));
        $user_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('user_id'));
        $new_password = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('new_password'));

        if ($this->checkValidToken($_token)) {
            $employee = Employee::findOrFail($user_id);
            $employee_password_reset_token = EmployeePasswordResetToken::where('token', $_token)->first();
            $data['password'] = Hash::make($new_password);
            $employee->update($data);
            $token_data['status'] = 0;
            $employee_password_reset_token->update($token_data);
        }


        return Redirect::route('home');
    }



    public function myProfile()
    {

        $user_id = Session::get('user-id');
        $employee = Employee::find($user_id);

        return View::make('employees.my_profile', compact('employee'))->render();
    }

    public function ajaxSaveProfileChanges()
    {

        $user_id = Session::get('user-id');
        $given_name = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('given_name'));
        $surname = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('surname'));
        $designation = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('designation'));
        $contact_no = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('contact_no'));
        $country_code = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('country_code'));

        $data_employee = array(
            'given_name' => $given_name,
            'surname' => $surname,
            'designation' => $designation,
            'country_code' => $country_code,
            'contact_no' => $contact_no,
        );

        $employee = Employee::find($user_id);
        $employee->update($data_employee);

        $audit_action = array(
            'action' => 'update',
            'model-id' => $employee->id,
            'data' => $data_employee
        );
        AuditTrail::addAuditEntry("Employee", json_encode($audit_action));

        return "Updated Successfully.";
    }

    public function ajaxProfileImageChange()
    {
        if (Input::hasFile('profile_image')) {
            $employee = Employee::findOrFail(Session::get('user-id'));

            $profile_image = Input::file('profile_image');
            $file_name = Input::get('file_name');

            if (in_array($profile_image->getClientOriginalExtension(), ['jpeg', 'jpg', 'png', 'gif'])) {
                // generate file name
                $file_name = 'profile_image_' . $employee->id . '_' . $file_name . '.' . $profile_image->getClientOriginalExtension();

                $file_save_data = GCSFileHandler::saveFile($profile_image, $file_name);

                $data_employee['profile_image_gcs_file_url'] = $file_save_data['gcs_file_url'];
                $data_employee['profile_image_file_url'] = $file_save_data['image_url'];

                $employee->update($data_employee);

                Session::set('user-profile-image', $file_save_data['image_url']);

                $audit_action = array(
                    'action' => 'update',
                    'model-id' => $employee->id,
                    'data' => $data_employee
                );
                AuditTrail::addAuditEntry("Employee", json_encode($audit_action));

                return "Profile Image has been updated successfully";
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function ajaxUpdateEmail()
    {
        $response = array(
            'status'  => '',
            'message' => ''
        );

        $employee_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('employee_id'));
        $email = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('email'));

        if ($email != '') {
            $response['status'] = 'fail';
            $response['message'] = 'Email Cannot be Empty.';
        }

        if (count(Employee::getEmployeeByEmail($email)) != 0) {
            $response['status'] = 'fail';
            $response['message'] = 'Employee with email already exists.';

            return Response::json($response);
        }

        // update email
        DB::table('employees')
            ->where('id', $employee_id)
            ->update(array(
                'email' => $email
            ));

        $response['status'] = 'success';
        $response['message'] = 'Successfully Updated the Email.';
        return Response::json($response);
    }

    public function ajaxDeleteProfileImage()
    {
        $employee_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('employee_id'));

        DB::table('employees')
            ->where('id', $employee_id)
            ->update(array(
                'profile_image_gcs_file_url' => null,
                'profile_image_file_url' => null
            ));

        Session::forget('user-profile-image');

        return "Successfully Removed the profile image";
    }

    public function ajaxSignatureChange()
    {
        $employee = Employee::findOrFail(Session::get('user-id'));

        if (Input::has('signature_html')) {
            $signature_html = Input::get('signature_html');
            syslog(LOG_INFO, '$signature_html -- ' . $signature_html);
            DB::table('employees')->where('id', $employee->id)->update(array('signature_html' => $signature_html));
        }

        return "Email Signature has been updated successfully";
    }

    public function ajaxSignatureImageChange()
    {
        $employee = Employee::findOrFail(Session::get('user-id'));

        if (Input::hasFile('signature_image')) {
            $signature_image = Input::file('signature_image');
            $signature_file_name = Input::get('signature_file_name');

            if (in_array($signature_image->getClientOriginalExtension(), ['jpeg', 'jpg', 'png', 'gif'])) {
                // generate file name
                $signature_file_name = 'signature_image_' . $employee->id . '_' . $signature_file_name . '.' . $signature_image->getClientOriginalExtension();

                $file_save_data = GCSFileHandler::saveFile($signature_image, $signature_file_name);

                $data_employee['signature_gcs_file_url'] = $file_save_data['gcs_file_url'];
                $data_employee['signature_file_url'] = $file_save_data['image_url'];

                $employee->update($data_employee);

                $audit_action = array(
                    'action' => 'update',
                    'model-id' => $employee->id,
                    'data' => $data_employee
                );
                AuditTrail::addAuditEntry("Employee", json_encode($audit_action));
            } else {
                return false;
            }
        }

        return "Email Signature Image has been updated successfully";
    }

    public function ajaxDeleteSignatureImage()
    {
        $employee_id = Input::get('employee_id');

        DB::table('employees')
            ->where('id', $employee_id)
            ->update(array(
                'signature_gcs_file_url' => null,
                'signature_file_url' => null
            ));

        return "Successfully Removed the signature image";
    }

    public function checkValidToken($_token)
    {
        date_default_timezone_set("Asia/Singapore");
        $employee_password_reset_token = EmployeePasswordResetToken::where('token', $_token)->first();
        $status = $employee_password_reset_token->status;
        $created_timestamp = $employee_password_reset_token->created_timestamp;
        $created_timestamp = new DateTime($created_timestamp);
        $date_time_now = new DateTime(date('Y-m-d h:i:sa'));
        $interval = $created_timestamp->diff($date_time_now);
        $interval = $interval->format('%h');
        $token_check_status  = false;


        if ($status == 1) {
            if ($interval < 1) {
                $token_check_status = true;
            } else {
                $token_data['status'] = 0;
                $employee_password_reset_token->update($token_data);
                $token_check_status = false;
            }
        }

        return $token_check_status;
    }

    public function ajaxUpdateLayoutPreference()
    {
        $employee_id = Session::get('user-id');
        $selected_the_layout_once = Session::get('user-selected-the-layout-once');
        $layout_preference = Input::get('layout_preference');

        $data_employee = array();

        if ($layout_preference == 'old_layout') {
            $data_employee['use_old_layout'] = 1;
        } else {
            $data_employee['use_old_layout'] = 0;
        }


        Session::set('user-use-old-layout', $data_employee['use_old_layout']);

        if ($selected_the_layout_once == 0) {
            $data_employee['selected_the_layout_once'] = 1;
            Session::set('user-selected-the-layout-once', 1);
        }

        DB::table('employees')
            ->where('id', $employee_id)
            ->update($data_employee);
    }

    public function ajaxUpdateGuidedTourStatus()
    {
        $employee_id = Session::get('user-id');

        DB::table('employees')
            ->where('id', $employee_id)
            ->update(array('took_the_guide' => 1));

        Session::set('user-took-the-guide', 1);
    }

    public function ajaxUpdateEmployeeNotifications()
    {
        $employee_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('employee_id'));
        $type_of_alert = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('type_of_alert'));
        $type_of_notification = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('type_of_notification'));
        $value = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('value'));

        $notification = Notification::where('employee_id', $employee_id)
            ->where('type_of_alert', $type_of_alert)
            ->where('organization_id', Session::get('user-organization-id'))
            ->first();

        $data[$type_of_notification] = $value;

        if ($notification) {
            $notification->update($data);
        } else {
            $data['employee_id'] = $employee_id;
            $data['type_of_alert'] = $type_of_alert;
            $data['organization_id'] = Session::get('user-organization-id');
            $data['status'] = 1;
            $notification = Notification::create($data);
        }

        return "Successfully Updated";
    }
}
