<?php

class TasksController extends BaseController
{

    public function index()
    {
        return View::make('tasks.index');
    }

    public function ajaxLoadTaskList(){

        $search_task_lists = Input::get('search_task_lists');
        $organization_id = Session::get('user-organization-id');

        $filter_date_range = Input::get('dashboard_filter_date_range');
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $completed_tasks = Input::get('completed_tasks');
        $filter_by_date_type = Input::get('filter_by_date_type');
        $sort_by = Input::get('sort_by');
        $sort_order = Input::get('sort_order');


        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $tasks = Task::where('organization_id',$organization_id);

        if($completed_tasks){
            $tasks = $tasks->where('status', 0);
        }else{
            $tasks = $tasks->where('status', 1);
        }

        if($search_task_lists != ''){
            $tasks = $tasks->where('title', 'LIKE', '%' . $search_task_lists . '%');
        }

        if($filter_by_date_type == 'Creation'){
            if($from_date != '' && $to_date != ''){
                $tasks = $tasks->where('created_at' ,'>=',$from_date)->where('created_at' ,'<=',$to_date) ;
            }
        }else if($filter_by_date_type == 'Due Date'){
            if($from_date != '' && $to_date != ''){
                $tasks = $tasks->where('to_date_time' ,'>=',$from_date)->where('to_date_time' ,'<=',$to_date) ;
            }
        }


        if($sort_by != ''){
            if($sort_by == 'title'){
                $tasks = $tasks->orderBy('title',$sort_order);
            }elseif($sort_by == 'location'){
                $tasks = $tasks->orderBy('location',$sort_order);
            }elseif($sort_by == 'date'){
                $tasks = $tasks->orderBy('from_date_time',$sort_order);
            }

        }

        $tasks = $tasks->paginate(10);

        return View::make('tasks._ajax_partials.tasks_list', compact('tasks'))->render();

    }

    public function ajaxAddTask(){

        $title = Input::get('title');
        $from_date_time = Input::get('from_date_time');
        $to_date_time = Input::get('to_date_time');
        $assigned_to = Input::get('assigned_to');
        $location = Input::get('location');
        $description = Input::get('description');
        $customer_id = Input::get('customer_id');
        $contact_id = Input::get('contact_id');

        $reminder_types = Input::get('reminder_types');
        $times = Input::get('times');
        $time_units = Input::get('time_units');

        $guest_emails = Input::get('guest_emails');
        $guest_emails = explode(',', $guest_emails);

        syslog(LOG_INFO, 'customer_Id -- ' .$customer_id);
        syslog(LOG_INFO, 'contact_id -- ' .$contact_id);


        $task_data = array(
            'title' => $title,
            'from_date_time' => $from_date_time,
            'to_date_time' => $to_date_time,
            'assigned_to' => $assigned_to,
            'location' => $location,
            'description' => $description,
            'customer_id' => $customer_id,
            'contact_id' => $contact_id,
            'reminder_types' => $reminder_types, //array
            'times' => $times, //array
            'time_units' => $time_units, //array
            'guest_emails' => $guest_emails, //array
        );

        Task::createTask($task_data);

        return "New Task Added Successfully.";

    }

    public function ajaxUpdateTask(){

        $task_id = Input::get('task_id');
        $title = Input::get('title');
        $from_date_time = Input::get('from_date_time');
        $to_date_time = Input::get('to_date_time');
        $assigned_to = Input::get('assigned_to');
        $location = Input::get('location');
        $description = Input::get('description');
        $customer_id = Input::get('customer_id');
        $contact_id = Input::get('contact_id');

        $reminder_types = Input::get('reminder_types');
        $times = Input::get('times');
        $time_units = Input::get('time_units');

        $guest_emails = Input::get('guest_emails');

        $task = Task::findOrFail($task_id);

        $task->update(array(
            'title' => $title,
            'assigned_to' => $assigned_to,
            'from_date_time' => $from_date_time,
            'to_date_time'=> $to_date_time,
            'location' => $location,
            'description' => $description,
            'customer_id' => $customer_id,
            'contact_id' => $contact_id,
        ));

        //remove all reminders added before updating with new ones
        TaskReminder::where('task_id',$task_id)->delete();

        if(!empty($reminder_types)){
            foreach($reminder_types as $key => $value){

                $task_reminder = TaskReminder::create(array(
                    'task_id' => $task_id,
                    'reminder_type' => $value,
                    'time' => $times[$key],
                    'time_unit' => $time_units[$key],
                ));

                TaskReminder::addTaskToReminderQueue($task_reminder->id, $task->from_date_time, $task_reminder->time, $task_reminder->time_unit);

            }
        }

        //remove all guest emails added before updating with new ones
        TaskGuest::where('task_id',$task_id)->delete();

        if(!empty($guest_emails)) {
            foreach ($guest_emails as $key => $value) {

                TaskGuest::create(array(
                    'task_id' => $task_id,
                    'guest_email' => $value,
                ));

            }
        }
        return "Task Updated Successfully.";
    }


