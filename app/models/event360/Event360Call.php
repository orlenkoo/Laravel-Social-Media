<?php

class Event360Call extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'event360_calls';

    // Don't forget to fill this array
    protected $fillable = [
        'delacon_cid',
        'delacon_call_id',
        'delacon_call_identification',
        'datetime',
        'incoming_call_number',
        'geo_origin',
        'number1300',
        'transferred_number',
        'time',
        'result',
        'dealer_id',
        'dealer_name',
        'dealer_category',
        'duration',
        'durationof1300',
        'voice_mail_left',
        'search_engine',
        'type',
        'keyword',
        'url',
        'extra_tracking',
        'converted',
        'survey_outcome',
        'survey_sales_amount',
        'recording_url',
        'api_call_type',
    ];

    public function leads()
    {
        return $this->hasMany('Lead');
    }

    public static function createDelaconCallIdentification($call_tracking_record)
    {
        return str_replace(' ', '_', $call_tracking_record->Time . '_' . $call_tracking_record->Number1300);
    }

    public static function saveCallRecord($organization_id, $call_tracking_record)
    {
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('Y-m-d H:i:s');

        $delacon_call_identification = Event360Call::createDelaconCallIdentification($call_tracking_record);

        $event360_call = Event360Call::where('delacon_call_identification', $delacon_call_identification)->first();

        if (!is_object($event360_call)) {
            $data_event360_call = array(
                'delacon_cid' => $delacon_call_identification->CompanyID,
                'delacon_call_identification' => $delacon_call_identification,
                'datetime' => $timestamp,
                'incoming_call_number' => $call_tracking_record->IncomingCallNumber,
                'geo_origin' => $call_tracking_record->{'Geo-Origin'},
                'number1300' => $call_tracking_record->Number1300,
                'transferred_number' => $call_tracking_record->TransferredNumber,
                'time' => $call_tracking_record->Time,
                'result' => $call_tracking_record->Result,
                'dealer_id' => $call_tracking_record->DealerID,
                'dealer_name' => $call_tracking_record->DealerName,
                'dealer_category' => $call_tracking_record->DealerCategory,
                'duration' => $call_tracking_record->Duration,
                'durationof1300' => $call_tracking_record->DurationOf1300,
                'voice_mail_left' => $call_tracking_record->VoiceMailLeft,
                'search_engine' => $call_tracking_record->SearchEngine,
                'type' => $call_tracking_record->Type,
                'keyword' => $call_tracking_record->Keyword,
                'url' => $call_tracking_record->URL,
                'extra_tracking' => $call_tracking_record->ExtraTracking,
                'converted' => $call_tracking_record->Converted,
                'survey_outcome' => $call_tracking_record->SurveyOutcome,
                'survey_sales_amount' => $call_tracking_record->SurveySalesAmount,
                'recording_url' => $call_tracking_record->RecordingUrl,
                'api_call_type' => 'reporting',
            );

            $event360_call = Event360Call::create($data_event360_call);

            // add lead for this
            $data_lead = array(
                'organization_id' => $organization_id,
                'datetime' => $timestamp,
                'lead_source' => 'event360_calls',
                'lead_source_id' => $event360_call->id,
                'status' => 'Accepted',
                'source' => '', // hard coded
                'medium' => '', // hard coded
                'webtics_pixel_session_id' => '', // hard coded
                'webtics_pixel_property_id' => '', // hard coded
            );
            $lead = Lead::createLead($data_lead);
            //Event360EmailAlertsController::newEvent360CallLeadAlert($lead);

        }

        return $event360_call;
    }
}