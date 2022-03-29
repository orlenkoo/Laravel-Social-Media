<?php

class MonthlyForecast extends \Eloquent
{
    public static $rules = [
    ];

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'updated_date',
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

    public static function getMonthlyForecastLast($employee_id, $team_id, $month, $year)
    {



        if($team_id != '') {
            $employee_list = TeamAssignment::getEmployeesInTeam($team_id);
            $monthly_forecast_list = array();
            foreach ($employee_list as $employee_id) {
                $monthly_forecast_value = MonthlyForecast::getMonthlyForecastLastValue($employee_id, $month, $year);

                    $monthly_forecast_list[] = $monthly_forecast_value;

            }

                return array_sum($monthly_forecast_value);

        } else {
            $monthly_forecast_value = MonthlyForecast::getMonthlyForecastLastValue($employee_id, $month, $year);

            return $monthly_forecast_value;

        }

    }

    public static function getMonthlyForecastLastValue($employee_id, $month, $year)
    {
        $monthly_forecast = MonthlyForecast::where('employee_id', $employee_id)->where('month', $month)->where('year', $year)->orderBy('id', 'desc')->first();


        if (is_object($monthly_forecast)) {
            return $monthly_forecast->value;
        } else {
            return 0;
        }
    }

    public static function getMonthlyForecastLastObj($employee_id, $month, $year)
    {
        $monthly_forecast = MonthlyForecast::where('employee_id', $employee_id)->where('month', $month)->where('year', $year)->orderBy('id', 'desc')->first();
        return $monthly_forecast;
    }

    public static function getMonthlyForecasts($employee_id, $month, $year)
    {
        $monthly_forecasts = MonthlyForecast::where('employee_id', $employee_id)->where('month', $month)->where('year', $year)->get();
        return $monthly_forecasts;
    }

}