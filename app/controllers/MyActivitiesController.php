<?php

/**
 * Created by PhpStorm.
 * User: Anojan
 * Date: 19/10/2017
 * Time: 5:50 PM
 */
class MyActivitiesController extends BaseController
{
    public function index()
    {
        return View::make('my_activities.index')->render();
    }

    public function ajaxGetAddNewCallForm()
    {
        $customer_id = Input::get('customer_id');
        $post_data_to_load = Input::get('post_data_to_load'); // function to call after saving form

        $contacts = Contact::getCustomerContactsList($customer_id);

        return View::make('my_activities._partials.add_new_call', compact('customer_id', 'contacts', 'post_data_to_load'))->render();
    }

    public function ajaxGetEditCallForm()
    {
        $call_id = Input::get('call_id');
        $post_data_to_load = Input::get('post_data_to_load'); // function to call after saving form

        $call = Call::find($call_id);

        if(is_object($call)) {
            $contacts = Contact::getCustomerContactsList($call->customer_id);

            return View::make('my_activities._partials.edit_call_form', compact('call', 'contacts', 'post_data_to_load'))->render();
        } else {
            return "Call not found.";
        }


    }

    public function ajaxLoadCallsList()
    {
        $search_query_calls_filter = Input::get('search_query_calls_filter');

        $organization_id = Session::get('user-organization-id');
        $customer_ids = Customer::where('organization_id',$organization_id)->lists('id');

        //date range code
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $build_query = Call::whereIn('customer_id',$customer_ids);

        $user_id = Session::get('user-id');
        $user_level = Session::get('user-level');

        if($user_level == 'sales') {
            $build_query->where('assigned_to', '=', $user_id);
        }else{
            $build_query->where('assigned_to', '!=', '');
        }


        if($search_query_calls_filter != ''){

            $build_query->where(function($query)  use ($search_query_calls_filter)
            {
                $query->where('agenda','like', '%' . $search_query_calls_filter. '%')
                    ->orWhere('summary','like', '%' . $search_query_calls_filter . '%');
            });
        }

        $build_query->whereBetween('created_datetime' , array($from_date, $to_date));

        $calls = $build_query->orderBy('id', 'desc')->paginate(10);
        return View::make('my_activities._ajax_partials.calls_list', compact('calls'))->render();
    }

    public function ajaxSaveNewCall()
    {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $customer_id = Input::get('customer_id');
        $agenda = Input::get('agenda');
        $call_date = Input::get('call_date');
        $call_with = Input::get('call_with');
        $start_time = Input::get('start_time');
        $end_time = Input::get('end_time');
        $summary = Input::get('summary');
        $create_task = Input::get('create_task');

        $data_calls = array(
            'customer_id' => $customer_id,
            'created_datetime' => $datetime,
            'agenda' => $agenda,
            'summary' => $summary,
            'call_date' => $call_date,
            'assigned_to' => Session::get('user-id'),
            'task' => 0,
            'scheduled_start_time' => $start_time,
            'scheduled_end_time' => $end_time,
            'call_with' => $call_with
        );

        $call = Call::create($data_calls);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $call->id,
            'data' =>  $data_calls
        );
        AuditTrail::addAuditEntry("Call", json_encode($audit_action));

        $data_customer_time_line_items = array(
            'customer_id' => $customer_id,
            'time_line_item_source' => "Call",
            'time_line_item_source_id' => $call->id,
            'datetime' => $datetime
        );

        CustomerTimeLineItem::create($data_customer_time_line_items);

        if($create_task){

            $event = TasksController::setActivityDataForCalendar('Call',$call);

            $task_data = array(
                'customer_id' => $customer_id,
                'contact_id' => 0,
                'title' => $event['title'],
                'from_date_time' => $event['date_start'],
                'to_date_time' => $event['date_end'],
                'assigned_to' => Session::get('user-id'),
                'location' => $event['location'],
                'description' => $event['description'],
                'reminder_types' => [], //array
                'times' => [], //array
                'time_units' => [], //array
                'guest_emails' => [], //array
            );

            Task::createTask($task_data);
        }

