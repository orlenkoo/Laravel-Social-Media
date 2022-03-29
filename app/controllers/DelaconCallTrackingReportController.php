<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/5/2016
 * Time: 12:37 PM
 */
class DelaconCallTrackingReportController extends \BaseController
{

    public function callAPI($from_date, $to_date, $customer_name, $delacon_customer_account)
    {
        $return_result = array('call_tracking_records' => null, 'message' => "", 'status' => false);

        syslog(LOG_INFO, '$customer_name -- '. $customer_name);

        if (preg_replace('/\s+/', '', $customer_name) != '' && $customer_name != null) {

            if($delacon_customer_account == "Webnatics") {
                $api_key = '600_0hrZfZGUCeevApS4r3K1W9kj+dp+BqCbBPN4vQFZJeYiVYBOa7De5aOmDhphUri2'; // webnatics api key
            } else if($delacon_customer_account == "Event360") {
                $api_key = '785_Jig3v7tTl/mi09ySxBTmAXkhTkPL7mZC+cLA9KulbLqW2phWoSUAgJef7YpNNfsA'; // event360 api key
            }



            $customer_name = urlencode($customer_name);


            $url = "https://pla.delaconcorp.com/site/report/report.jsp?reportoption=xml&showrecurl=1&datefrom=$from_date&dateto=$to_date&customername=$customer_name&showcid=1&showrowid=1";
            syslog(LOG_INFO, '$url -- '. $url);
            $data = ['data' => 'this', 'data2' => 'that'];
            $headers = "accept: */*\r\n" .
                "Content-Type: application/text\r\n" .
                "Auth: $api_key\r\n";

            $context = [
                'http' => [
                    'method' => 'POST',
                    'header' => $headers,
                    'content' => http_build_query($data),
                ]
            ];
            $context = stream_context_create($context);
            $result = file_get_contents($url, false, $context);


            if (strpos($result, 'report period cannot exceed 100 days') === false) {

                if(strpos($result, 'customer name specified not found for this user') === false) {
                    syslog(LOG_INFO, '$result -- '. $result);

                    $call_tracking_records_xml = simplexml_load_string($result) or die("Error: Cannot create object");

                    syslog(LOG_INFO, '$call_tracking_records_xml -- ' . json_encode($call_tracking_records_xml));

                    $call_tracking_records = $call_tracking_records_xml->CallingFlow;

                    $return_result['call_tracking_records'] = $call_tracking_records;
                    $return_result['message'] = "";
                    $return_result['status'] = true;
                } else {
                    $return_result['call_tracking_records'] = null;
                    $return_result['status'] = false;
                    $return_result['message'] = "Customer name specified not found for this user.";
                }




            } else {
                $return_result['call_tracking_records'] = null;
                $return_result['status'] = false;
                $return_result['message'] = "Date range cannot exceed 100 days.";
            }
        } else {
            $return_result['call_tracking_records'] = null;
            $return_result['status'] = false;
            $return_result['message'] = "Call Tracking Customer Name not setup.";
        }

        return $return_result;
    }

    public function viewReport()
    {
        $lead_source = Input::get('filter_call_tracking_lead_source');
        $lead_rating = Input::get('filter_call_tracking_lead_rating');
        $from_date = date("Y-m-d", strtotime(Input::get('filter_call_tracking_from_date'))) . " 00:00:00";
        $to_date = date("Y-m-d", strtotime(Input::get('filter_call_tracking_to_date'))) . " 23:59:59";

        $build_query = Lead::whereBetween('datetime', array($from_date, $to_date))->where('lead_source', 'event360_calls')->where('organization_id', Session::get('user-organization-id'));


        if($lead_rating != '') {
            $build_query->where('lead_rating', $lead_rating);
        }

        $event360_call_leads = $build_query->paginate(10);

        return View::make('webtics_product.third_party_api.delacon_call_tracking.report', compact('event360_call_leads', 'lead_source'))->render();
    }

    public function saveToTheDatabase()
    {
        $delacon_properties = DelaconProperty::where('status', 1)->get();

        date_default_timezone_set("Asia/Singapore");
        $date = date('Y-m-d');

        foreach($delacon_properties as $delacon_property) {
            $result = $this->callAPI($date, $date, $delacon_property->delacon_property_name, $delacon_property->delacon_customer_account);
            //$result = $this->callAPI($date, $date, "SWEE SENG CREDIT PTE LTD"); // this is to check a hard coded customer

            if($result['status']) {
                $call_tracking_records = $result['call_tracking_records'];
                foreach($call_tracking_records as $call_tracking_record) {
                    Event360Call::saveCallRecord($delacon_property->organization_id, $call_tracking_record);
                }

            } else {
                echo $result['message'];
            }
        }

        return "Saved Successfully.";
    }

