<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 31/05/2016
 * Time: 09:23
 */
class UserLevelController extends BaseController
{
    /**
     * Display Screens
     *
     * @return Response
     */
    public function index()
    {
        $user_levels = UserLevel::where('organization_id',Session::get('user-organization-id'))->paginate(10);

        return View::make('user_levels.index', compact('user_levels'));
    }

    /**
     * Store a newly created resource in storage.
     * POST /user_levels
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), UserLevel::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        UserLevel::create($data);



        return Redirect::route('user_levels.index');
    }

    /**
     * Update the specified resource in storage.
     * PUT /user_levels/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $user_level = UserLevel::findOrFail($id);

        $validator = Validator::make($data = Input::all(), UserLevel::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $user_level->update($data);


        return Redirect::route('user_levels.index');
    }


    public function ajaxSearch()
    {
        $query_string = Input::get('s');
        $user_levels = UserLevel::where('user_level', 'LIKE', '%' . $query_string . '%')->get();

        return View::make('user_levels._ajax_partials.user_level_search_list_table', compact('user_levels'))->render();
    }

    /**
     * Change the status of a user level to disable.
     *
     * @param  int $id
     * @return Response
     */
    public function disable($id)
    {
        $user_level = UserLevel::findOrFail($id);
        $data = array('status' => 0);
        $user_level->update($data);

        return Redirect::route('user_levels.index');
    }

    /**
     * Change the status of a user level to enable.
     *
     * @param  int $id
     * @return Response
     */
    public function enable($id)
    {
        $user_level = UserLevel::findOrFail($id);
        $data = array('status' => 1);
        $user_level->update($data);


        return Redirect::route('user_levels.index');
    }


    public function ajaxGetUserLevelList()
    {
        $user_levels = UserLevel::select(DB::raw('user_level,id'))->where('organization_id', Session::get('user-organization-id'))->where('status', 1)->orderBy('user_level', 'asc')->get();
        $user_levels_json = json_encode($user_levels);

        return Response::make($user_levels_json);
    }
}