        return "Call Updated Successfully";
    }

    public function ajaxUpdateCall()
    {
        $call_id = Input::get('call_id');
        $customer_id = Input::get('customer_id');
        $agenda = Input::get('agenda');
        $call_date = Input::get('call_date');
        $call_with = Input::get('call_with');
        $start_time = Input::get('start_time');
        $end_time = Input::get('end_time');
        $summary = Input::get('summary');

        $call = Call::find($call_id);

        $data_calls = array(
            'customer_id' => $customer_id,
            'agenda' => $agenda,
            'summary' => $summary,
            'call_date' => $call_date,
            'scheduled_start_time' => $start_time,
            'scheduled_end_time' => $end_time,
            'call_with' => $call_with
        );

        $call->update($data_calls);

        $audit_action = array(
            'action' => 'update',
            'model-id' => $call->id,
            'data' => $data_calls
        );
        AuditTrail::addAuditEntry("Call", json_encode($audit_action));

        return "Call Updated Successfully";
    }

    public function ajaxGetAddNewMeetingForm()
    {
        $customer_id = Input::get('customer_id');
        $post_data_to_load = Input::get('post_data_to_load'); // function to call after saving form

        $customer = Customer::find($customer_id);

        $contacts = Contact::getCustomerContactsList($customer_id);

        $customer_address = is_object($customer)? $customer->getAddress(): "";

        return View::make('my_activities._partials.add_new_meeting', compact('customer_id', 'contacts', 'post_data_to_load', 'customer_address'))->render();
    }

    public function ajaxGetEditMeetingForm()
    {
        $meeting_id = Input::get('meeting_id');
        $post_data_to_load = Input::get('post_data_to_load'); // function to call after saving form

        $meeting = Meeting::find($meeting_id);

        if(is_object($meeting)) {
            $contacts = Contact::getCustomerContactsList($meeting->customer_id);

            $customer = Customer::find($meeting->customer_id);

            $customer_address = is_object($customer)? $customer->getAddress(): "";

            $meeting_status_logs = MeetingStatusLog::where('meeting_id',$meeting->id)->get();

            return View::make('my_activities._partials.edit_meeting_form', compact('meeting', 'contacts', 'customer_address', 'post_data_to_load','meeting_status_logs'))->render();
        } else {
            return "Meeting not found.";
        }


    }

    public function ajaxLoadMeetingsList()
    {
        $search_query_meetings_filter = Input::get('search_query_meetings_filter');

        $organization_id = Session::get('user-organization-id');
        $customer_ids = Customer::where('organization_id',$organization_id)->lists('id');

        //date range code
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $build_query = Meeting::whereIn('customer_id',$customer_ids);

        $user_id = Session::get('user-id');
        $user_level = Session::get('user-level');

        syslog(LOG_INFO,"user_id -- $user_id");
        syslog(LOG_INFO,"user-level -- $user_level");

        if($user_level == 'sales') {
            $build_query->where('assigned_to', '=', $user_id);
        }else{
            $build_query->where('assigned_to', '!=', '');
        }

        if($search_query_meetings_filter != ''){

            $build_query->where(function($query)  use ($search_query_meetings_filter)
            {
                $query->where('agenda', 'like', '%' . $search_query_meetings_filter . '%')
                    ->orWhere('meeting_person', 'like' , '%' . $search_query_meetings_filter . '%');
            });
        }

        $build_query->whereBetween('created_datetime' , array($from_date, $to_date));

        $meetings = $build_query->orderBy('id','desc')->paginate(10);

        return View::make('my_activities._ajax_partials.meetings_list', compact('meetings'))->render();
    }

    public function ajaxSaveNewMeeting()
    {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $customer_id = Input::get('customer_id');
        $agenda = Input::get('agenda');
        $meeting_date = Input::get('meeting_date');
        $meeting_person = Input::get('meeting_person');
        $start_time = Input::get('start_time');
        $end_time = Input::get('end_time');
        $venue = Input::get('venue');
        $summary = Input::get('summary');
        $create_task = Input::get('create_task');

        $data_meetings = array(
            'customer_id' => $customer_id,
            'created_datetime' => $datetime,
            'agenda' => $agenda,
            'venue' => $venue,
            'summary' => $summary,
            'meeting_date' => $meeting_date,
            'assigned_to' => Session::get('user-id'),
            'task' => 0,
            'scheduled_start_time' => $start_time,
            'scheduled_end_time' => $end_time,
            'meeting_person' => $meeting_person
        );

        $meeting = Meeting::create($data_meetings);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $meeting->id,
            'data' => $data_meetings
        );
        AuditTrail::addAuditEntry("Meeting", json_encode($audit_action));

        $data_customer_time_line_items = array(
            'customer_id' => $customer_id,
            'time_line_item_source' => "Meeting",
            'time_line_item_source_id' => $meeting->id,
            'datetime' => $datetime
        );

        CustomerTimeLineItem::create($data_customer_time_line_items);

        if($create_task){

            $event = TasksController::setActivityDataForCalendar('Meeting',$meeting);

            $task_data = array(
                'customer_id' => $customer_id,
                'contact_id' => 0,
                'title' => $event['title'],
                'from_date_time' => $event['date_start'],
                'to_date_time' => $event['date_end'],
                'assigned_to' => Session::get('user-id'),
                'location' => $event['location'],
                'description' => $event['description'],
                'reminder_types' => [], //array
                'times' => [], //array
                'time_units' => [], //array
                'guest_emails' => [], //array
            );

            Task::createTask($task_data);
        }


        return "Meeting Updated Successfully";
    }

    public function ajaxUpdateMeeting()
    {
        $meeting_id = Input::get('meeting_id');
        $customer_id = Input::get('customer_id');
        $agenda = Input::get('agenda');
        $meeting_date = Input::get('meeting_date');
        $meeting_person = Input::get('meeting_person');
        $start_time = Input::get('start_time');
        $end_time = Input::get('end_time');
        $venue = Input::get('venue');
        $summary = Input::get('summary');

        $meeting = Meeting::find($meeting_id);

        $data_meetings = array(
            'customer_id' => $customer_id,
            'agenda' => $agenda,
            'venue' => $venue,
            'summary' => $summary,
            'meeting_date' => $meeting_date,
            'scheduled_start_time' => $start_time,
            'scheduled_end_time' => $end_time,
            'meeting_person' => $meeting_person
        );

        $meeting->update($data_meetings);

        $audit_action = array(
            'action' => 'update',
            'model-id' => $meeting->id,
            'data' => $data_meetings
        );
        AuditTrail::addAuditEntry("Meeting", json_encode($audit_action));

        return "Meeting Updated Successfully";
    }

    public function ajaxGetAddNewEmailForm()
    {
        $customer_id = Input::get('customer_id');
        $post_data_to_load = Input::get('post_data_to_load'); // function to call after saving form

        $contacts = Contact::getCustomerContactsList($customer_id);

        return View::make('my_activities._partials.add_new_email', compact('customer_id', 'contacts', 'post_data_to_load'))->render();
    }

    public function ajaxGetEditEmailForm()
    {
        $email_id = Input::get('email_id');
        $post_data_to_load = Input::get('post_data_to_load'); // function to call after saving form

        $email = Email::find($email_id);

        if (is_object($email)) {
            $contacts = Contact::getCustomerContactsList($email->customer_id);

            return View::make('my_activities._partials.edit_email_form', compact('email', 'contacts', 'post_data_to_load'))->render();
        } else {
            return "Email not found.";
        }


    }

    public function ajaxLoadEmailsList()
    {
        $search_query_emails_filter = Input::get('search_query_emails_filter');
        $status_filter = Input::get('status_filter');

        $organization_id = Session::get('user-organization-id');
        $customer_ids = Customer::where('organization_id',$organization_id)->lists('id');

        //date range code
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $build_query = Email::whereIn('customer_id',$customer_ids);

        $user_id = Session::get('user-id');
        $user_level = Session::get('user-level');

        if($user_level == 'sales') {
            $build_query->where('sent_by_id', '=', $user_id);
        }else{
            $build_query->where('sent_by_id', '!=', '');
        }


        if($search_query_emails_filter != ''){

            $build_query->where(function($query)  use ($search_query_emails_filter)
            {
                $query->where('to', 'like', '%' . $search_query_emails_filter . '%')
                    ->orWhere('cc','like', '%' . $search_query_emails_filter . '%')
                    ->orWhere('bcc', 'like', '%' . $search_query_emails_filter . '%')
                    ->orWhere('subject', 'like', '%' . $search_query_emails_filter . '%')
                    ->orWhere('body', 'like', '%' . $search_query_emails_filter . '%');
            });
        }

        if($status_filter != ''){
            $build_query->where('status', $status_filter);
        }

        $build_query->whereBetween('sent_on' , array($from_date, $to_date));

        $emails = $build_query->orderBy('id', 'desc')->paginate(10);

        return View::make('my_activities._ajax_partials.emails_list', compact('emails'))->render();
    }

    public function ajaxSaveNewEmail()
    {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $sender = Employee::find(Session::get('user-id'));

        $customer_id = Input::get('customer_id');
        $to = Input::get('to');
        $cc = Input::get('cc');
        $bcc = Input::get('bcc');
        $subject = Input::get('subject');
        $body = Input::get('body');
        $email_attachment_titles = Input::get('email_attachment_titles');
        $email_attachment_files = Input::file('email_attachment_files');
        $status = (Input::get('submit_button_value') == 'Send') ? 'sent' : 'draft';
        $create_task = Input::get('create_task');

//        syslog(LOG_INFO, '$email_attachment_files -- ' . json_encode($email_attachment_files));
//        syslog(LOG_INFO, '$email_attachment_titles -- ' . json_encode($email_attachment_titles));
//        syslog(LOG_INFO, 'submit_button_value -- ' . Input::get('submit_button_value'));

        $data_emails = array(
            'customer_id' => $customer_id,
            'to' => $to,
            'cc' => $cc,
            'bcc' => $bcc,
            'subject' => $subject,
            'body' => $body,
            'sent_by_id' => $sender->id,
            'sent_on' => $datetime,
            'status' => $status,
            'attachment_urls_json' => '',
            'email_id' => '',

        );

//        syslog(LOG_INFO, '$data_emails -- ' . json_encode($data_emails));

        $email = Email::saveEmail($data_emails);
        $email_id = $email->id;

        if(Input::hasFile('email_attachment_files')) {
            foreach ($email_attachment_files as $key => $email_attachment_file) {
                Email::saveEmailAttachment($email_id, $email_attachment_titles[$key], $email_attachment_file);
            }
        }

        $email_attachments = Email::getAttachmentList($email_id);
        if($status == 'sent'){

            $signature_html = $sender->signature_html;
            $signature_file_url = $sender->signature_file_url;

            $body = $body."<br><br>
              $signature_html <br>
              <img src='$signature_file_url' alt=''>
            ";

            $send_tos = Email::formatEmailsSendingArrayBeforeSending($to);
            $send_ccs = Email::formatEmailsSendingArrayBeforeSending($cc);
            $send_bcc = Email::formatEmailsSendingArrayBeforeSending($bcc);

            EmailsController::sendWeb360CustomerEmails($subject, $body, $send_tos, $sender, $send_ccs, $send_bcc, '', '', $email_attachments);

        }

        if($create_task){

            $event = TasksController::setActivityDataForCalendar('Email',$email);

            $task_data = array(
                'customer_id' => $customer_id,
                'contact_id' => 0,
                'title' => $event['title'],
                'from_date_time' => $event['date_start'],
                'to_date_time' => $event['date_end'],
                'assigned_to' => Session::get('user-id'),
                'location' => $event['location'],
                'description' => $event['description'],
                'reminder_types' => [], //array
                'times' => [], //array
                'time_units' => [], //array
                'guest_emails' => [], //array
            );

            Task::createTask($task_data);
        }

        return "Email Recorded Successfully";
    }

    public function ajaxUpdateEmail()
    {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $sender = Employee::find(Session::get('user-id'));

        $email_id = Input::get('email_id');
        $customer_id = Input::get('customer_id');

        $to = Input::get('to');
        $cc = Input::get('cc');
        $bcc = Input::get('bcc');
        $subject = Input::get('subject');
        $body = Input::get('body');
        $email_attachment_titles = Input::get('email_attachment_titles');
        $email_attachment_files = Input::file('email_attachment_files');
        $status = (Input::get('submit_button_value') == 'Send') ? 'sent' : 'draft';

        syslog(LOG_INFO, 'submit_button_value -- ' . Input::get('submit_button_value'));

        if(Input::hasFile('email_attachment_files')){
            foreach($email_attachment_files as $key => $email_attachment_file){
                Email::saveEmailAttachment($email_id,$email_attachment_titles[$key],$email_attachment_file);
            }
        }

        if($status == 'sent'){

            $signature_html = $sender->signature_html;
            $signature_file_url = $sender->signature_file_url;

            $body = $body."<br><br>
              $signature_html <br>
              <img src='$signature_file_url' alt=''>
            ";

            $email_attachments = Email::getAttachmentList($email_id);

            $send_tos = Email::formatEmailsSendingArrayBeforeSending($to);
            $send_ccs = Email::formatEmailsSendingArrayBeforeSending($cc);
            $send_bcc = Email::formatEmailsSendingArrayBeforeSending($bcc);

            EmailsController::sendWeb360CustomerEmails($subject, $body, $send_tos, $sender, $send_ccs, $send_bcc,'','',$email_attachments);

        }

        $data_emails = array(
            'customer_id' => $customer_id,
            'to' => $to,
            'cc' => $cc,
            'bcc' => $bcc,
            'subject' => $subject,
            'body' => $body,
            'sent_by_id' => $sender->id,
            'sent_on' => $datetime,
            'status' => $status,
            'attachment_urls_json' => '',
            'email_id' => $email_id,
        );

        syslog(LOG_INFO, '$data_emails -- ' . json_encode($data_emails));

        Email::saveEmail($data_emails);

        return "Email Recorded Successfully";
    }

    public function addActivityToMyCalender(){

        $calender_type = Input::get('calender_type');
        $calender_data = Input::get('calender_data');

        $calender_data = json_decode($calender_data);
        syslog(LOG_INFO,'$calender_data -- '.$calender_data);
        $calender_data = json_decode($calender_data,true);

        if($calender_type == 'apple_calender' || $calender_type == 'outlook'){
            header('Content-type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename=activity.ics');

            $ics = new ICS(array(
                'location' => $calender_data['location'],
                'description' => $calender_data['description'],
                'dtstart' => $calender_data['date_start'],
                'dtend' => $calender_data['date_end'],
                'title' =>  $calender_data['title']
            ));

            echo $ics->to_string();

        }elseif($calender_type == 'google_online'){
            return View::make('my_activities.calenders.google',compact('calender_data'))->render();
        }

    }

    public static function generateJSONForCalendar($activity_type,$activity_object){

        $event = array();

        if($activity_type == 'Meeting'){

            $event['type'] = $activity_type;
            $event['title'] = "Meeting At {$activity_object->venue} ; With {$activity_object->meeting_person} ";
            $event['description'] = "Agenda = {$activity_object->agenda} ;";
            $event['date_start'] = $activity_object->meeting_date.' '.$activity_object->scheduled_start_time;
            $event['date_end'] = $activity_object->meeting_date.' '.$activity_object->scheduled_end_time;
            $event['location'] = $activity_object->venue;

        }elseif($activity_type == 'Email'){

            $event['type'] = $activity_type;
            $event['title'] = "Email To {$activity_object->to} ; [Subject {$activity_object->subject}]";
            $event['description'] = "To {$activity_object->to}; Subject {$activity_object->subject} ;";
            $event['date_start'] = $activity_object->sent_on;
            $event['date_end'] = $activity_object->sent_on;
            $event['location'] = '';

        }elseif($activity_type == 'Call'){
            
            $event['type'] = $activity_type;
            $event['title'] = "Call With {$activity_object->call_with} ;";
            $event['description'] = "Agenda {$activity_object->agenda} ;";
            $event['date_start'] = $activity_object->call_date.' '.$activity_object->scheduled_start_time;
            $event['date_end'] = $activity_object->call_date.' '.$activity_object->scheduled_end_time;
            $event['location'] = '';
        }

        $event['date_start'] =  date("Y-m-d H:i", strtotime($event['date_start']));
        $event['date_end'] =  date("Y-m-d H:i", strtotime($event['date_end']));

        return json_encode($event);

    }

    public function removeEmailAttachment(){

        $email_attachment_id = Input::get('email_attachment_id');
        EmailAttachment::find($email_attachment_id)->delete();

    }
}