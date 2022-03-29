<?php
/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 6/28/2019
 * Time: 9:25 AM
 */

class NovocallLead extends \Eloquent
{
    protected $table = "novocall_leads";

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'schedule_id',
        'type_of_lead',
        'lead_details',
    ];

    public static $types_of_novocall_leads = [
        'Call' => 'Call',
        'Schedule' => 'Schedule',
        'Message' => 'Message',
        'Event' => 'Event',
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public static function saveNovocallLead($type_of_lead, $identifier, $schedule_id, $novocall_lead_data)
    {
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('Y-m-d H:i:s');

        $utm_source = $novocall_lead_data['utm_source'];
        $utm_medium = $novocall_lead_data['utm_medium'];
        $utm_campaign = $novocall_lead_data['utm_campaign'];
        $utm_content = $novocall_lead_data['utm_content'];
        $utm_term = $novocall_lead_data['utm_term'];

        unset($novocall_lead_data['utm_source']);
        unset($novocall_lead_data['utm_medium']);
        unset($novocall_lead_data['utm_campaign']);
        unset($novocall_lead_data['utm_content']);
        unset($novocall_lead_data['utm_term']);

        if ($schedule_id != null || trim($schedule_id) != '') {
            $scheduled_call = NovocallLead::where('schedule_id', $schedule_id)->first();
        } else {
            $scheduled_call = null;
        }

        if (is_object($scheduled_call)) {
            $scheduled_call->update($novocall_lead_data);
        } else {
            $novocall_lead = NovocallLead::create([
                'organization_id' => $identifier,
                'type_of_lead' => $type_of_lead,
                'lead_details' => json_encode($novocall_lead_data),
            ]);

            $data_lead = [
                'organization_id' => $identifier,
                'datetime' => $timestamp,
                'lead_source' => 'novocall_leads',
                'lead_source_id' => $novocall_lead->id,
                'status' => 'Accepted',
                'lead_rating' => 'Raw Lead',
                'utm_source' => $utm_source,
                'utm_medium' => $utm_medium,
                'utm_term' => $utm_term,
                'utm_campaign' => $utm_campaign,
                'utm_content' => $utm_content,
            ];
            $lead = Lead::createLead($data_lead);
        }


        return true;
    }
}