<?php

class Task extends \Eloquent
{
    protected $table = "tasks";

    protected $fillable = [
        'id',
        'organization_id',
        'title',
        'customer_id',
        'contact_id',
        'created_by',
        'assigned_to',
        'from_date_time',
        'to_date_time',
        'location',
        'description',
        'status',
        'created_at'
    ];

    public function taskReminders()
    {
        return $this->hasMany('TaskReminder');
    }

    public function taskGuests()
    {
        return $this->hasMany('TaskGuest');
    }

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function contact()
    {
        return $this->belongsTo('Contact', 'contact_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('Employee', 'created_by');
    }
    public function assignedTo()
    {
        return $this->belongsTo('Employee', 'assigned_to');
    }

    public static function createTask($task_data){

        $title = $task_data['title'];
        $from_date_time = $task_data['from_date_time'];
        $to_date_time = $task_data['to_date_time'];
        $assigned_to = $task_data['assigned_to'];
        $location = $task_data['location'];
        $description = $task_data['description'];
        $customer_id = $task_data['customer_id'];
        $contact_id = $task_data['contact_id'];
        $reminder_types = $task_data['reminder_types'];
        $times = $task_data['times'];
        $time_units = $task_data['time_units'];
        $guest_emails = $task_data['guest_emails'];

        $task = Task::create(array(
            'organization_id' => Session::get('user-organization-id'),
            'title' => $title ,
            'created_by' => Session::get('user-id'),
            'assigned_to' => $assigned_to,
            'from_date_time' => $from_date_time,
            'to_date_time'=> $to_date_time,
            'location' => $location,
            'description' => $description,
            'customer_id' => $customer_id,
            'contact_id' => $contact_id,
            'status' => 1
        ));

        if(count($reminder_types) > 0){
            foreach($reminder_types as $key => $value){
                $task_reminder = TaskReminder::create(array(
                    'task_id' => $task->id,
                    'reminder_type' => $value,
                    'time' => $times[$key],
                    'time_unit' => $time_units[$key],
                ));
                TaskReminder::addTaskToReminderQueue($task_reminder->id, $task->from_date_time, $task_reminder->time, $task_reminder->time_unit);
            }
        }

        if(count($guest_emails) > 0) {
            foreach ($guest_emails as $key => $value) {
                TaskGuest::create(array(
                    'task_id' => $task->id,
                    'guest_email' => $value,
                ));
            }
        }

        return $task->id;
    }
}