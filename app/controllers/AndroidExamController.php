<?php
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

class AndroidExamController extends BaseController
{

    public function listData()
    {
        $data_type = Input::get("data_type");

        if($data_type == "monthly_sales") {
            $data = array(
                "January" => "50000",
                "February" => "20000",
                "March" => "30000",
                "April" => "45000",
            );
        } else if($data_type == "hourly_activity_count") {
            $data = array(
                "08:00" => "20",
                "09:00" => "35",
                "10:00" => "40",
                "11:00" => "80",
                "12:00" => "25",
                "01:00" => "10",
                "02:00" => "20",
            );
        }

        $return_array = array(
            "data" => $data
        );


        return json_encode($return_array);
    }

}