<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/9/2015
 * Time: 2:10 PM
 */
class MeetingAttendee extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    // Don't forget to fill this array
    protected $fillable = [
        'activity_log_id',
        'employee_id',
        'status'
    ];

    public function activity(){
        return $this->belongsTo('ActivityLog', 'activity_log_id');
    }

    public function employee(){
        return $this->belongsTo('Employee', 'employee_id');
    }

    public static function checkIfAlreadyInvited ($activity_log_id, $employee_id) {

        $no_of_meeting_attendees = MeetingAttendee::where('activity_log_id', $activity_log_id)->where('employee_id', $employee_id)->whereIn('status', array('Accepted', 'Pending'))->count();

        if($no_of_meeting_attendees > 0) {
            return true;
        } else {
            return false;
        }

    }
}