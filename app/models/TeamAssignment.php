<?php

class TeamAssignment extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        'team_id' => 'required',
        'employee_id' => 'required'

    ];

	protected $fillable = ['organization_id', 'team_id', 'employee_id'];

    public function team(){
        return $this->belongsTo('Team', 'team_id');
    }

    public function employee(){
        return $this->belongsTo('Employee', 'employee_id');
    }

    public static function getTeamForEmployee($employee_id) {
        $team_id = DB::table('team_assignments')->where('employee_id', $employee_id)->pluck('team_id');
        return $team_id;
    }

    public static function getTeamNameForEmployee($employee_id) {
        $team_id = DB::table('team_assignments')->where('employee_id', $employee_id)->pluck('team_id');
        $team_name = DB::table('teams')->where('id', $team_id)->pluck('team_name');
        return $team_name;
    }

    public static function getEmployeesInTeam($team_id) {
        $employee_ids = DB::table('team_assignments')->where('team_id', $team_id)->lists('employee_id');

        return $employee_ids;
    }

}