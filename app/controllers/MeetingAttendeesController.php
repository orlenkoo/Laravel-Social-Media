<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 4/9/2015
 * Time: 10:39 AM
 */
class MeetingAttendeesController extends \BaseController
{
    public function updateStatus()
    {

        $meeting_attendee_id = Input::get('meeting_attendee_id');
        $status = Input::get('status');
        $meeting_inviter_employee_id = Input::get('meeting_inviter_employee_id');



        $this->updateMeetingAttendeeStatus($meeting_attendee_id, $status, $meeting_inviter_employee_id);


    }



    // refactored method so can update status from anywhere.
    public function updateMeetingAttendeeStatus($meeting_attendee_id, $status, $meeting_inviter_employee_id)
    {
        // update status on meeting attendees table

        $meeting_attendee = MeetingAttendee::findOrFail($meeting_attendee_id);

        // check if meeting invitor if so do nothing

        if ($meeting_inviter_employee_id == Session::get('user-id')) {
            return print 'Your the creator of this meeting. This is just a mail copy to the actual invitee for your reference.';
        }


        if ($status == 'Accepted') {
            // check if meeting already exists
            if ($this->checkIfAlreadyAccepted($meeting_attendee)) {
                $message = 'Already Accepted. Please close this window.';

            } else {
                // retrieve main activity
                $main_activity = $meeting_attendee->activity;
                // create new duplicate activities under each of attendee
                $new_activity_data = array(
                    'parent_activity_logs_id' => $main_activity->id,
                    'customer_id' => $main_activity->customer_id,
                    'date' => $main_activity->date,
                    'activity_type' => $main_activity->activity_type,
                    'activity_purpose' => $main_activity->activity_purpose,
                    'agenda' => $main_activity->agenda,
                    'venue' => $main_activity->venue,
                    'date_of_activity' => $main_activity->date_of_activity,
                    'assigned_to' => $meeting_attendee->employee_id,
                    'task' => $main_activity->task,
                    'scheduled_start_time' => $main_activity->scheduled_start_time,
                    'scheduled_end_time' => $main_activity->scheduled_end_time,
                    'meeting_person' => $main_activity->meeting_person
                );

                $new_activity = ActivityLog::create($new_activity_data);

                if ($new_activity->task) {


                    $eventHandler = new EventHandler($new_activity);
                    $event = $eventHandler->addEventToCalendar();

                    $dataEvent = array('google_calendar_event_object_json' => json_encode($event));
                    $new_activity->update($dataEvent);
                }

                $message = 'Meeting Invite Accepted. Event added to your Google Calendar and Webtics.';
            }
            $updated_status = 'Accepted';
        } else if ($status == 'Declined') {
            if ($this->checkIfAlreadyAccepted($meeting_attendee)) {
                //delete child activity for this user
                $this->deleteExistingChildActivity($meeting_attendee->activity);

                $message = 'Declined. Activity Removed from Google Calendar and Webtics. Inviter has been notified.';
            } else {
                $message = 'Declined. Inviter has been notified.';
            }
            $updated_status = 'Declined';

        } else if ($status == 'Accept Edited') {
            $child_activity = ActivityLog::getChildActivityForTheEmployee($meeting_attendee->activity);
            ActivityLog::updateGoogleCalendarEventOnActivityEdit($child_activity);
            $updated_status = 'Accepted';
            $message = 'Accepted. Activity updated on Google Calendar and Webtics. Inviter has been notified.';

        } else if ($status == 'Decline Edited') {
            $this->deleteExistingChildActivity($meeting_attendee->activity);
            $updated_status = 'Declined';
            $message = 'Declined. Activity Removed from Google Calendar and Webtics. Inviter has been notified.';
        } else if ($status == 'Accept Deleted') {
            if ($this->checkIfAlreadyAccepted($meeting_attendee)) {
                $this->deleteExistingChildActivity($meeting_attendee->activity);

                $message = 'Event Deleted from your calendar and Activity removed from Webtics.';
            } else {
                $message = 'Event already deleted.';
            }

            $updated_status = $status;

        }


        $data_meeting_attendee = array(
            'status' => $updated_status
        );

        $meeting_attendee->update($data_meeting_attendee);

        return print $message;
    }

    public function checkIfAlreadyAccepted($meeting_attendee)
    {
        // first check what is status on

        $child_activity = ActivityLog::where('parent_activity_logs_id', $meeting_attendee->activity_log_id)->where('assigned_to', $meeting_attendee->employee_id)->get();

        if (count($child_activity) > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function deleteExistingChildActivity($activity_log)
    {
        // first get the child activity
        $child_activity = ActivityLog::getChildActivityForTheEmployee($activity_log);


        if($child_activity->google_calendar_event_object_json != null) {
            $eventHandler = new EventHandler($child_activity);

            $eventHandler->deleteEvent();
        }

        ActivityLog::destroy($child_activity->id);

    }

    public function ajaxLoadMeetingAttendeeDetails($id)
    {

        $meeting_attendees = MeetingAttendee::where('activity_log_id', $id)->get();


        return View::make('meeting_attendees._ajax_partials.attendee_details', compact('meeting_attendees'))->render();
    }

    public static function updateChildActivities($activity_log, $data)
    {


        // get all sub activities
        $sub_activities = $activity_log->subActivities;

        // IMPORTANT before updating remove assigned to from data set
        syslog(LOG_INFO, 'updateChildActivities -- array data count before - '. count($data));
        unset($data['assigned_to']);
        syslog(LOG_INFO, 'updateChildActivities -- array data count after - '. count($data));

        foreach ($sub_activities as $sub_activity) {

            $sub_activity->update($data);

        }

        //return dd($meeting_invitees);
        $emailsController = new EmailsController();
        $emailsController->sendActivityUpdateInvites($activity_log->meetingAttendees);

        return true;

    }
}