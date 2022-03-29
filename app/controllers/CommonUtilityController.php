<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 19/5/2017
 * Time: 2:29 PM
 */
class CommonUtilityController extends BaseController
{
    public function ajaxUpdateIndividualFieldsOfModel()
    {
        $table_name = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('table_name'));
        $row_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('row_id'));
        $column_name = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('column_name'));
        $column_value = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('column_value'));
        $model_name = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('model_name'));

        syslog(LOG_INFO, '$table_name -- '.$table_name);
        syslog(LOG_INFO, '$row_id -- '.$row_id);
        syslog(LOG_INFO, '$column_name -- '.$column_name);
        syslog(LOG_INFO, '$column_value -- '.$column_value);
        syslog(LOG_INFO, '$model_name -- '.$model_name);

       DB::table($table_name)
            ->where('id', $row_id)
            ->update(array($column_name => $column_value));

       if($model_name != ""){
           $audit_action = array(
               'action' => 'update',
               'model-id' => $row_id,
               'data' => array($column_name => $column_value)
           );
           AuditTrail::addAuditEntry($model_name, json_encode($audit_action));
           //Audit Trail Code
       }

        return "Updated Successfully.";
    }

    public function ajaxPostDateRangeSessions()
    {
        $date_range = Input::get('dashboard_filter_date_range');
        $from_date = Input::get('dashboard_filter_from_date');
        $to_date = Input::get('dashboard_filter_to_date');

        syslog(LOG_INFO, '$date_range -- ' .$date_range);
        syslog(LOG_INFO, '$from_date -- ' .$from_date);
        syslog(LOG_INFO, '$to_date -- ' .$to_date);

        Session::forget('dashboard_date_range');
        Session::forget('dashboard_from_date');
        Session::forget('dashboard_to_date');

        Session::set('dashboard_date_range', $date_range);
        Session::set('dashboard_from_date', $from_date);
        Session::set('dashboard_to_date', $to_date);

        syslog(LOG_INFO, 's $date_range -- ' .Session::get('dashboard_date_range'));
        syslog(LOG_INFO, 's $from_date -- ' .Session::get('dashboard_from_date'));
        syslog(LOG_INFO, 's $to_date -- ' .Session::get('dashboard_to_date'));

        return "";
    }
}