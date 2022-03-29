<?php

class Quotation extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = "quotations";

    // Don't forget to fill this array
    protected $fillable = [
        'customer_id',
        'organization_id',
        'lead_id',
        'quoted_datetime',
        'quoted_by',
        'project_quote',
        'company_name',
        'address',
        'contact_person',
        'email',
        'phone_number',
        'fax',
        'sub_total',
        'discount_percentage',
        'discount_value',
        'total_taxes',
        'net_total',
        'payment_terms',
        'quotation_status',
        'quotation_closed_at',
        'quotation_closed_by'
    ];

    public static $quotation_status = array(
        'New' => 'New',
        'Pending' => 'Pending',
        'Closed' => 'Closed',
        'Lost' => 'Lost',
        'Negotiate' => 'Negotiate',
        'Updated' => 'Updated'
    );

    public static $allowed_user_levels_to_send_quotations = array(
        'marketing' => 'Marketing',
        'management' => 'Management',
        'super_user' => 'Super User',
    );

    // relationships

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function lead()
    {
        return $this->belongsTo('Lead', 'lead_id');
    }

    public function quotedBy()
    {
        return $this->belongsTo('Employee', 'quoted_by');
    }

    public function quotationItems()
    {
        return $this->hasMany('QuotationItem');
    }

    public function quotationAttachments()
    {
        return $this->hasMany('QuotationAttachment');
    }

    public static function getNumberOfQuotationsForGivenCustomer($customer){
        $quotation_count = is_object($customer) ? sizeof($customer->quotations) : 0 ;
        return $quotation_count;
    }

    public static function updateQuotationStatus($quotation_id, $quotation_status) {

        date_default_timezone_set("Asia/Singapore");
        $datetime = date('Y-m-d H:i:s');

        $data_quotation = array(
            'quotation_status' => $quotation_status
        );

        if($quotation_status == 'Closed'){
            array_push($data_quotation,array(
                'quotation_closed_at' => $datetime,
                'quotation_closed_by' => Session::get('user-id')
            ));
        }

        $quotation = Quotation::find($quotation_id);
        $quotation->update($data_quotation);

        // update lead rating
        $lead_rating = "";

        if($quotation_status == "Closed") {
            $lead_rating = "Quoted - Won";
        } else if($quotation_status == "Negotiate") {
            $lead_rating = "Quoted - Negotiation";
        } else if($quotation_status == "Lost") {
            $lead_rating = "Quoted - Lost";
        }

        syslog(LOG_INFO, '$lead id -- '. $quotation->lead->id);
        syslog(LOG_INFO, '$lead_rating -- '. $lead_rating);

        $data_lead = array('lead_rating' => $lead_rating);

        $quotation->lead->update($data_lead);

        return true;
    }


    public static function addNewQuotation($data_quotations)
    {
        $descriptions = $data_quotations['descriptions'];
        $unit_of_measures = $data_quotations['unit_of_measures'];
        $no_of_units = $data_quotations['no_of_units'];
        $unit_costs = $data_quotations['unit_costs'];
        $taxables = $data_quotations['taxables'];
        $taxes = $data_quotations['taxes'];
        $costs = $data_quotations['costs'];
        $datetime = $data_quotations['quoted_datetime'];
        $customer_id = $data_quotations['customer_id'];

        $quotation = Quotation::create($data_quotations);


        $audit_action = array(
            'action' => 'create',
            'model-id' => $quotation->id,
            'data' => $data_quotations
        );
        AuditTrail::addAuditEntry("Quotation", json_encode($audit_action));

        // update quotation items
        $i = 0;
        foreach($descriptions as $description) {
            $data_quotation_items = array(
                'quotation_id' => $quotation->id,
                'description' => $description,
                'unit_of_measure' => $unit_of_measures[$i],
                'no_of_units' => $no_of_units[$i],
                'unit_cost' => $unit_costs[$i],
                'taxable' => $taxables[$i],
                'tax' => $taxes[$i],
                'cost' => $costs[$i]
            );

           $quotation_item = QuotationItem::create($data_quotation_items);

            $audit_action = array(
                      'action' => 'create',
                      'model-id' => $quotation_item->id,
                      'data' => $data_quotation_items
                  );
            AuditTrail::addAuditEntry("QuotationItem", json_encode($audit_action));

            $i++;
        }


        // update time line entry
        $data_customer_time_line_items = array(
            'customer_id' => $customer_id,
            'time_line_item_source' => "Quotation",
            'time_line_item_source_id' => $quotation->id,
            'datetime' => $datetime
        );

        CustomerTimeLineItem::create($data_customer_time_line_items);

        return $quotation;

    }

    public static function generateQuotationPDF($quotation_id, $output_type){

        $quotation = Quotation::find($quotation_id);

        $html = View::make('quotations._partials.quotations_pdf', compact('quotation'))->render();

        // for testing just return html
        //return $html;

        // generate file name

        $file_name = 'quotation_' . $quotation_id . '.pdf';

        $content = PDFGenerator::generatePDF($html);

        $file_save_data = GCSFileHandler::writeFile($content, $file_name);

        $file_save_data['file_name'] = $file_name;

        return $file_save_data;
    }

    public static function checkIfAuthorizedToEditQuotation(){

        if(Session::get('user-level') == 'marketing') {
            return false;
        } else {
            return true;
        }

    }

    public static function checkIfAuthorizedToSendQuotation($quotation){

        $lead_assigned_to = $quotation->quoted_by;
        $logged_user_id = Session::get('user-id');

        syslog(LOG_INFO, "lead_assigned_to -- $lead_assigned_to");
        syslog(LOG_INFO, "logged_user_id -- $logged_user_id");

        if($logged_user_id == $lead_assigned_to){
            return true;
        }

        if(Session::get('user-level') == 'sales') {
            if(Session::get('user-id') != $lead_assigned_to){
                return false;
            }
        }

        elseif(Session::get('user-level') == 'marketing') {
            return false;
        } else {
            return true;
        }
    }

    public static function salesValueByCampaignData($organization_id,$from_date,$to_date,$sales_filter_query){

        $query_sales_value_by_campaigns = "SELECT leads.auto_tagged_campaign AS campaign, 
            SUM(quotations.net_total) AS sales_value 
            FROM quotations
            LEFT JOIN leads 
            ON leads.id = quotations.lead_id
            WHERE quotations.quotation_status = 'Closed'
            AND quotations.organization_id = '$organization_id'
            AND quotations.quotation_closed_at >= '$from_date' 
            AND quotations.quotation_closed_at <= '$to_date' 
            $sales_filter_query
            GROUP BY campaign
            ORDER BY campaign
            ";

        syslog(LOG_INFO, '$query_sales_value_by_campaigns -- '. $query_sales_value_by_campaigns);

        $sales_value_by_campaigns = DB::select($query_sales_value_by_campaigns);

        return $sales_value_by_campaigns;

    }

    public static function salesVolumeByCampaignData($organization_id,$from_date,$to_date,$sales_filter_query){

        $query_sales_volume_by_campaigns = "SELECT leads.auto_tagged_campaign AS campaign, 
            count(quotations.id) AS sales_volume 
            FROM quotations
            LEFT JOIN leads 
            ON leads.id = quotations.lead_id
            WHERE quotations.quotation_status = 'Closed'
            AND quotations.organization_id = '$organization_id'
            AND quotations.quotation_closed_at >= '$from_date' 
            AND quotations.quotation_closed_at <= '$to_date' 
            $sales_filter_query
            GROUP BY campaign
            ORDER BY campaign
            ";

        syslog(LOG_INFO, '$query_sales_volume_by_campaigns -- '. $query_sales_volume_by_campaigns);

        $sales_volume_by_campaigns = DB::select($query_sales_volume_by_campaigns);

        return $sales_volume_by_campaigns;

    }

    public static function salesValueBySalesPersonData($organization_id,$from_date,$to_date){

        $query_sales_value_by_sales_persons = "SELECT 
                                            CONCAT(employees.given_name, ' ', employees.surname) AS sales_person, 
                                            SUM(quotations.net_total) AS sales_value 
                                            FROM quotations
                                            LEFT JOIN employees 
                                            ON employees.id = quotations.quotation_closed_by
                                            WHERE quotations.quotation_status = 'Closed'
                                            AND quotations.organization_id = '$organization_id'
                                            AND quotations.quotation_closed_at >= '$from_date' 
                                            AND quotations.quotation_closed_at <= '$to_date'
                                            GROUP BY sales_person
                                            ORDER BY sales_person
                                            ";

        syslog(LOG_INFO, '$query_sales_value_by_sales_persons -- '. $query_sales_value_by_sales_persons);

        $sales_value_by_sales_persons = DB::select($query_sales_value_by_sales_persons);

        return $sales_value_by_sales_persons;

    }

    public static function salesVolumeBySalesPersonData($organization_id,$from_date,$to_date){

        $query_sales_volume_by_sales_persons = "SELECT 
                                            CONCAT(employees.given_name, ' ', employees.surname) AS sales_person, 
                                            count(quotations.id) AS sales_volume 
                                            FROM quotations
                                            LEFT JOIN employees 
                                            ON employees.id = quotations.quotation_closed_by
                                            WHERE quotations.quotation_status = 'Closed'
                                            AND quotations.organization_id = '$organization_id'
                                            AND quotations.quotation_closed_at >= '$from_date' 
                                            AND quotations.quotation_closed_at <= '$to_date'
                                            GROUP BY sales_person
                                            ORDER BY sales_person
                                            ";

        syslog(LOG_INFO, '$query_sales_volume_by_sales_persons -- '. $query_sales_volume_by_sales_persons);

        $sales_volume_by_sales_persons = DB::select($query_sales_volume_by_sales_persons);

        return $sales_volume_by_sales_persons;

    }






}