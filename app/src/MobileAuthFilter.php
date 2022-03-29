<?php
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/7/2015
 * Time: 2:57 PM
 */
class MobileAuthFilter
{
    public function filter()
    {
        $user = UserService::getCurrentUser();

        if(isset($user)) {
            //return dd($user->email);
            $email = $user->email;
            if(Employee::postLoginEmployeeFunctions($email)) {
                // is a user
                //return true;
            } else {
                return Redirect::route('not_authorized');
            }
        } else {
            Session::flush();
            return Redirect::route('mobile.web.login');
        }

    }
}