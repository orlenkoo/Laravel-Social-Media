<?php

class QuotationsController extends \BaseController {


    public function index(){

        return View::make('quotations.index')->render();
    }


    public function ajaxLoadQuotationsList(){

        $organization_id = Session::get('user-organization-id');
        $search_query = urldecode(Input::get('search_query'));
        $generated_by_filter = Input::get('generated_by_filter');
        $customer_filter = Input::get('customer_filter');
        $status_filter = Input::get('status_filter');
        $filter_campaign = Input::get('filter_campaign');
        $filter_lead_source = Input::get('filter_lead_source');


        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        //$user_id = Session::get('user-id');

        $build_query = Quotation::where('organization_id',$organization_id);

        if($generated_by_filter != '') {
            $build_query->where('quoted_by', $generated_by_filter);
        }

        if($search_query != '') {
            $build_query->where('project_quote', 'LIKE', '%' . $search_query . '%');
        }

        if($generated_by_filter != '' && $generated_by_filter != 'null') {
            $build_query->where('quoted_by',$generated_by_filter);
        }

        $customer_ids = [];
        if($customer_filter != '' && $customer_filter != 'null') {
            $build_query->where('customer_id', $customer_filter);
        } else {
            if($filter_campaign != '' && $filter_campaign != 'null' ){
                $filter_campaign = explode(",",$filter_campaign);
                $customer_ids = Lead::where('organization_id',$organization_id)->whereIn('auto_tagged_campaign', $filter_campaign)->lists('customer_id');

            }
        }

        if($filter_lead_source != '' && $filter_lead_source != 'null' ){
            $filter_lead_source = explode(",",$filter_lead_source);
            $customer_ids = array_merge($customer_ids, Lead::where('organization_id',$organization_id)->whereIn('lead_source', $filter_lead_source)->lists('customer_id'));
        }

        if($customer_ids) {
            $build_query->whereIn('customer_id', $customer_ids);
        }

        if($status_filter != '' && $status_filter != 'null') {
            $build_query->where('quotation_status', $status_filter);
        }



        $build_query->whereBetween('quoted_datetime' , array($from_date, $to_date));

        $quotations = $build_query->orderBy('id','desc')->paginate(5);

        return View::make('quotations._ajax_partials.quotations_list', compact('quotations'))->render();

    }

    public function ajaxSaveNewQuotation()
    {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $organization_id = Session::get('user-organization-id');
        $customer_id = Input::get('customer_id');
        $lead_id = Input::get('lead_id');
        $project_quote = Input::get('project_quote');
        $company_name = Input::get('company_name');
        $address = Input::get('address');
        $contact_person = Input::get('contact_person');
        $email = Input::get('email_address');
        $phone_number = Input::get('phone_number');
        $fax = Input::get('fax');
        $sub_total = Input::get('sub_total');
        $discount_percentage = Input::get('discount_percentage');
        $discount_value = Input::get('discount_value');
        $total_taxes = Input::get('total_taxes');
        $net_total = Input::get('net_total');
        $payment_terms = Input::get('payment_terms');

        $descriptions = Input::get('descriptions');
        $unit_of_measures = Input::get('unit_of_measures');
        $no_of_units = Input::get('no_of_units');
        $taxables = Input::get('taxables');
        $taxes = Input::get('taxes');
        $unit_costs = Input::get('unit_costs');
        $costs = Input::get('costs');

        syslog(LOG_INFO,"Quoted By -- ".Session::get('user-id'));

        $data_quotations = array(
            'customer_id' => $customer_id,
            'organization_id' => $organization_id,
            'lead_id' => $lead_id,
            'quoted_datetime' => $datetime,
            'quoted_by' => Session::get('user-id'),
            'project_quote' => $project_quote,
            'company_name' => $company_name,
            'address' => $address,
            'contact_person' => $contact_person,
            'email' => $email,
            'phone_number' => $phone_number,
            'fax' => $fax,
            'sub_total' => $sub_total,
            'discount_percentage' => $discount_percentage,
            'discount_value' => $discount_value,
            'total_taxes' => $total_taxes,
            'net_total' => $net_total,
            'payment_terms' => $payment_terms,
            'quotation_status' => 'New',
            'descriptions' => $descriptions,
            'unit_of_measures' => $unit_of_measures,
            'no_of_units' => $no_of_units,
            'unit_costs' => $unit_costs,
            'taxables' => $taxables,
            'taxes' => $taxes,
            'costs' => $costs
        );

        $quotation = Quotation::addNewQuotation($data_quotations);

        $notification_employee_id = Notification::where('type_of_alert', 'quotation_is_created')
            ->where('status', 1)
            ->where('email', 1)
            ->where('organization_id', $quotation->organization_id)
            ->lists('employee_id');

        $syslog_notification_employee_id = implode(",",$notification_employee_id);

        syslog(LOG_INFO,'$syslog_notification_employee_id -- '.$syslog_notification_employee_id);

        $employees_in_organzation_to_notify = Employee::where('organization_id',$quotation->organization_id)->whereIn('user_level',array(
            'marketing',
            'management'
        ))->where('status',1)
            ->whereIn('id', $notification_employee_id)
            ->where('status',1)
            ->get();

        Notification::quotationCreatedEmailNotification($quotation,$employees_in_organzation_to_notify);


        return "Quotation Created Successfully";
    }