    public function ajaxUpdateMarkAsDoneStatus(){

        $mark_done = Input::get('mark_done');

        foreach ($mark_done as $key => $value){

            $status = 1;
            $task = Task::findOrFail($value);
            if($task->status){ //if task status is 1 then change to 0
                $status = 0;
            }

            $task->update(array(
                'status' => $status
            ));

        }

        return "Task done status updated successfully.";
    }

    public function ajaxGetAllTasksForCalender(){

        $organization_id = Session::get('user-organization-id');
        $start = Input::get('start');
        $end = Input::get('end');

        $tasks = DB::table('tasks')
            ->select(DB::raw('title as title ,from_date_time as start,to_date_time as end,true as allDay, id'))
            ->where('status',1)
            ->where('organization_id',$organization_id)
            ->where('from_date_time','>=',$start)
            ->where('to_date_time','<=',$end)
            ->get();

        return $tasks;

    }

    public function ajaxUpdateTaskDateTime(){

        $task_id = Input::get('task_id');
        $event = Input::get('event');
        $start = Input::get('start');
        $end = Input::get('end');

        $task = Task::findOrFail($task_id);

        $update_data = array();

        if($event == 'drop'){

            if($end == ''){
                $end = date("Y-m-d",strtotime($start)).' '.date("h:i:s",strtotime($task->to_date_time));
            }

            $update_data['from_date_time'] = $start;
            $update_data['to_date_time'] = $end;

        } elseif($event == 'resize'){

            $update_data['to_date_time'] = $end;

        }

        $task->update($update_data);

        return "Task Updated Successfully.";
    }

    public function ajaxLoadUpdateTaskForm(){

        $task_id = Input::get('task_id');
        $task = Task::findOrFail($task_id);
        return View::make('tasks._partials.edit_task_form', compact('task'))->render();
    }

    public static function setActivityDataForCalendar($event_type,$event_object){

        $event = array();

        if($event_type == ''){
            return $event;
        }

        if($event_type == 'Meeting'){

            $date = date('jS M Y', strtotime($event_object->meeting_date));

            $event['id'] = $event_object->id;
            $event['title'] = "Meeting | On $date | For: {$event_object->agenda} ";
            $event['description'] = "Agenda = {$event_object->agenda} ";
            $event['date_start'] = $event_object->meeting_date.' '.$event_object->scheduled_start_time;
            $event['date_end'] = $event_object->meeting_date.' '.$event_object->scheduled_end_time;
            $event['location'] = $event_object->venue;

        }elseif($event_type == 'Email'){

            $date = date('jS M Y', strtotime($event_object->sent_on));

            $event['id'] = $event_object->id;
            $event['title'] = "Email | On $date | For: {$event_object->subject}";
            $event['description'] = "To {$event_object->to} Subject {$event_object->subject} ";
            $event['date_start'] = $event_object->sent_on;
            $event['date_end'] = $event_object->sent_on;
            $event['location'] = '';

        }elseif($event_type == 'Call'){

            $date = date('jS M Y', strtotime($event_object->call_date));


            $event['id'] = $event_object->id;
            $event['title'] = "Call | On $date | For: {$event_object->agenda} ";
            $event['description'] = "Agenda {$event_object->agenda} ";
            $event['date_start'] = $event_object->call_date.' '.$event_object->scheduled_start_time;
            $event['date_end'] = $event_object->call_date.' '.$event_object->scheduled_end_time;
            $event['location'] = '';
        }

        $event['date_start'] =  date("Y-m-d H:i", strtotime($event['date_start']));
        $event['date_end'] =  date("Y-m-d H:i", strtotime($event['date_end']));

        return $event;

    }

    public function sendTaskReminderFromTaskQueue()
    {
       try {
           $task_reminder_id = Input::get('task_reminder_id');

           syslog(LOG_INFO, '$task_reminder_id -- ' . $task_reminder_id);

           // get task details and send reminder email
           $task_reminder = TaskReminder::find($task_reminder_id);

           if(is_object($task_reminder)) {
               // get task information
               $task = $task_reminder->task;
               // send task reminder email
               $subject = "WEB360: Task Reminder: ". $task->title;

               syslog(LOG_INFO, '$subject -- ' . $subject);

               $mailBody = '';

               $mailBody .= View::make('tasks._partials.task_reminder_email', compact('task'))->render();

               EmailsController::sendGridEmailSender($subject, $mailBody, $task->assignedTo->email);

               // send reminder to task guests
               $task_guests = $task->taskGuests;
               foreach($task_guests as $task_guest) {
                   EmailsController::sendGridEmailSender($subject, $mailBody, $task_guest->guest_email);
               }
           }
       } catch (Exception $e) {
           syslog(LOG_ERR, 'error caused -- '.$e->getMessage());
           syslog(LOG_ERR, 'error caused -- '.$e->getTraceAsString());
       }
    }
}

