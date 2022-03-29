<?php
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

class ActivityDashboardController extends BaseController
{

    public function index()
    {
        if (Request::url() != "http://elegant-cipher-95902.appspot.com") {
            date_default_timezone_set("Asia/Singapore");
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d', strtotime("+30 days"));
            return View::make('dashboard.activity_dashboard.index', compact('from_date', 'to_date'));
        } else {
            return Redirect::to('http://webtics.biz');
        }
    }

    public function performanceAndPipelineDashboard()
    {
        if (Request::url() != "http://elegant-cipher-95902.appspot.com") {
            date_default_timezone_set("Asia/Singapore");
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d', strtotime("+30 days"));
            return View::make('dashboard.performance_and_pipeline_dashboard.index', compact('from_date', 'to_date'));
        } else {
            return Redirect::to('http://webtics.biz');
        }
    }

    // Ajax functions

    /*
    *   Load the activities statistics for the activities dashboard
    */
    public function ajaxLoadActivitiesStatistics()
    {
        $sales_person = Input::get('sales_person');
        $activity_team_filter = Input::get('activity_team_filter');

        $activities_statistics = array(
            'number_of_calls_today' => ActivityLog::getActivityByTime('Call', 'Today', $sales_person, $activity_team_filter),
            'number_of_calls_this_week' => ActivityLog::getActivityByTime('Call', 'Week', $sales_person, $activity_team_filter),
            'number_of_calls_month_to_date' => ActivityLog::getActivityByTime('Call', 'Month', $sales_person, $activity_team_filter),
            'number_of_meetings_today' => ActivityLog::getActivityByTime('Meeting', 'Today', $sales_person, $activity_team_filter),
            'number_of_meetings_this_week' => ActivityLog::getActivityByTime('Meeting', 'Week', $sales_person, $activity_team_filter),
            'number_of_meetings_month_to_date' => ActivityLog::getActivityByTime('Meeting', 'Month', $sales_person, $activity_team_filter)
        );

        return View::make('dashboard.activity_dashboard._ajax_partials.activities_statistics', compact('activities_statistics'))->render();
    }

    /*
     * Load my activities for today
     */
    public function ajaxLoadMyActivitiesForDay()
    {
        $assigned_to = Input::get('sales_person');
        $activity_team_filter = Input::get('activity_team_filter');
//        $assigned_to = Session::get('user-id');

        date_default_timezone_set("Asia/Singapore");
        $today_date = date('Y-m-d');

        $activities_list = ActivityLog::where('date_of_activity', $today_date)->where('assigned_to', $assigned_to)->orderby('date_of_activity')->get();

        return View::make('dashboard.activity_dashboard._ajax_partials.todays_activities', compact('activities_list'))->render();
    }

    public function ajaxActivityChartView()
    {
        $sales_person = Input::get('sales_person');
        $activity_team_filter = Input::get('activity_team_filter');

        $number_of_meetings_this_week = ActivityLog::getActivityByTime('Meeting', 'Week', $sales_person, $activity_team_filter);
        $number_of_calls_this_week = ActivityLog::getActivityByTime('Call', 'Week', $sales_person, $activity_team_filter);
        if ($number_of_calls_this_week > 0) {
            $calls_conversion_rate = $number_of_meetings_this_week / $number_of_calls_this_week;
            $weekly_call_required = 12 / $calls_conversion_rate;
        } else {
            $weekly_call_required = 0;
        }
        $number_of_contracts_last_3months = Contract::getContractsByPeriod(date('Y-m-d'), $sales_person, $activity_team_filter, 'Contracts', '3Months')->count();
        $number_of_meetings_last_3months = ActivityLog::getActivityByTime('Meeting', '3Months', $sales_person, $activity_team_filter);
        if ($number_of_meetings_last_3months > 0 && $number_of_contracts_last_3months > 0) {
            $meeting_conversion_rate = $number_of_contracts_last_3months / $number_of_meetings_last_3months;
            $weekly_meetings_required = 2 / $meeting_conversion_rate;
        } else {
            $weekly_meetings_required = 0;
        }

        $activities_statistics = array(
            'weekly_call_required' => $weekly_call_required,
            'weekly_meetings_required' => $weekly_meetings_required,
            'average_calls_per_week' => ActivityLog::getActivityByTime('Call', '4Weeks', $sales_person, $activity_team_filter) / 4,
            'average_meetings_per_week' => ActivityLog::getActivityByTime('Meeting', '4Weeks', $sales_person, $activity_team_filter) / 4,
            'number_of_calls_this_week' => $number_of_calls_this_week,
            'number_of_calls_month_to_date' => ActivityLog::getActivityByTime('Call', 'Month', $sales_person, $activity_team_filter),
            'number_of_meetings_this_week' => $number_of_meetings_this_week,
            'number_of_meetings_month_to_date' => ActivityLog::getActivityByTime('Meeting', 'Month', $sales_person, $activity_team_filter)
        );

        return json_encode($activities_statistics);
    }

}