    public function ajaxUpdateQuotation()
    {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $quotation_id = Input::get('quotation_id');
        $organization_id = Session::get('user-organization-id');
        $customer_id = Input::get('customer_id');
        $lead_id = Input::get('lead_id');
        $project_quote = Input::get('project_quote');
        $company_name = Input::get('company_name');
        $address = Input::get('address');
        $contact_person = Input::get('contact_person');
        $email = Input::get('email_address');
        $phone_number = Input::get('phone_number');
        $fax = Input::get('fax');
        $sub_total = Input::get('sub_total');
        $discount_percentage = Input::get('discount_percentage');
        $discount_value = Input::get('discount_value');
        $total_taxes = Input::get('total_taxes');
        $net_total = Input::get('net_total');
        $payment_terms = Input::get('payment_terms');

        $descriptions = Input::get('descriptions');
        $unit_of_measures = Input::get('unit_of_measures');
        $no_of_units = Input::get('no_of_units');
        $taxables = Input::get('taxables');
        $taxes = Input::get('taxes');
        $unit_costs = Input::get('unit_costs');
        $costs = Input::get('costs');

        $quotation_old = Quotation::find($quotation_id);

        $data_quotations = array(
            'customer_id' => $customer_id,
            'organization_id' => $organization_id,
            'lead_id' => $lead_id,
            'quoted_datetime' => $datetime,
            'quoted_by' => Session::get('user-id'),
            'project_quote' => $project_quote,
            'company_name' => $company_name,
            'address' => $address,
            'contact_person' => $contact_person,
            'email' => $email,
            'phone_number' => $phone_number,
            'fax' => $fax,
            'sub_total' => $sub_total,
            'discount_percentage' => $discount_percentage,
            'discount_value' => $discount_value,
            'total_taxes' => $total_taxes,
            'net_total' => $net_total,
            'payment_terms' => $payment_terms,
            'quotation_status' => $quotation_old->quotation_status,
            'descriptions' => $descriptions,
            'unit_of_measures' => $unit_of_measures,
            'no_of_units' => $no_of_units,
            'unit_costs' => $unit_costs,
            'taxables' => $taxables,
            'taxes' => $taxes,
            'costs' => $costs
        );

        Quotation::addNewQuotation($data_quotations);


        $quotation_old->update(array('quotation_status' => 'Updated'));

        return "Quotation Updated Successfully";
    }

