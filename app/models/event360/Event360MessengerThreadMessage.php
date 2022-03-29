
<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class Event360MessengerThreadMessage extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_messenger_thread_messages';

    // Don't forget to fill this array
    protected $fillable = [
        'event360_messenger_thread_id',
        'message',
        'sent_by',
        'sent_by_employee_id',
        'timestamp',
        'last_message_sent_by'
    ];

    public function event360MessengerThread()
    {
        return $this->belongsTo('Event360MessengerThread', 'event360_messenger_thread_id');
    }

    public function employee()
    {
        return $this->belongsTo('Employee', 'sent_by_employee_id');
    }

    public static function saveMessage($event360_messenger_thread_id, $message, $sent_by, $sent_by_employee_id = null)
    {

        date_default_timezone_set("Asia/Singapore");

        $event360_messenger_thread_message = Event360MessengerThreadMessage::create(
            array(
                'event360_messenger_thread_id' => $event360_messenger_thread_id,
                'message' => $message,
                'sent_by' => $sent_by,
                'sent_by_employee_id' => $sent_by_employee_id,
                'timestamp' => date('Y-m-d H:i:s'),
                'last_message_sent_by' => "Vendor",

            )
        );

        $event360_messenger_thread = $event360_messenger_thread_message->event360MessengerThread;

        $event360_event_planner_profile = $event360_messenger_thread->event360EventPlannerProfile;
        $vendor_name = $event360_messenger_thread->event360VendorProfile->organization->organization;

        $subject = 'Event360 | You Have A Message | Subject: '. $event360_messenger_thread->subject;

        $mailBody = '';

        $email_sender = new EmailsController();

        $mailBody .= View::make('webtics_product.event360.email_templates.event_planner_email_alert_new_message_received', compact('event360_event_planner_profile', 'vendor_name'))->render();

        $email_sender->sendEmail($subject, $mailBody, $event360_event_planner_profile->email, null);

        return $event360_messenger_thread_message;
    }
}