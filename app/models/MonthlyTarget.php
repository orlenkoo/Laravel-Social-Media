<?php

class MonthlyTarget extends \Eloquent
{
    public static $rules = [
    ];

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'updated_by',
        'value'
    ];

    public function employee()
    {
        return $this->belongsTo('Employee', 'employee_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('Employee', 'updated_by');
    }

    public static $month = array(
        'January' => 'January',
        'February' => 'February',
        'March' => 'March',
        'April' => 'April',
        'May' => 'May',
        'June' => 'June',
        'July' => 'July',
        'August' => 'August',
        'September' => 'September',
        'October' => 'October',
        'November' => 'November',
        'December' => 'December'
    );

    public static function year()
    {
        $year = array();
        for ($i = 2008; $i < 2025; $i++) {
            $year["$i"] = "$i";
        }
        return $year;
    }

    public static function getMonthlyTarget($employee_id, $team_id, $month, $year)
    {


        if($team_id != '') {
            $employee_list = TeamAssignment::getEmployeesInTeam($team_id);
            $monthly_target = MonthlyTarget::whereIn('employee_id', $employee_list)->where('month', $month)->where('year', $year)->get();
        } else {
            $monthly_target = MonthlyTarget::where('employee_id', $employee_id)->where('month', $month)->where('year', $year)->get();
        }



        return $monthly_target;
    }

    public static function getMonthlyTargetObj($employee_id, $month, $year)
    {
        $monthly_target = MonthlyTarget::where('employee_id', $employee_id)->where('month', $month)->where('year', $year)->first();
        return $monthly_target;

    }


}