    public function ajaxSendQuotationForApproval()
    {
        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $quotation_id = Input::get('quotation_id');
        $send_quotation_contact_person = Input::get('send_quotation_contact_person');
        $send_quotation_email = Input::get('send_quotation_email');
        $send_quotation_subject = Input::get('send_quotation_subject');
        $send_quotation_note = Input::get('send_quotation_note');

        // get sender details
        $sender = Employee::find(Session::get('user-id'));

        $quotation = Quotation::find($quotation_id);

        $quotation->update(array('quotation_status' => 'Pending'));


        // update lead rating
        $lead = Lead::find($quotation->lead_id);

        if(is_object($lead)){
            $lead->update(array('lead_rating' => 'Quoted', 'lead_rating_updated_datetime' => $datetime));
        }

        $attachments = array();

        $file_save_data = Quotation::generateQuotationPDF($quotation_id, 'email_attachment');

        array_push($attachments,array(
            'file_path' => $file_save_data['image_url'],
            'file_name' => 'quotation_'.$quotation->id.'.pdf'
        ));

        $subject = $send_quotation_subject;

        syslog(LOG_INFO, '$send_quotation_note -- '. $send_quotation_note);

        $signature_html = $sender->signature_html;
        $signature_file_url = $sender->signature_file_url;

        $mailBody = View::make('quotations._partials.quotation_approval_email', compact('quotation',
            'send_quotation_note',
            'send_quotation_contact_person',
            'signature_html',
            'signature_file_url'
        ))->render();

        EmailsController::sendGridEmailSender($subject, $mailBody, $send_quotation_email, $sender->email, "", $sender->getEmployeeFullName(), $attachments);


        $audit_model_id = 0;

        // update lead rating - to quoted
        $data_lead = array('lead_rating' => "Quoted");

        if(is_object($lead)){
            $quotation->lead->update($data_lead);
            $audit_model_id = $quotation->lead->id;
        }

        $audit_action = array(
            'action' => 'update',
            'model-id' => $audit_model_id,
            'data' => $data_lead
        );
        AuditTrail::addAuditEntry("Quoted", json_encode($audit_action));

        return "Successfully Sent Quotation";

    }


    public function generatePDF(){


        $quotation_id = Input::get('quotation_id');

        $file_save_data = Quotation::generateQuotationPDF($quotation_id, null);

        $response = Response::make(file_get_contents($file_save_data['image_url']), '200');

        $response->header('Content-Type', 'application/pdf');

        return $response;
    }

    public function ajaxLoadQuotationAttachmentsTable(){

        $quotation_id = Input::get('quotation_id');
        $quotation = Quotation::find($quotation_id);
        $quotation_attachments = QuotationAttachment::where('quotation_id', $quotation_id)->orderBy('id','desc')->get();

        return View::make('quotations._partials.quotation_attachments_table', compact('quotation','quotation_attachments'))->render();

    }

