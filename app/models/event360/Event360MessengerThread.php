<?php

/**
 * Created by PhpStorm.
 * User: Kasun
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360MessengerThread extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_messenger_threads';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_vendor_profile_id',
        'event360_event_planner_profile_id',
        'subject',
        'timestamp',
        'created_at',
        'updated_at',
    ];

    public function event360VendorProfile()
    {
        return $this->belongsTo('Event360VendorProfile', 'event360_vendor_profile_id');
    }

    public function event360EventPlannerProfile()
    {
        return $this->belongsTo('Event360EventPlannerProfile', 'event360_event_planner_profile_id');
    }

    public function event360MessengerThreadMessages()
    {
        return  $this->hasMany('Event360MessengerThreadMessage');
    }

    public static function checkEvent360MessengerLastMessageSentByVendor($event360_messenger_thread_id){

        $event360_messenger_thread = Event360MessengerThreadMessage::where('event360_messenger_thread_id', $event360_messenger_thread_id)
            ->latest()
            ->first();
        $event360_messenger_thread_sent_by = $event360_messenger_thread->sent_by;
        if($event360_messenger_thread_sent_by == 'Vendor'){
            return true;
        }else{
            return false;
        }

    }
}