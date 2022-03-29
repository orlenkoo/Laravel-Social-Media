<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/8/2015
 * Time: 10:15 AM
 */
class EventHandler
{
    private $activity = null;
    private $service = null;
    private $start_time = null;
    private $end_time = null;

    public function __construct($activity)
    {
        $this->activity = $activity;

        // check and set start end times
        if($this->activity->scheduled_start_time != '' && $this->activity->scheduled_start_time != null) {
            $this->start_time = $this->activity->scheduled_start_time;
        } else {
            date_default_timezone_set("Asia/Singapore");
            $this->start_time = date('h:ia');
        }

        if($this->activity->scheduled_end_time != '' && $this->activity->scheduled_end_time != null) {
            $this->end_time = $this->activity->scheduled_end_time;

            if(strtotime($this->start_time) > strtotime($this->end_time)) {
                syslog(LOG_INFO, 'start time greater than end time');
                $this->end_time = $this->start_time;
                $data = array('scheduled_end_time' => $this->end_time);

                $this->activity->update($data);
            }

        } else {

            $this->end_time = $this->start_time;
            $data = array('scheduled_end_time' => $this->end_time);

            $this->activity->update($data);
        }



        syslog(LOG_INFO, 'activity scheduled start time - '.$this->start_time);
        syslog(LOG_INFO, 'activity scheduled end time - '.$this->end_time);

        if(getenv('APP_ENGINE_ENVIRONMENT') == 'PRODUCTION') {
            $bucket_name = getenv('PRODUCTION_STORAGE_BUCKET_NAME');
        } else {
            $bucket_name = getenv('STAGING_STORAGE_BUCKET_NAME');
        }
        $root_path = 'gs://'. $bucket_name .'/meeting-tracker-2a6e12e9d672.p12';

        $client_email = '665775573583-ehrdd0la72ruouhtpl9g6jb5vtl7jpe4@developer.gserviceaccount.com';
        $private_key = file_get_contents($root_path);
        $scopes = array('https://www.googleapis.com/auth/calendar');

        $user_to_impersonate = Session::get('user-email');
        $credentials = new Google_Auth_AssertionCredentials(
            $client_email,
            $scopes,
            $private_key,
            'notasecret',                                 // Default P12 password
            'http://oauth.net/grant_type/jwt/1.0/bearer', // Default grant type
            $user_to_impersonate
        );

        $client = new Google_Client();
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        $this->service = new Google_Service_Calendar($client);
    }

    /*
     * Add event to the google calendar of the logged in user
     */
    public function addEventToCalendar()
    {

        $cloned = '';

        if($this->activity->parent_activity_logs_id != null) {
            $cloned .= ' [Invitation] ';
        }




        $event = new Google_Service_Calendar_Event(array(
            'summary' => 'Webtics - '.$cloned .''. $this->activity->customer->account_name . ' - '. $this->activity->activity_type . ' - ' . $this->activity->activity_purpose . ' - ' . $this->activity->agenda,
            'location' => '' . $this->activity->venue,
            'description' => '' . $this->activity->details,
            'start' => array(
                'dateTime' => ''. date("Y-m-d", strtotime($this->activity->date_of_activity)) . 'T' . date('G:i:s', strtotime($this->start_time)),
                'timeZone' => 'Asia/Singapore',
            ),
            'end' => array(
                'dateTime' => ''. date("Y-m-d", strtotime($this->activity->date_of_activity)) . 'T' . date('G:i:s', strtotime($this->end_time)),
                'timeZone' => 'Asia/Singapore',
            ),


            'reminders' => array(
                'useDefault' => FALSE,
                'overrides' => array(
                    array('method' => 'email', 'minutes' => 120),
                    array('method' => 'email', 'minutes' => 60),
                    array('method' => 'popup', 'minutes' => 30),
                    array('method' => 'popup', 'minutes' => 10),
                ),
            ),
        ));

        $calendarId = 'primary';
        $event = $this->service->events->insert($calendarId, $event);

        return $event;
    }

    /*
     * Edit created event on the google calendar
     */
    public function editEvent()
    {
        syslog(LOG_INFO, '0020');
        $eventJson = json_decode($this->activity->google_calendar_event_object_json, true);

        $event = $this->service->events->get('primary', $eventJson['id']);
        //return dd($event);
        syslog(LOG_INFO, '0021');



        if($event->status != 'cancelled') {
            syslog(LOG_INFO, '0023 - task' . $this->activity->task);
            if ($this->activity->task == 1) {
                syslog(LOG_INFO, '0022');


                $event->setSummary('Webtics - '. $this->activity->customer->account_name . ' - ' . $this->activity->activity_type . ' - ' . $this->activity->activity_purpose . ' - ' . $this->activity->agenda);
                $event->setDescription('' . $this->activity->details);
                $event->setLocation('' . $this->activity->venue);

                syslog(LOG_INFO, '0023');
                $start = new Google_Service_Calendar_EventDateTime();
                $start->setDateTime('' . date("Y-m-d", strtotime($this->activity->date_of_activity)) . 'T' . date('G:i:s', strtotime($this->start_time)));
                $start->setTimeZone("Asia/Singapore");
                $event->setStart($start);

                $end = new Google_Service_Calendar_EventDateTime();
                $end->setDateTime('' . date("Y-m-d", strtotime($this->activity->date_of_activity)) . 'T' . date('G:i:s', strtotime($this->end_time)));
                $end->setTimeZone("Asia/Singapore");
                $event->setEnd($end);

                $updatedEvent = $this->service->events->update('primary', $event->getId(), $event);
            } else {
                syslog(LOG_INFO, '0024');
                $this->service->events->delete('primary', $event->getId());

                $updatedEvent = null;
            }
        } else {
            $updatedEvent = $this->addEventToCalendar();

            if ($this->activity->task == 1) {
                $updatedEvent->setSummary('Webtics - '. $this->activity->customer->account_name . ' - ' . $this->activity->activity_type . ' - ' . $this->activity->activity_purpose . ' - ' . $this->activity->agenda);
                $updatedEvent->setDescription('' . $this->activity->details);
                $updatedEvent->setLocation('' . $this->activity->venue);

                syslog(LOG_INFO, '0023');
                $start = new Google_Service_Calendar_EventDateTime();
                $start->setDateTime('' . date("Y-m-d", strtotime($this->activity->date_of_activity)) . 'T' . date('G:i:s', strtotime($this->start_time)));
                $start->setTimeZone("Asia/Singapore");
                $updatedEvent->setStart($start);

                $end = new Google_Service_Calendar_EventDateTime();
                $end->setDateTime('' . date("Y-m-d", strtotime($this->activity->date_of_activity)) . 'T' . date('G:i:s', strtotime($this->end_time)));
                $end->setTimeZone("Asia/Singapore");
                $updatedEvent->setEnd($end);

                $updatedEvent = $this->service->events->update('primary', $updatedEvent->getId(), $updatedEvent);
            } else {
                $this->service->events->delete('primary', $updatedEvent->getId());

                $updatedEvent = null;
            }
        }


        return $updatedEvent;
    }

    public function deleteEvent()
    {

        $eventJson = json_decode($this->activity->google_calendar_event_object_json, true);

        $event = $this->service->events->get('primary', $eventJson['id']);

        if($event->status != 'cancelled') {

                $this->service->events->delete('primary', $event->getId());


        } else {
            $updatedEvent = $this->addEventToCalendar();


                $this->service->events->delete('primary', $updatedEvent->getId());


        };

        return true;
    }


}