    public function ajaxSaveQuotationAttachment(){

        $quotation_id = Input::get('quotation_id');
        $title = Input::get('title');
        $description = Input::get('description');
        $save_method = Input::get('quotation_attachment_save_method');
        $quotation_attachment_id = Input::get('quotation_attachment_id');

        $data_attachment['quotation_gcs_file_url'] = '';
        $data_attachment['quotation_file_url'] = '';

        $response_data = '';

        if ($save_method == 'edit') {

            $quotation_attachment = QuotationAttachment::findOrFail($quotation_attachment_id);

            $data = array(
                'title' => $title,
                'description' => $description
            );

            $quotation_attachment->update($data);

            $audit_action = array(
                'action' => 'update',
                'model-id' => $quotation_attachment->id,
                'data' => array("quotation_id" => $quotation_id, "data" => $data)
            );
            AuditTrail::addAuditEntry("QuotationAttachment", json_encode($audit_action));

            $response_data = 'Quotation Attachment Updated Successfully.';

        } else {
            $data_quotation_attachment = array(
                'quotation_id' => $quotation_id,
                'title' => $title,
                'description' => $description
            );

            $quotation_attachment = QuotationAttachment::create($data_quotation_attachment);

            $audit_action = array(
                'action' => 'create',
                'model-id' => $quotation_attachment->id,
                'data' => $data_quotation_attachment
            );
            AuditTrail::addAuditEntry("QuotationAttachment", json_encode($audit_action));

            $response_data = 'Quotation Attachment Created Successfully.';

        }

        if (Input::hasFile('attachment')) {

            $attachment_file = Input::file('attachment');

            // generate file name
            $file_name = 'quotation_attachment_' . $quotation_attachment->id . '.' . $attachment_file->getClientOriginalExtension();

            $file_save_data = GCSFileHandler::saveFile($attachment_file, $file_name);

            $data_attachment['quotation_gcs_file_url'] = $file_save_data['gcs_file_url'];
            $data_attachment['quotation_file_url'] = $file_save_data['image_url'];

            $quotation_attachment->update($data_attachment);
        }

        return $response_data;
    }

    public function ajaxUpdateQuotationStatus()
    {
        $quotation_id = Input::get('quotation_id');
        $quotation_status = Input::get('status');

        $quotation_update_status = Quotation::updateQuotationStatus($quotation_id, $quotation_status);
        $quotation = Quotation::find($quotation_id);

        $employee =  Employee::findOrFail(Session::get('user-id'));
        $quotation_status_changed_by = $employee->getEmployeeFullName();

        $notification_employee_id = Notification::where('type_of_alert', 'quotation_status_is_changed')
            ->where('status', 1)
            ->where('email', 1)
            ->where('organization_id', $quotation->organization_id)
            ->lists('employee_id');

        syslog(LOG_INFO,'$notification_employee_id -- '.implode('|',$notification_employee_id));

        $employees_in_organzation_to_notify = Employee::where('organization_id',$quotation->organization_id)->whereIn('user_level',array(
            'marketing',
            'management'
        ))->where('status',1)
            ->whereIn('id', $notification_employee_id)
            ->where('status',1)
            ->get();

        Notification::quotationStatusChangedEmailNotification($quotation,$quotation_status_changed_by,$employees_in_organzation_to_notify);

        return "Successfully Updated Quotation Status.";
    }


    public function updateQuotationStatusByCustomer(){

        $quotation_id = Input::get('quotation_id');
        $quotation_status = Input::get('status');

        Quotation::updateQuotationStatus($quotation_id, $quotation_status);

        echo "Thank you. Our representative will be in touch with you shortly.";
    }

    public function ajaxLoadDashboardContractedSalesWidget()
    {

        date_default_timezone_set("Asia/Singapore");
        $organization_id = Session::get('user-organization-id');



        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $my_sales_widget_assigned_to = Input::get('my_sales_widget_assigned_to');

        $sales_filter_query = '';
        if($my_sales_widget_assigned_to != 'null') {
            $sales_filter_query = "AND quotations.quotation_closed_by IN (".$my_sales_widget_assigned_to.")";
        }

        $query_contracted_sales_list = "select lead_source,company_name, concat(given_name,' ',surname) as sales_closed_by ,sum(net_total - total_taxes) as `achieved_sales`,auto_tagged_campaign
                                    from quotations
                                    left join customers
                                    on quotations.customer_id = customers.id
                                    left join leads
                                    on quotations.lead_id = leads.id
                                    left join employees
                                    on quotations.quoted_by = employees.id
                                    where quotations.organization_id = $organization_id and quotations.quotation_status != 'Updated'
                                      and quoted_datetime between '$from_date' and '$to_date'
                                      and quotations.quotation_status = 'Closed' $sales_filter_query
                                    group by company_name
                                    order by achieved_sales desc";


        $contracted_sales_list = DB::select($query_contracted_sales_list);

        return View::make('dashboard.common._ajax_partials.contracted_sales_list_widget', compact('contracted_sales_list'))->render();


    }