    public function delaconPushAPISaveCallRecord()
    {
        date_default_timezone_set("Asia/Singapore");
        $timestamp = date('Y-m-d H:i:s');
        $time = date('H:i:s');

        $data = Input::all();
        syslog(LOG_INFO, 'delacon push data -- '.json_encode($data));

        $PLA_Call_Id = Input::has('PLA_Call_Id')? Input::get('PLA_Call_Id'): "";
        $PLA_CID = Input::has('PLA_CID')? Input::get('PLA_CID'): "";

        // first check if call record is there in the database
        $existing_event360_call = Event360Call::where('delacon_cid', $PLA_CID)->where('delacon_call_id', $PLA_Call_Id)->first();



        if(is_object($existing_event360_call)) {
            $PLA_Answering_Point = Input::has('PLA_Answering_Point')? Input::get('PLA_Answering_Point'): "";
            $PLA_CallResult = Input::has('PLA_CallResult')? Input::get('PLA_CallResult'): "";
            $PLA_Duration = Input::has('PLA_Duration')? Input::get('PLA_Duration'): "";
            $PLA_VoicemailLeft = Input::has('PLA_VoicemailLeft')? Input::get('PLA_VoicemailLeft'): "";
            $PLA_SurveyOutcome = Input::has('PLA_SurveyOutcome')? Input::get('PLA_SurveyOutcome'): "";
            $PLA_SurveySaleAmount = Input::has('PLA_SurveySaleAmount')? Input::get('PLA_SurveySaleAmount'): "";
            $PLA_Converted = Input::has('PLA_Converted')? Input::get('PLA_Converted'): "";
            $PLA_dtmf = Input::has('PLA_dtmf')? Input::get('PLA_dtmf'): "";
            $PLA_RecordingFile = Input::has('PLA_RecordingFile')? Input::get('PLA_RecordingFile'): "";

            $data_event360_call = array(
                'transferred_number' => $PLA_Answering_Point,
                'result' => $PLA_CallResult,
                'duration' => $PLA_Duration,
                'durationof1300' => $PLA_Duration,
                'voice_mail_left' => $PLA_VoicemailLeft,
                'extra_tracking' => '',
                'converted' => $PLA_Converted,
                'survey_outcome' => $PLA_SurveyOutcome,
                'survey_sales_amount' => $PLA_SurveySaleAmount,
                'recording_url' => $PLA_RecordingFile,
            );
            $existing_event360_call->update($data_event360_call);

        } else {

            $delacon_property = DelaconProperty::where('delacon_cid', $PLA_CID)->first();

            if(is_object($delacon_property)) {
                $PLA_Caller_Phone_Number = Input::has('PLA_Caller_Phone_Number')? Input::get('PLA_Caller_Phone_Number'): "";
                $PLA_Exchange = Input::has('PLA_Exchange')? Input::get('PLA_Exchange'): "";
                $PLA_City = Input::has('PLA_City')? Input::get('PLA_City'): "";
                $PLA_State = Input::has('PLA_State')? Input::get('PLA_State'): "";
                $PLA_Number_Dialed = Input::has('PLA_Number_Dialed')? Input::get('PLA_Number_Dialed'): "";
                $PLA_Dealer_ID = Input::has('PLA_Dealer_ID')? Input::get('PLA_Dealer_ID'): "";
                $PLA_Dealer_Name = Input::has('PLA_Dealer_Name')? Input::get('PLA_Dealer_Name'): "";
                $PLA_Dealer_Category = Input::has('PLA_Dealer_Category')? Input::get('PLA_Dealer_Category'): "";
                $PLA_Search_Engine_Used = Input::has('PLA_Search_Engine_Used')? Input::get('PLA_Search_Engine_Used'): "";
                $PLA_Search_Type = Input::has('PLA_Search_Type')? Input::get('PLA_Search_Type'): "";
                $PLA_Keywords_Searched = Input::has('PLA_Keywords_Searched')? Input::get('PLA_Keywords_Searched'): "";
                $PLA_URL = Input::has('PLA_URL')? Input::get('PLA_URL'): "";

                $data_event360_call = array(
                    'delacon_cid' => $PLA_CID,
                    'delacon_call_id' => $PLA_Call_Id,
                    'datetime' => $timestamp,
                    'incoming_call_number' => $PLA_Caller_Phone_Number,
                    'geo_origin' => $PLA_City . ' ' . $PLA_State,
                    'number1300' => $PLA_Number_Dialed,
                    'transferred_number' => '',
                    'time' => $timestamp,
                    'result' => '',
                    'dealer_id' => $PLA_Dealer_ID,
                    'dealer_name' => $PLA_Dealer_Name,
                    'dealer_category' => $PLA_Dealer_Category,
                    'duration' => '',
                    'durationof1300' => '',
                    'voice_mail_left' => '',
                    'search_engine' => $PLA_Search_Engine_Used,
                    'type' => $PLA_Search_Type,
                    'keyword' => $PLA_Keywords_Searched,
                    'url' => $PLA_URL,
                    'extra_tracking' => '',
                    'converted' => '',
                    'survey_outcome' => '',
                    'survey_sales_amount' => '',
                    'recording_url' => '',
                    'api_call_type' => 'push',
                );
                $event360_call = Event360Call::create($data_event360_call);

                // get campaign id if present
                $campaign_id = DB::table('campaigns')->where('call_tracking_number', $PLA_Number_Dialed)->pluck('id');

                // add lead for this
                $data_lead = array(
                    'organization_id' => $delacon_property->organization_id,
                    'campaign_id' => $campaign_id,
                    'datetime' => $timestamp,
                    'lead_source' => 'event360_calls',
                    'lead_source_id' => $event360_call->id,
                    'status' => 'Accepted',
                    'lead_rating' => 'Raw Lead',
                    'utm_source' => '', // hard coded
                    'utm_medium' => '', // hard coded
                    'webtics_pixel_session_id' => '', // hard coded
                    'webtics_pixel_property_id' => '', // hard coded
                );
                $lead = Lead::createLead($data_lead);
                //Event360EmailAlertsController::newEvent360CallLeadAlert($lead);
            }



        }

        $response_array = array(
            "PLA_Call_Id" => "".Input::get('PLA_Call_Id'),
            "success"=> "true",
            "error"=> ""

        );

        return json_encode($response_array);
    }

}