<?php

class MeetingStatusLog extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = "meeting_status_logs";

    // Don't forget to fill this array
    protected $fillable = [
        'meeting_id',
        'status_updated_on',
        'status',
        'latitude',
        'longitude',
        'address',
    ];

    // relationships

    public function meeting()
    {
        return $this->belongsTo('Meeting', 'meeting_id');
    }

    public static function getMeetingDetails($meeting_id){

        $first_meeting_start = MeetingStatusLog::where('meeting_id',$meeting_id)->first();
        $end_meeting_stop = MeetingStatusLog::where('meeting_id',$meeting_id)->orderBy('id','desc')->first();

        if(is_object($first_meeting_start) && is_object($end_meeting_stop)){
            $meeting_details = array(
                'start_time' => date('h:i:s a ', strtotime($first_meeting_start->status_updated_on)),
                'start_address' => $first_meeting_start->address,
                'end_time' => date('h:i:s a ', strtotime($end_meeting_stop->status_updated_on)),
                'end_address' => $end_meeting_stop->address,
            );
        }else{
            $meeting_details = null;
        }

        return $meeting_details;

    }
}