    public function ajaxLoadMySalesDashboardSalesVolumeByCampaignChart(){

        $organization_id = Session::get('user-organization-id');
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $my_sales_widget_assigned_to = Input::get('my_sales_widget_assigned_to');

        $sales_filter_query = '';
        if($my_sales_widget_assigned_to != 'null') {
            $sales_filter_query = "AND quotations.quotation_closed_by IN (".$my_sales_widget_assigned_to.")";
        }

        $lead_volume_by_campaigns = Quotation::salesVolumeByCampaignData($organization_id,$from_date,$to_date,$sales_filter_query);

        return json_encode($lead_volume_by_campaigns);
    }

    public function ajaxLoadMySalesDashboardSalesValueByCampaignChart(){

        $organization_id = Session::get('user-organization-id');
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';

        $my_sales_widget_assigned_to = Input::get('my_sales_widget_assigned_to');

        $sales_filter_query = '';
        if($my_sales_widget_assigned_to != 'null') {
            $sales_filter_query = "AND quotations.quotation_closed_by IN (".$my_sales_widget_assigned_to.")";
        }

        $lead_value_by_campaigns = Quotation::salesValueByCampaignData($organization_id,$from_date,$to_date,$sales_filter_query);

        return json_encode($lead_value_by_campaigns);
    }

    public function ajaxLoadMySalesDashboardSalesVolumeBySalesPersonChart(){

        $organization_id = Session::get('user-organization-id');
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';



        $lead_volume_by_campaigns = Quotation::salesVolumeBySalesPersonData($organization_id,$from_date,$to_date);

        return json_encode($lead_volume_by_campaigns);
    }

    public function ajaxLoadMySalesDashboardSalesValueBySalesPersonChart(){

        $organization_id = Session::get('user-organization-id');
        $filter_date_range = Input::get('dashboard_filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');
        $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
        $from_date = $date_range['from_date']. ' 00:00:00';
        $to_date = $date_range['to_date']. ' 23:59:59';



        $lead_value_by_campaigns = Quotation::salesValueBySalesPersonData($organization_id,$from_date,$to_date);

        return json_encode($lead_value_by_campaigns);
    }

    public function sendQuotationFollowUpReminderEmail()
    {

        syslog(LOG_INFO, 'Running Quotation Follow Up Reminder.');
        try {

            //get all organization who want the quoatation follow up reminder
            $organization_id_for_follow_up = OrganizationPreference::where('send_quotation_follow_up_email_reminder', 1)
            ->lists('organization_id');

            //get only the active organizations from that list
            $organization_id = Organization::where('status',1)
                ->whereIn('id',$organization_id_for_follow_up)
                ->lists('id');

            if(count($organization_id)) {

                $quotations = Quotation::whereIn('quotation_status',array(
                    'Pending' => 'Pending',
                    'Negotiate' => 'Negotiate',
                ))->whereIn('organization_id',$organization_id)
                    ->get();

                $subject = "WEB360: Quotation Follow Up";

                syslog(LOG_INFO, 'subject -- '.$subject);

                foreach($quotations as $quotation) {

                    $quoted_by = $quotation->quotedBy;

                    if(is_object($quoted_by)){
                        $quoted_by_email = $quoted_by->email;

                        syslog(LOG_INFO, 'subject -- '.$quoted_by_email);

                        $mailBody = '';
                        $mailBody .= View::make('emails.quotation_follow_up_reminder', compact('quotation','quoted_by'))->render();

                        EmailsController::sendGridEmailSender($subject, $mailBody, $quoted_by_email);
                    }
                }
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, 'error caused -- '.$e->getMessage());
            syslog(LOG_ERR, 'error caused -- '.$e->getTraceAsString());
        }
    }

}
