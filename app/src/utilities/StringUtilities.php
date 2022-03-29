<?php
/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 28/11/2017
 * Time: 5:09 PM
 */

class StringUtilities
{
    public static function capitalizeString($string) {
        return str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($string))));
    }

    public static function setSelectedMenuClass($selected_class, $url_route) {
        $current_route = Route::currentRouteName();

        if($current_route == $url_route) {
            echo $selected_class;
        }
    }

    public static function trimText($string_to_trim, $no_of_chars, $end_chars) {
        return substr($string_to_trim, 0, $no_of_chars) . $end_chars;
    }
}