<?php

class AuditTrailsController extends \BaseController {

	public function index()
	{
		return View::make('audit_trails.index')->render();
	}

	public function ajaxLoadAuditTrailsList()
	{
		$employee_filter = Input::get('employee_filter');
		$organization_id = Session::get('user-organization-id');
		$filter_date_range = Input::get('dashboard_filter_date_range');
		$filter_from_date = Input::get('dashboard_filter_from_date');
		$filter_to_date = Input::get('dashboard_filter_to_date');
		$date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
		$from_date = $date_range['from_date']. ' 00:00:00';
		$to_date = $date_range['to_date']. ' 23:59:59';
		$user_level_filter = Input::get('user_level_filter');

		$build_query = Employee::where('organization_id', $organization_id)
			;
		if($employee_filter != '' && $employee_filter != 'null'){
			$employee_filter = explode(",",$employee_filter);
			$build_query->whereIn('id', $employee_filter);
		}

		if($user_level_filter != '' && $user_level_filter != 'null'){
			$user_level_filter = explode(",",$user_level_filter);
			$build_query->whereIn('user_level', $user_level_filter);
		}

		$audit_trail_employees = $build_query->orderBy('id','asc')->paginate(15);

		return View::make('audit_trails._ajax_partials.audit_trails_list', compact('audit_trail_employees','from_date','to_date'))->render();
	}

}
