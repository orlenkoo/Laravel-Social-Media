<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 10:23
 */
class Event360LeadsController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     * POST /leads
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Lead::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Lead::createLead($data);

        // add a deny permission to

        return Redirect::route('leads.index');
    }

    /**
     * Update the specified resource in storage.
     * PUT /leads/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $lead = Lead::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Lead::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $lead->update($data);


        return Redirect::route('leads.index');
    }


    public function updateAjaxLeadStatus()
    {
        $lead_id = Input::get('lead_id');

        $lead = Lead::find($lead_id);

        $lead->update(
            array(
                'status' => 'Accepted',
                'status_updated_datetime' => date('Y-m-d H:i:s'),
                'status_updated_by' => Session::get('user-id'),
            )
        );
    }

    public function updateAjaxLeadRating()
    {
        date_default_timezone_set("Asia/Singapore");
        $lead_id = Input::get('lead_id');
        $lead_rating = Input::get('lead_rating');

        $lead = Lead::find($lead_id);

        Lead::updateLeadRating($lead, $lead_rating, Session::get('user-id'));
    }

    public function forwardAjaxLead()
    {
        date_default_timezone_set("Asia/Singapore");
        $lead_id = Input::get('lead_id');
        $to_email = Input::get('to_email');
        $message = Input::get('message');

        $lead = Lead::find($lead_id);


        $lead_forward = Lead::forwardLead($lead, $to_email, $message, Session::get('user-id'));


        $audit_action = array(
            'action' => 'create',
            'model-id' => $lead_forward->id,
            'data' => $lead_forward
        );

        AuditTrail::addAuditEntry("LeadForward", json_encode($audit_action));


    }

    public function saveAjaxLeadNotes()
    {
        date_default_timezone_set("Asia/Singapore");
        $lead_id = Input::get('lead_id');
        $note = Input::get('note');

        $lead = Lead::find($lead_id);


        $lead_note = LeadNote::createLeadNote($lead->id, $note, Session::get('user-id'));


        $audit_action = array(
            'action' => 'create',
            'model-id' => $lead_note->id,
            'data' => $lead_note
        );

        AuditTrail::addAuditEntry("LeadNote", json_encode($audit_action));


    }


    public function saveAjaxMessage()
    {
        $event360_messenger_thread_id = Input::get('event360_messenger_thread_id');
        $message = Input::get('message');
        $user_id = Session::get('user-id');
        $sent_by = 'Vendor';

        Event360MessengerThreadMessage::saveMessage($event360_messenger_thread_id, $message, $sent_by, $user_id);


    }

    public function homePageWidgetLeadsByWeekChart()
    {
        header('Access-Control-Allow-Origin: *');

        $lead_source = Input::get('lead_source');

        if(Input::has('organization_id')){
            $organization_id = Input::get('organization_id');
        }else{
            $organization_id = Session::get('user-organization-id');
        }


        $widget_data_generator = new WidgetDataGenerator();
        $result = $widget_data_generator->getHomePageWidgetLeadsByWeekChart($lead_source, $organization_id);

        return $result;

    }

    public function exportToExcel()
    {

        Excel::create('leads reports', function ($excel) {

            $excel->sheet('Sheet', function ($sheet) {

                $leads_id_list_for_excel = json_decode(Input::get('leads_id_list_for_excel'));
                $leads = Lead::whereIn('id', $leads_id_list_for_excel)->get();
                $lead_source = Input::get('lead_source');
                $sheet->loadView('leads.excel.' . $lead_source, ['leads' => $leads]);

            });

        })->download('xls');

        return true;
    }

}