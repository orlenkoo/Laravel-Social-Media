<?php

class TeamsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     * GET /teams
     *
     * @return Response
     */
    public function index()
    {
        $teams = Team::where('organization_id', Session::get('user-organization-id'))->paginate(10);
        $employees = DB::table('employees')->where('organization_id', Session::get('user-organization-id'))->orderBy('given_name', 'asc')->lists('given_name', 'id');
        $employees_notlist = Employee::where('organization_id', Session::get('user-organization-id'))->orderBy('given_name', 'asc')->get();
        $team_assignments = TeamAssignment::where('organization_id', Session::get('user-organization-id'))->get();

        return View::make('teams.index', compact('teams', 'employees', 'employees_notlist', 'team_assignments'));
    }

    /**
     * Show the form for creating a new resource.
     * GET /teams/create
     *
     * @return Response
     */
    public function create()
    {
        $employees = DB::table('employees')->orderBy('given_name', 'asc')->lists('given_name', 'id');
        return View::make('teams.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     * POST /teams
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Team::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Team::create($data);
        //AuditTrail::addAuditEntry("Customer","Created -- ".json_encode($data));

        return Redirect::route('teams.index');
    }

    /**
     * Display the specified resource.
     * GET /teams/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $team = Team::findOrFail($id);

        return View::make('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     * GET /teams/{id}/edit
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $team = Team::find($id);
        $employees = DB::table('employees')->orderBy('given_name', 'asc')->lists('given_name', 'id');
        $employees_notlist = Employee::orderBy('given_name', 'asc')->get();
        $team_assignments = TeamAssignment::all();

        return View::make('teams.edit', compact('team', 'employees', 'employees_notlist', 'team_assignments'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /teams/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {


        $team = Team::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Team::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // return dd($data);

        $team->update($data);


        return Redirect::route('teams.index');
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /teams/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        Team::destroy($id);

        return Redirect::route('teams.index');
    }

    /**
     * Change the status of a account type to disable.
     *
     * @param  int $id
     * @return Response
     */
    public function disable($id)
    {
        $team = Team::findOrFail($id);
        $data = array('status' => 0);
        $team->update($data);

        return Redirect::route('teams.index');
    }

    /**
     * Change the status of a account type to enable.
     *
     * @param  int $id
     * @return Response
     */
    public function enable($id)
    {
        $team = Team::findOrFail($id);
        $data = array('status' => 1);
        $team->update($data);


        return Redirect::route('teams.index');
    }


    /*
     * Assign widgets to customers
     * takes customer id and widgets list that is to be assigned as argument
     */
    public function teamEmployeeAssignments()
    {
        //return dd(Input::all());
        $teamId = Input::get('team_id');
        $organizationId = Input::get('organization_id');

        DB::table('team_assignments')->where('team_id', '=', $teamId)->delete();

        if (Input::has('employees')) {
            $employees = Input::get('employees');

            foreach ($employees as $employee) {
                DB::table('team_assignments')->insert(
                    array('organization_id' => $organizationId, 'team_id' => $teamId, 'employee_id' => $employee)
                );
            }


        }

        //return Redirect::route('teams.edit', array('team_id' => $teamId));
        return Redirect::route('teams.index');

    }

    /*
     * Get the widget assignments for a customer
     */
    public static function getEmployeeAssignmentsByTeam($teamId)
    {
        $teamAssignments = DB::table('team_assignments')
            ->select('employee_id')
            ->where('team_id', '=', $teamId)
            ->get();

        return $teamAssignments;
    }

    public function ajaxReturnTeamsList()
    {
        $buildQuery = Team::select(DB::raw('team_name ,id'))->orderBy('team_name', 'asc');


        $teams = $buildQuery->get();

        return Response::make(json_encode($teams));
    }

    public function getTeamOverview()
    {
        $teams = Team::all();
        $employees = DB::table('employees')->orderBy('given_name', 'asc')->lists('given_name', 'id');
        $employees_notlist = Employee::orderBy('given_name', 'asc')->get();
        $team_assignments = TeamAssignment::all();

        return View::make('teams.team_overview', compact('teams', 'employees', 'employees_notlist', 'team_assignments'));

    }

}