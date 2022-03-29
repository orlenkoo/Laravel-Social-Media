<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 29/4/2016
 * Time: 9:16 AM
 */
class TestController  extends \BaseController
{
    public function index()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $difference_type = Input::get('difference_type');



       /* echo "months --" . $this->findDifference($start_date, $end_date, "months");
        echo "prorated-months --" . $this->findDifference($start_date, $end_date, "prorated-months");
        echo "years --" . $this->findDifference($start_date, $end_date, "years");*/
        //return GeneralCommonFunctionHelper::findDifference($start_date, $end_date, $difference_type);
        return GeneralCommonFunctionHelper::findNumberOfYearsBetween($start_date, $end_date);
    }

    public function findDifference ($start_date, $end_date, $difference_type) {
        $difference = 0;
        $date_format = 'Y-m-d';
        $current_date = date_create_from_format($date_format, $start_date);
        //syslog(LOG_INFO, '$joined_date -- '. $joined_date);
        //syslog(LOG_INFO, '$joined_date -- '. $current_date);
        $joined_date = date_create_from_format($date_format, $end_date);
        $interval = $current_date->diff($joined_date);

        switch($difference_type){
            case "months":
                $difference = $interval->m + 12*$interval->y;
                break;
            case "prorated-months":
                $difference = $interval->m;
                break;
            case "years":
                $difference = $interval->y;
                break;
            default:
                break;
        }



        return $difference;
    }

    public function getContentClassification(){

        $content = 'Google, headquartered in Mountain View, unveiled the new Android phone at the Consumer Electronic Show.  Sundar Pichai said in his keynote that users love their new Android phones.';

        $categories = GoogleCloudNaturalLanguageUtilities::getContentClassification($content);

        dd($categories);

    }
}