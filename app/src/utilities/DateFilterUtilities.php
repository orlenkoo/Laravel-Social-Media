<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2/8/2017
 * Time: 11:08 AM
 */
class DateFilterUtilities
{
    public static $date_ranges = array(
        'Today',
        'Custom',
        'Yesterday',
        'Last Week',
        'Last Month',
        'Last 7 Days',
        'Last 30 Days',
    );

    public static function getDateRange($date_range_type, $selected_from_date, $selected_to_date) {
        date_default_timezone_set("Asia/Singapore");

        $date_range = array();

        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        switch ($date_range_type) {
            case "Custom":
                $from_date = $selected_from_date ;
                $to_date = $selected_to_date ;
                break;
            case "Today":
                break;
            case "Yesterday":
                $from_date = date('Y-m-d',strtotime("-1 days"));
                $to_date = date('Y-m-d',strtotime("-1 days"));
                break;
            case "Last Week":
                $from_date = date("Y-m-d", strtotime("last week monday"));
                $to_date = date("Y-m-d", strtotime("last week sunday"));
                break;
            case "Last Month":
                $from_date = date('Y-m-d', strtotime('first day of last month'));
                $to_date = date('Y-m-d', strtotime('last day of last month'));
                break;
            case "Last 7 Days":
                $from_date = date('Y-m-d', strtotime('-7 days'));
                break;
            case "Last 30 Days":
                $from_date = date('Y-m-d', strtotime('-30 days'));
                break;
            default:
                echo "";
        }

        $date_range['from_date'] = $from_date;
        $date_range['to_date'] = $to_date;


        return $date_range;
    }
}