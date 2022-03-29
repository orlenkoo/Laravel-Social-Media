<?php

class Team extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        'team_name' => 'required',
        'team_lead_id' => 'required'

    ];

    protected $fillable = ['organization_id', 'team_name', 'team_lead_id', 'status'];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function teamlead()
    {
        return $this->belongsTo('Employee', 'team_lead_id');
    }

    public function teamassignments()
    {
        return $this->hasMany('TeamAssignment');
    }

    public function contracts()
    {
        return $this->hasMany('Contract');
    }

    public static function checkIfEmployeeAssigned($teamId, $employeeId)
    {
        $teamEmployeeAssignments = DB::table('team_assignments')
            ->where('organization_id',Session::get('user-organization-id'))
            ->where('team_id', '=', $teamId)
            ->where('employee_id', '=', $employeeId)
            ->get();
        if (count($teamEmployeeAssignments) > 0) {
            return true;
        } else {
            return false;
        }

    }

    public static function getTeamById($id)
    {
        $team_name = DB::table('teams')->where('id', $id)->pluck('team_name');
        return $team_name;
    }


}