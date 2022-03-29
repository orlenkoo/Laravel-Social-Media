<?php

class IndustriesController extends \BaseController {

	/**
	 * Display a listing of industries
	 *
	 * @return Response
	 */
	public function index()
	{
		$industries = Industry::where('organization_id',Session::get('user-organization-id'))->paginate(10);

		return View::make('industries.index', compact('industries'));
	}

	/**
	 * Show the form for creating a new industry
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('industries.create');
	}

	/**
	 * Store a newly created industry in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Industry::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Industry::create($data);

		return Redirect::route('industries.index');
	}

	/**
	 * Display the specified industry.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$industry = Industry::findOrFail($id);

		return View::make('industries.show', compact('industry'));
	}

	/**
	 * Show the form for editing the specified industry.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$industry = Industry::find($id);

		return View::make('industries.edit', compact('industry'));
	}

	/**
	 * Update the specified industry in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$industry = Industry::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Industry::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$industry->update($data);

		return Redirect::route('industries.index');
	}

	/**
	 * Remove the specified industry from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Industry::destroy($id);

		return Redirect::route('industries.index');
	}
    /**
     * Change the status of a account type to disable.
     *
     * @param  int  $id
     * @return Response
     */
    public function disable($id)
    {
        $industry = Industry::findOrFail($id);
        $data = array('status' => 0);
        $industry->update($data);

        return Redirect::route('industries.index');
    }

    /**
     * Change the status of a account type to enable.
     *
     * @param  int  $id
     * @return Response
     */
    public function enable($id)
    {
        $industry = Industry::findOrFail($id);
        $data = array('status' => 1);
        $industry->update($data);


        return Redirect::route('industries.index');
    }

	public function ajaxLoadIndustries()
	{
		//syslog(LOG_INFO, 'IndustriesController -- ajaxLoadIndustries --');
		$industries_json = "";
		if(Session::has('industries_list') && Session::get('industries_list') != '') {
			//syslog(LOG_INFO, 'IndustriesController -- ajaxLoadIndustries -- get from session --');
			$industries_json = Session::get('industries_list');

		} else {
			//syslog(LOG_INFO, 'IndustriesController -- ajaxLoadIndustries -- get from database ');
			$industries = Industry::select(DB::raw('industry ,id'))->where('organization_id',Session::get('user-organization-id'))->orderBy('industry', 'asc')->get();
			$industries_json = json_encode($industries);
			Session::set('industries_list', $industries_json);

		}
		//syslog(LOG_INFO, 'IndustriesController -- ajaxLoadIndustries -- industries_json -- '. $industries_json);
		return Response::make($industries_json);
	}

}
