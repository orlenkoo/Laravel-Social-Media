<?php

use Illuminate\Support\Facades\Hash;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/6/2016
 * Time: 10:27 AM
 */
class SignUpProcessController extends BaseController
{


    public function submitLogin()
    {
        $email = Input::get('email');
        $password =  Input::get('password');
        $referring_url = Input::get('referring_url');

        $employee = Employee::where('email', '=', $email)->first();

        syslog(LOG_ERR, 'login_attempts --' . $employee->login_attempts);
        $time_difference = GeneralCommonFunctionHelper::getHourlyDifferenceBetweenTwoDateTimes($employee->last_login_attempt_time, date('Y-m-d h:i:s', time()));
        syslog(LOG_ERR, 'time_difference --' . $time_difference);

        if ($employee->login_attempts > 3 && $time_difference < 1) {
            return 'Password attempts exceeded. Please wait 1 hour before trying again. Or contact admin.';
        }

        syslog(LOG_INFO, 'submitLogin -- 001');
        if (is_object($employee)) {
            syslog(LOG_INFO, 'submitLogin -- 002');
            if (Hash::check($password, $employee['password'])) {
                syslog(LOG_INFO, 'submitLogin -- 003');
                $employee->update(['login_attempts' => 0]);
                if (Employee::postLoginEmployeeFunctions($employee)) {
                    syslog(LOG_INFO, 'submitLogin -- 004');
                    if ($referring_url != '') {
                        syslog(LOG_INFO, 'submitLogin -- 005');
                        return Redirect::to($referring_url);
                    } else {
                        syslog(LOG_INFO, 'submitLogin -- 006');
                        return Redirect::route('home');
                    }
                    // is a user
                    //return true;

                    $audit_action = array(
                        'action' => 'login',
                        'model-id' => $employee->id,
                        'data' => $employee
                    );
                    AuditTrail::addAuditEntry("SystemLogin", json_encode($audit_action));
                } else {
                    syslog(LOG_INFO, 'submitLogin -- 007');
                    Session::flush();
                    return Redirect::route('not_authorized');
                }
            } else {
                syslog(LOG_INFO, 'submitLogin -- 008');
                $employee->update(['login_attempts' => $employee->login_attempts + 1, 'last_login_attempt_time' => date('Y-m-d h:i:s', time())]);

                return Redirect::route('not_authorized');
            }
        } else {
            syslog(LOG_INFO, 'submitLogin -- 009');
            Session::flush();
            return Redirect::route('not_authorized');
        }
    }
}
