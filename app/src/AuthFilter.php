<?php

/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 24/2/2015
 * Time: 2:24 PM
 * This for authentication functions of the system
 */
class AuthFilter
{

    public function filter()
    {

        if (!Session::has('user-email') || !Session::has('user-organization-id')) {
            Session::flush();
            $referring_url = Request::fullUrl();

            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $referring_url = route('home');
                Session::set('referring_url', $referring_url);
                return Response::json([
                    'not-authorized' => 'not-authorized'
                ], 401);
            }
            Session::set('referring_url', $referring_url);
            return Redirect::route('login');
        }

    }
} 