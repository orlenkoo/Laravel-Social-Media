<?php
/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 6/28/2019
 * Time: 8:53 AM
 */

class NovocallAPIIntegrationsController extends \BaseController
{
    public function saveCallLead()
    {
        if (Input::has('identifier')) {
            $schedule_id = Input::get('schedule_id');
            $identifier = Input::get('identifier');
            $id = Input::get('id');
            $event = Input::get('event');
            $call_status_dest1 = Input::get('call_status_dest1');
            $dest2 = Input::get('dest2');
            $source = Input::get('source');
            $manager_name = Input::get('manager_name');
            $manager_number = Input::get('manager_number');
            $name = Input::get('name');
            $email = Input::get('email');
            $created_at = Input::get('created_at');
            $duration = Input::get('duration');
            $ip_address = Input::get('ip_address');
            $call_status_dest2 = Input::get('call_status_dest2');
            $widget = Input::get('widget');
            $utm_source = Input::get('utm_source');
            $utm_medium = Input::get('utm_medium');
            $utm_campaign = Input::get('utm_campaign');
            $utm_content = Input::get('utm_content');
            $utm_term = Input::get('utm_term');

            $novocall_lead_data = [
                'id' => $id,
                'event' => $event,
                'call_status_dest1' => $call_status_dest1,
                'dest2' => $dest2,
                'source' => $source,
                'manager_name' => $manager_name,
                'manager_number' => $manager_number,
                'name' => $name,
                'email' => $email,
                'created_at' => $created_at,
                'duration' => $duration,
                'ip_address' => $ip_address,
                'call_status_dest2' => $call_status_dest2,
                'widget' => $widget,
                'utm_source' => $utm_source,
                'utm_medium' => $utm_medium,
                'utm_campaign' => $utm_campaign,
                'utm_content' => $utm_content,
                'utm_term' => $utm_term,
            ];

            syslog(LOG_INFO, 'novocall - lead - data -- ' . json_encode($novocall_lead_data));

            NovocallLead::saveNovocallLead('Call', $identifier, $schedule_id, $novocall_lead_data);

            return json_encode(
                [
                    'status' => 'success'
                ]
            );
        } else {
            return json_encode(
                [
                    'status' => 'failed'
                ]
            );
        }

    }


    public function saveMessageLead()
    {
        if (Input::has('identifier')) {
            $schedule_id = Input::get('schedule_id');
            $identifier = Input::get('identifier');
            $id = Input::get('id');
            $event = Input::get('event');
            $number = Input::get('number');
            $name = Input::get('name');
            $email = Input::get('email');
            $source = Input::get('source');
            $content = Input::get('content');
            $created_at = Input::get('created_at');
            $ip_address = Input::get('ip_address');
            $widget = Input::get('widget');
            $utm_source = Input::get('utm_source');
            $utm_medium = Input::get('utm_medium');
            $utm_campaign = Input::get('utm_campaign');
            $utm_content = Input::get('utm_content');
            $utm_term = Input::get('utm_term');

            $novocall_lead_data = [
                'id' => $id,
                'event' => $event,
                'number' => $number,
                'name' => $name,
                'email' => $email,
                'source' => $source,
                'content' => $content,
                'created_at' => $created_at,
                'ip_address' => $ip_address,
                'widget' => $widget,
                'utm_source' => $utm_source,
                'utm_medium' => $utm_medium,
                'utm_campaign' => $utm_campaign,
                'utm_content' => $utm_content,
                'utm_term' => $utm_term,
            ];

            syslog(LOG_INFO, 'novocall - lead - data -- ' . json_encode($novocall_lead_data));

            NovocallLead::saveNovocallLead('Message', $identifier, $schedule_id, $novocall_lead_data);

            return json_encode(
                [
                    'status' => 'success'
                ]
            );
        } else {
            return json_encode(
                [
                    'status' => 'failed'
                ]
            );
        }
    }

    public function saveScheduleLead()
    {
        if (Input::has('identifier')) {
            $schedule_id = Input::get('schedule_id');
            $identifier = Input::get('identifier');
            $id = Input::get('id');
            $event = Input::get('event');
            $number = Input::get('number');
            $name = Input::get('name');
            $email = Input::get('email');
            $timing = Input::get('timing');
            $timing_end = Input::get('timing_end');
            $created_at = Input::get('created_at');
            $ip_address = Input::get('ip_address');
            $source = Input::get('source');
            $auto = Input::get('auto');
            $widget = Input::get('widget');
            $utm_source = Input::get('utm_source');
            $utm_medium = Input::get('utm_medium');
            $utm_campaign = Input::get('utm_campaign');
            $utm_content = Input::get('utm_content');
            $utm_term = Input::get('utm_term');

            $novocall_lead_data = [
                'id' => $id,
                'event' => $event,
                'number' => $number,
                'name' => $name,
                'email' => $email,
                'timing' => $timing,
                'timing_end' => $timing_end,
                'created_at' => $created_at,
                'ip_address' => $ip_address,
                'source' => $source,
                'auto' => $auto,
                'widget' => $widget,
                'utm_source' => $utm_source,
                'utm_medium' => $utm_medium,
                'utm_campaign' => $utm_campaign,
                'utm_content' => $utm_content,
                'utm_term' => $utm_term,
            ];

            syslog(LOG_INFO, 'novocall - lead - data -- ' . json_encode($novocall_lead_data));

            NovocallLead::saveNovocallLead('Schedule', $identifier, $schedule_id, $novocall_lead_data);

            return json_encode(
                [
                    'status' => 'success'
                ]
            );
        } else {
            return json_encode(
                [
                    'status' => 'failed'
                ]
            );
        }
    }
}