<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/7/2015
 * Time: 3:03 PM
 */
class MobileWebController extends BaseController
{
    public function getMeetings()
    {
        $employee_id = Session::get('user-id');
        syslog(LOG_INFO, $employee_id);
        date_default_timezone_set("Asia/Singapore");
        $date = date('Y-m-d');
        //$meetings = DB::table('activity_logs')->where('activity_type', 'meeting')->where('assigned_to' , $employee_id)->where('date_of_activity' , $date)->get();
        $meetings = ActivityLog::where('assigned_to', $employee_id)->where('date_of_activity', $date)->where('activity_type', 'meeting')->get();

        return View::make('mobile_web.meetings', compact('meetings', 'employee_id', 'date'));
    }

    public function updateMeeting()
    {
        $meeting_id = Input::get('meeting_id');
        $status = Input::get('status');
        $latitude = Input::get('latitude');
        $longitude = Input::get('longitude');
        $address = Input::get('address');

        syslog(LOG_INFO, '$meeting_id --' . $meeting_id);
        syslog(LOG_INFO, 'status --' . $status . '-');
        syslog(LOG_INFO, '$latitude --' . $latitude);
        syslog(LOG_INFO, '$longitude --' . $longitude);
        syslog(LOG_INFO, '$address --' . $address);


        date_default_timezone_set("Asia/Singapore");

        //$date = date('d/m/Y');
        $time = date('h:i:s a', time());

        $meeting = ActivityLog::findOrFail($meeting_id);

        if ($status == 'start') {
            $data = array(
                'start_time' => $time,
                'start_latitude' => $latitude,
                'start_longitude' => $longitude,
                'start_address' => $address,
                'meeting_status' => 'start'
            );
            $audit_action = array(
                'action' => 'update',
                'model-id' => $meeting_id,
                'data' => $data
            );
            AuditTrail::addAuditEntry("ActivityLog", json_encode($audit_action));


        } else {
            $data = array(
                'end_time' => $time,
                'end_latitude' => $latitude,
                'end_longitude' => $longitude,
                'end_address' => $address,
                'meeting_status' => 'stop'
            );
            $audit_action = array(
                'action' => 'update',
                'model-id' => $meeting_id,
                'data' => $data
            );
            AuditTrail::addAuditEntry("ActivityLog", json_encode($audit_action));


        }
        $meeting->update($data);

        //return json_encode($meeting);
        return $meeting_id;
    }

    public function updateOutcome()
    {
        $meeting_id = Input::get('meeting_id');
        $outcome = Input::get('outcome');
        $meeting_summary = Input::get('meeting_summary');
        syslog(LOG_INFO, 'MobileWebController -- updateOutcome -- meeting_id --' . $meeting_id);
        syslog(LOG_INFO, 'MobileWebController -- updateOutcome -- $outcome --' . $outcome);
        syslog(LOG_INFO, 'MobileWebController -- updateOutcome -- $meeting_summary --' . $meeting_summary);
        $meeting = ActivityLog::findOrFail($meeting_id);
        $data = array(
            'meeting_summary' => $meeting_summary,
            'outcome' => $outcome
        );
        $meeting->update($data);

        //return json_encode($meeting);
        return json_encode(array('status' => 'updated'));
    }
}