<?php

use google\appengine\api\taskqueue\PushTask;
use google\appengine\api\taskqueue\PushQueue;

class TaskReminder extends \Eloquent
{
    protected $table = "task_reminders";

    protected $fillable = [
        'id',
        'task_id',
        'reminder_type',
        'time',
        'time_unit',
    ];

    public static $reminder_types = array(
//        'sms' => 'SMS',
        'email' => 'Email',
    );

    public static $time_units = array(
        'minutes' => 'Minutes',
        'hours' => 'Hours',
        'days' => 'Days',
        'weeks' => 'Weeks',
    );

    public function task()
    {
        return $this->belongsTo('Task', 'task_id');
    }

    public static function addTaskToReminderQueue($task_reminder_id, $task_start_date_time, $reminder_time, $reminder_time_unit)
    {
        try {
            $current_date_time = date("Y-m-d H:i");

            switch($reminder_time_unit) {
                case "minutes":
                    $reminder_time_in_seconds = $reminder_time * 60;
                    break;
                case "hours":
                    $reminder_time_in_seconds = $reminder_time * 60 * 60;
                    break;
                case "days":
                    $reminder_time_in_seconds = $reminder_time * 60 * 60 * 24;
                    break;
                case "weeks":
                    $reminder_time_in_seconds = $reminder_time * 60 * 60 * 24 * 7;
                    break;
                default:
                    $reminder_time_in_seconds = 0;
            }

            $reminder_date_time = strtotime($task_start_date_time) - $reminder_time_in_seconds;

            $delay_seconds = $reminder_date_time - strtotime($current_date_time);

            syslog(LOG_INFO, '$reminder_time_in_seconds -- ' . $reminder_time_in_seconds);
            syslog(LOG_INFO, '$reminder_date_time -- ' . $reminder_date_time);
            syslog(LOG_INFO, '$delay_seconds -- ' . $delay_seconds);

            $push_task = new PushTask('/tasks/send-task-reminder-from-task-queue',
                ['task_reminder_id' => $task_reminder_id], ['delay_seconds' => $delay_seconds]);
            $push_task->add('queue-send-task-reminder');
        } catch (Exception $e) {
            syslog(LOG_ERR, 'error caused -- '.$e->getMessage());
            syslog(LOG_ERR, 'error caused -- '.$e->getTraceAsString());
        }

    }
}