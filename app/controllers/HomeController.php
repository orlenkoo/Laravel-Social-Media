<?php
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

class HomeController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function __construct()
    {
        $this->beforeFilter('force.ssl');
    }

    public function index()
    {

        $organization = session::get('user-organization-id');

        date_default_timezone_set("Asia/Singapore");
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d', strtotime("+30 days"));

        $user_level = Session::get('user-level');

        syslog(LOG_INFO, 'home -- $user_level -- ' . $user_level);

        // if old layout is selected redirect to old version.
        $use_old_layout = Session::get('user-use-old-layout');
        if($use_old_layout) {
            return Redirect::route('home_quick_access');
        }

        $dashboard_type = 'marketing';

        if (in_array($user_level, array('management', 'super_user'))){
            $dashboard_type = 'management';
        } else if ($user_level == 'sales') {
            $dashboard_type = 'sales';
        }

        return View::make('dashboard.index', compact('dashboard_type', 'from_date', 'to_date', 'organization'));


    }

    public function homeQuickAccess()
    {
        $organization = session::get('user-organization-id');

        if(Request::url() != "http://elegant-cipher-95902.appspot.com") {
            date_default_timezone_set("Asia/Singapore");
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d', strtotime("+30 days"));

                return View::make('quick_access', compact('from_date', 'to_date','organization'));

        } else {
            return Redirect::to('http://webtics.biz');
        }
    }

    public function showWelcome()
    {
        return View::make('index');
    }

    public function mobileView()
    {
        return View::make('mobile.index');
    }

    public function mobileLogin()
    {
        $email = Input::get('email');
        $returnString = "{";
        $employee = Employee::checkIfEmployeeLogin($email);

        if (Employee::checkIfEmployeeLogin($email)) {
            // check if there is a started meeting for this employee and if there is return that value as well
            $pendingMeeting = MeetingTracker::checkIfPendingMeetingIsThere(json_decode($employee)->id);
            if ($pendingMeeting) {
                $returnString .= '"pendingmeeting":' . $pendingMeeting . ',';
            }
            $returnString .= '"employee":' . $employee;
            return $returnString . '}';
        } else {
            return 'false';
        }
    }

    public function showNotAuthorized()
    {
        return View::make('notauth');
    }

    public function logout()
    {
        $url = '/login';
        Session::flush();
        return Redirect::to($url);
    }

    public function mobileLogout()
    {
        $url = UserService::createLogoutUrl('/mobileweb');
        Session::flush();

        return Redirect::to($url);
    }

    public function login()
    {
        $referring_url = Session::get('referring_url');
        return View::make('login', compact('referring_url'));
    }

    // mobile web login
    public function mobileWebLogin()
    {
        $url = UserService::createLoginUrl('/mobileweb/meetings');
        return View::make('mobile_web.login', compact('url'));
    }

    public function dashboardWidgetActivity()
    {
        $resultString = '';

        $resultString .= '<table>';
        $resultString .= '<tr><th>Number of Calls Today</th><td>' . ActivityLog::getActivityByTime('Call', 'Today') . '</td></tr>';
        $resultString .= '<tr><th>Number of Calls This Week</th><td>' . ActivityLog::getActivityByTime('Call', 'Week') . '</td></tr>';
        $resultString .= '<tr><th>Number of Calls Month-to-date</th><td>' . ActivityLog::getActivityByTime('Call', 'Month') . '</td></tr>';
        $resultString .= '<tr><th>Number of Meetings Today</th><td>' . ActivityLog::getActivityByTime('Meeting', 'Today') . '</td></tr>';
        $resultString .= '<tr><th>Number of Meetings This Week</th><td>' . ActivityLog::getActivityByTime('Meeting', 'Week') . '</td></tr>';
        $resultString .= '<tr><th>Number of Meetings Month-to-date</th><td>' . ActivityLog::getActivityByTime('Meeting', 'Month') . '</td></tr>';

        $resultString .= '</table>';

        return $resultString;
    }

    public function dashboardWidgetPipelineBookValue()
    {
        $resultString = '';

        $resultString .= '<table>';
        $resultString .= '<tr><th>Total Pipeline</th><td>' . Contract::getPipeline('Book Value', 'Total Pipeline') . '</td></tr>';
        $resultString .= '<tr><th>Weighted Pipeline</th><td>' . Contract::getPipeline('Book Value', 'Weighted Pipeline') . '</td></tr>';
        $resultString .= '<tr><th>Call</th><td>' . Contract::getPipeline('Book Value', 'Call') . '</td></tr>';
        $resultString .= '<tr><th>Upsides</th><td>' . Contract::getPipeline('Book Value', 'Upsides') . '</td></tr>';
        $resultString .= '<tr><th>Closed Sales</th><td>' . Contract::getPipeline('Book Value', 'Closed Sales') . '</td></tr>';

        $resultString .= '</table>';

        return $resultString;
    }

    public function dashboardWidgetPipelineTotalRevenue()
    {
        $resultString = '';

        $resultString .= '<table>';
        $resultString .= '<tr><th>Total Pipeline</th><td>' . Contract::getPipeline('Total Revenue', 'Total Pipeline') . '</td></tr>';
        $resultString .= '<tr><th>Weighted Pipeline</th><td>' . Contract::getPipeline('Total Revenue', 'Weighted Pipeline') . '</td></tr>';
        $resultString .= '<tr><th>Call</th><td>' . Contract::getPipeline('Total Revenue', 'Call') . '</td></tr>';
        $resultString .= '<tr><th>Upsides</th><td>' . Contract::getPipeline('Total Revenue', 'Upsides') . '</td></tr>';
        $resultString .= '<tr><th>Closed Sales</th><td>' . Contract::getPipeline('Total Revenue', 'Closed Sales') . '</td></tr>';

        $resultString .= '</table>';

        return $resultString;
    }

    public function ajaxMyActivities()
    {
        date_default_timezone_set("Asia/Singapore");
        $buildQuery = ActivityLog::orderby('date_of_activity');

        $activity_from_date_filter = Input::get('activity_from_date_filter');
        syslog(LOG_INFO, 'activity_from_date_filter -- ' . $activity_from_date_filter);
        if ($activity_from_date_filter == '') {
            $activity_from_date_filter = date('Y-m-d');
            //$buildQuery->where('date_of_activity', '<=', $activity_from_date_filter);
        }

        $activity_to_date_filter = Input::get('activity_to_date_filter');
        syslog(LOG_INFO, 'activity_to_date_filter -- ' . $activity_to_date_filter);
        if ($activity_to_date_filter == '') {
            $activity_to_date_filter = date('Y-m-d');
            //$buildQuery->where('date_of_activity', '>=', $activity_to_date_filter);
        }

        $buildQuery->whereBetween('date_of_activity', array($activity_from_date_filter, $activity_to_date_filter));

        $activity_type_filter = Input::get('activity_type_filter');
        syslog(LOG_INFO, 'activity_type_filter -- ' . $activity_type_filter);
        if ($activity_type_filter != '') {
            $buildQuery->where('activity_type', '=', $activity_type_filter);
        }

        $activity_team_filter = Input::get('activity_team_filter');
        syslog(LOG_INFO, 'activity_team_filter -- ' . $activity_team_filter);


        $activity_employee_filter = Input::get('activity_employee_filter');
        syslog(LOG_INFO, 'activity_employee_filter -- ' . $activity_employee_filter);
        syslog(LOG_INFO, 'user id -- ' . Session::get('user-id'));

        if ($activity_team_filter != 'undefined' && $activity_team_filter != '') {
            $employees = TeamsController::getEmployeeAssignmentsByTeam($activity_team_filter);

            $employeeIds = array_map(create_function('$employee', 'return $employee->employee_id;'), $employees);

            $buildQuery->whereIn('assigned_to', $employeeIds);
        } else if ($activity_employee_filter != 'undefined' && $activity_employee_filter != '') {
            $buildQuery->where('assigned_to', '=', $activity_employee_filter);
        } else {
            $buildQuery->where('assigned_to', '=', Session::get('user-id'));
        }

        $activities_list = $buildQuery->get();

        $queries = DB::getQueryLog();
        $last_query = end($queries);

        syslog(LOG_INFO, 'last query -- ' . json_encode($last_query));
        return View::make('dashboard._ajax_partials.my_activities_list_table', compact('activities_list'))->render();
    }

    public function ajaxUniversalSearch()
    {
        $search_for = Input::get('search_for');
        $search_text = Input::get('search_text');

        $search_result = '';
        if ($search_text == '') {
            return 'Enter a search text';
        }
        if ($search_for == 'account') {
            $search_by_account = Input::get('search_by_account');

            if ($search_by_account == 'customer_name') {
                $buildQuery = Customer::where('account_name', 'LIKE', '%' . $search_text . '%');
            } elseif ($search_by_account == 'account_owner') {
                $employees = Employee::where('given_name', 'LIKE', '%' . $search_text . '%')->orWhere('surname', 'LIKE', '%' . $search_text . '%')->get();
                $employee_ids = array();
                foreach ($employees as $employee) {
                    $employee_ids[] = $employee->id;
                }
                $buildQuery = Customer::whereIn('account_owner_id', $employee_ids);
            } elseif ($search_by_account == 'sales_person') {
                $employees = Employee::where('given_name', 'LIKE', '%' . $search_text . '%')->orWhere('surname', 'LIKE', '%' . $search_text . '%')->get();
                $employee_ids = array();
                foreach ($employees as $employee) {
                    $employee_ids[] = $employee->id;
                }
                $buildQuery = Customer::whereIn('assigned_sales_person', $employee_ids);
            }

            $customers = $buildQuery->orderBy('last_view_date_time', 'DESC')->take(10)->get();

            return View::make('customers._ajax_partials.customer_table', compact('customers'))->render();

        } elseif ($search_for == 'contact') {
            $search_by_contact = Input::get('search_by_contact');
            if ($search_by_contact == 'given_name') {
                $buildQuery = Contact::where('given_name', 'LIKE', '%' . $search_text . '%');
//                dd($buildQuery);
            } elseif ($search_by_contact == 'surname') {
                $buildQuery = Contact::where('surname', 'LIKE', '%' . $search_text . '%');
            }
            $contacts = $buildQuery->orderBy('id', 'DESC')->take(10)->get();
            $editable = false;

            return View::make('contacts.index_list', compact('contacts', 'editable'))->render();
        } elseif ($search_for == 'contract') {
            $search_by_contract = Input::get('search_by_contract');
            if ($search_by_contract == 'project_quote') {
                $buildQuery = Contract::where('project_quote', 'LIKE', '%' . $search_text . '%');
            } elseif ($search_by_contract == 'sales_person') {
                $employees = Employee::where('given_name', 'LIKE', '%' . $search_text . '%')->orWhere('surname', 'LIKE', '%' . $search_text . '%')->get();
                $employee_ids = array();
                foreach ($employees as $employee) {
                    $employee_ids[] = $employee->id;
                }
                $buildQuery = Contract::whereIn('sales_person', $employee_ids);
            } elseif ($search_by_contract == 'assigned_specialist') {
                $employees = Employee::where('given_name', 'LIKE', '%' . $search_text . '%')->orWhere('surname', 'LIKE', '%' . $search_text . '%')->get();
                $employee_ids = array();
                foreach ($employees as $employee) {
                    $employee_ids[] = $employee->id;
                }
                $buildQuery = Contract::whereIn('assigned_specialist', $employee_ids);
            }
            $contracts = $buildQuery->orderBy('id', 'DESC')->take(10)->get();
            $editable = false;

            return View::make('customers._partials.contracts_list', compact('contracts', 'editable'))->render();
        } elseif ($search_for == 'campaign') {
            $search_by_campaign = Input::get('search_by_campaign');
            $campaign_book = true;
            if ($search_by_campaign == 'project_quote') {
                $contracts = Contract::where('project_quote', 'LIKE', '%' . $search_text . '%')->take(10)->get();
                $campaigns = array();
                foreach ($contracts as $contract) {
                    if (count($contract->campaigns) > 0) {
                        foreach ($contract->campaigns as $single_campaign) {
                            $campaigns[] = $single_campaign;
                        }

                    }
                }
            } elseif ($search_by_campaign == 'campaign_name') {
                $campaigns = Campaign::where('campaign_name', 'LIKE', '%' . $search_text . '%')->take(10)->get();
            }

            return View::make('customers._partials.campaigns_list', compact('campaigns', 'campaign_book'))->render();
        }
    }
}
