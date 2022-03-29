<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/5/2016
 * Time: 9:52 AM
 */
class GeneralCommonFunctionHelper
{
    public static function findDifferenceBetweenDates ($start_date, $end_date, $difference_type) {
        date_default_timezone_set("Asia/Singapore");
        $difference = 0;
        $date_format = 'Y-m-d';
        $start_date = new DateTime($start_date);
        $end_date = new DateTime($end_date);
        $interval = $start_date->diff($end_date);

        switch($difference_type){
            case "days":
                //$difference = $interval->y * 365.25 + $interval->m * 30 + $interval->d + $interval->h/24 + $interval->i / 60;
                $difference = $interval->days;
                break;
            case "months":
                $difference = $interval->y * 12 + $interval->m + $interval->d/30 + $interval->h / 24;
                break;
            case "prorated-months":
                $difference = $interval->y * 12 + $interval->m + $interval->d/30 + $interval->h / 24;
                break;
            case "years":
                $difference =  $interval->y + $interval->m / 12 + $interval->d / 365.25;;
                break;
            default:
                break;
        }

        if( $interval->invert)
            return -1 * $difference;
        else
            return $difference;

        return $difference;
    }

    public static function rearrangeDateToAmericanFormat($date_string)
    {

        syslog(LOG_INFO, '$date_string -- '. $date_string);

        $date_components = explode('/', $date_string);

        $year = $date_components[2];
        $month = strlen($date_components[1]) == 2? $date_components[1]: "0" .$date_components[1];
        $date = strlen($date_components[0]) == 2? $date_components[0]: "0" .$date_components[0];

        $new_date_format = $year . '-' . $month . '-' . $date;

        syslog(LOG_INFO, '$new_date_format -- '. $new_date_format);

        return $new_date_format;
    }

    public static function checkIfValidDate($date_string)
    {
        try {
            $dt = new DateTime( trim($date_string) );
        }
        catch( Exception $e ) {
            return false;
        }
        $month = $dt->format('m');
        $day = $dt->format('d');
        $year = $dt->format('Y');
        if( checkdate($month, $day, $year) ) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function findNumberOfYearsBetween($start_date, $end_date)
    {
        date_default_timezone_set("Asia/Singapore");
        $date_format = 'Y-m-d';
        $start_year = date_create_from_format($date_format, $start_date)->format('Y');
        //syslog(LOG_INFO, '$joined_date -- '. $joined_date);
        //syslog(LOG_INFO, '$joined_date -- '. $current_date);
        $end_year = date_create_from_format($date_format, $end_date)->format('Y');
        return $end_year-$start_year;
    }

    public static function getNumbersDropDown ($number = 200) {
        $list_of_numbers = array();
        for ($i = 0; $i < $number; $i++) {
            $list_of_numbers[] = $i;
        }
        $list_of_numbers = array('' => 'Select') + $list_of_numbers;
        return $list_of_numbers;
    }

    public static function getAdvanceNumbersDropDown ($min = 0, $max = 200, $select_text = "Select", $append_to_beginning = null) {
        $list_of_numbers = array();

        if($append_to_beginning != null) {
            $list_of_numbers["$append_to_beginning"] = $append_to_beginning;
        }

        for ($i = $min; $i < $max; $i++) {
            $list_of_numbers["$i"] = $i;
        }
        $list_of_numbers = array('' => $select_text) + $list_of_numbers;
        return $list_of_numbers;
    }

    public static function generateYears ($plus = 0, $minus = 0) {
        date_default_timezone_set("Asia/Singapore");
        $current_year = date('Y');
        $start_year = $current_year - $minus;
        $end_year = $current_year + $plus;
        $year = array();
        for ($i = $start_year; $i <= $end_year; $i++) {
            $year["$i"] = "$i";
        }
        return $year;
    }

    public static function getHourlyDifferenceBetweenTwoDateTimes($start_date_time, $end_date_time) {
        $date1 = new DateTime($start_date_time);
        $date2 = new DateTime($end_date_time);

        $diff = $date2->diff($date1);

        $hours = $diff->h;
        $minutes = $diff->i;

        $hours = $hours + ($diff->days*24) + ($minutes/60);

        return $hours;
    }

    public static function endDateCalculationBasedOnPeriod($period, $period_type, $start_date){

        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = $start_date;

        if($period != '' && $period != 0){
            if($period_type == 'Daily'){
                $end_date = date('Y-m-d', strtotime($start_date. ' + '.$period.' days'));
            }elseif($period_type == 'Weekly'){
                $end_date = date('Y-m-d', strtotime($start_date. ' + '.$period.' week'));
            }elseif($period_type == 'Monthly'){
                $end_date = date('Y-m-d', strtotime($start_date. ' + '.$period.' month'));
            }
        }

        return $end_date;
    }

    public static function sanitizeUserInput($input) {

        $output = strip_tags($input);

        return $output;

    }
}