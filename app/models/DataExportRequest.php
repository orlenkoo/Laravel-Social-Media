<?php

class DataExportRequest extends \Eloquent
{
    private $no_of_records = 0;

    protected $fillable = [
        'organization_id',
        'export_type',
        'generated_date',
        'status',
        'export_parameters',
        'requested_by',
        'no_of_records',
        'download_link'
    ];

    public static $status = array(
        'pending' => 'Pending',
        'generated' => 'Generated',
        'failed' => 'Failed'
    );

    public static $type_of_data = array(
        'customer' => 'Customer',
        'lead' => 'Lead'
    );

    public static $customer_export_parameters = array(
        'All' => 'All',
        'Created At' => 'Created At',
        'Account Owner' => 'Account Owner',
        'Industry' => 'Industry',
        'Country' => 'Country',
        'Customer tags' => 'Customer tags'
    );

    public static $lead_export_parameters = array(
        'Customer' => 'Customer',
        'Campaign' => 'Campaign',
        'Assigned To' => 'Assigned To',
        'Status Updated By' => 'Status Updated By',
        'Lead Rating Last Updated By' => 'Lead Rating Last Updated By',
        'Lead Rating' => 'Lead Rating'
    );

    public function requestedBy()
    {
        return $this->belongsTo('Employee', 'requested_by');
    }

    public function generateExcel($export_parameters){

        $this->no_of_records = 0;

        $excel = Excel::create('data_export_' . $export_parameters['data_type'], function ($excel) use ($export_parameters) {

            $excel->sheet('Sheet', function ($sheet)  use ($export_parameters) {

                $organization_id = Session::get('user-organization-id');

                if ($export_parameters['data_type'] == 'customer') {

                    $customers = Customer::where('organization_id',$organization_id);

                    if($export_parameters['customer_export_filter_export_parameter'] == 'All'){

                    }elseif($export_parameters['customer_export_filter_export_parameter'] == 'Created At'){

                        $date_range = DateFilterUtilities::getDateRange(
                            $export_parameters['customer_export_filter_date_range'],
                            $export_parameters['customer_export_filter_from_date'],
                            $export_parameters['customer_export_filter_to_date']
                        );

                        $from_date = $date_range['from_date']. ' 00:00:00';
                        $to_date = $date_range['to_date']. ' 23:59:59';

                        $customers->whereBetween('created_at', array($from_date, $to_date));

                    }elseif($export_parameters['customer_export_filter_export_parameter'] == 'Account Owner'){

                        if($export_parameters['customer_export_filter_account_owner'] != null || $export_parameters['customer_export_filter_account_owner']  != ''){
                            $customers->whereIn('account_owner_id',$export_parameters['customer_export_filter_account_owner']);
                        }

                    }elseif($export_parameters['customer_export_filter_export_parameter'] == 'Industry'){

                        if($export_parameters['customer_export_filter_industry'] != null || $export_parameters['customer_export_filter_industry']  != ''){
                            $customers->where('industry_id',$export_parameters['customer_export_filter_industry']);
                        }

                    }elseif($export_parameters['customer_export_filter_export_parameter'] == 'Country'){

                        if($export_parameters['customer_export_filter_country'] != null || $export_parameters['customer_export_filter_country']  != ''){
                            $customers->where('country_id',$export_parameters['customer_export_filter_country']);
                        }

                    }elseif($export_parameters['customer_export_filter_export_parameter'] == 'Customer tags'){

                        if($export_parameters['customer_export_filter_customer_tags'] != null || $export_parameters['customer_export_filter_customer_tags']  != ''){
                            $customer_ids = CustomerTagAssignment::whereIn('customer_tag_id',$export_parameters['customer_export_filter_customer_tags'])
                                ->lists('customer_id');

                            $customers->whereIn('id',$customer_ids);
                        }

                    }

                    $customers_count = clone $customers;
                    $customers =  $customers->get();

                    $this->no_of_records = $customers_count->count();

                    $sheet->loadView('data_exports._partials.customer_csv', ['customers' => $customers]);

                }elseif($export_parameters['data_type'] == 'lead'){

                    syslog(LOG_INFO,json_encode($export_parameters));

                    $leads = Lead::where('organization_id',$organization_id);

                    if($export_parameters['lead_export_filter_export_parameter'] == 'Customer'){
                        if($export_parameters['lead_export_filter_customer'] != null || $export_parameters['lead_export_filter_customer']  != ''){
                            $leads->where('customer_id',$export_parameters['lead_export_filter_customer']);
                        }
                    }elseif($export_parameters['lead_export_filter_export_parameter'] == 'Campaign'){
                        if($export_parameters['lead_export_filter_campaign'] != null || $export_parameters['lead_export_filter_campaign']  != ''){
                            $leads->where('campaign_id',$export_parameters['lead_export_filter_campaign']);
                        }
                    }elseif($export_parameters['lead_export_filter_export_parameter'] == 'Assigned To'){
                        if($export_parameters['lead_export_filter_assigned_to'] != null || $export_parameters['lead_export_filter_assigned_to']  != ''){
                            $leads->where('assigned_to',$export_parameters['lead_export_filter_assigned_to']);
                        }
                    }elseif($export_parameters['lead_export_filter_export_parameter'] == 'Status Updated By'){
                        if($export_parameters['lead_export_filter_status_updated_by'] != null || $export_parameters['lead_export_filter_status_updated_by']  != ''){
                            $leads->where('status_updated_by',$export_parameters['lead_export_filter_status_updated_by']);
                        }
                    }elseif($export_parameters['lead_export_filter_export_parameter'] == 'Lead Rating Last Updated By'){
                        if($export_parameters['lead_export_filter_lead_rating_last_updated_by'] != null || $export_parameters['lead_export_filter_lead_rating_last_updated_by']  != ''){
                            $leads->where('lead_rating_updated_by',$export_parameters['lead_export_filter_lead_rating_last_updated_by']);
                        }
                    }elseif($export_parameters['lead_export_filter_export_parameter'] == 'Lead Rating'){
                        if($export_parameters['lead_export_filter_lead_rating'] != null || $export_parameters['lead_export_filter_lead_rating']  != ''){
                            $leads->where('lead_rating',$export_parameters['lead_export_filter_lead_rating']);
                        }
                    }

                    $lead_count = clone $leads;
                    $leads = $leads->get();

                    $this->no_of_records = $lead_count->count();

                    $sheet->loadView('data_exports._partials.lead_csv', ['leads' => $leads]);
                }
            });
        });

        // save the generated file to google cloud storage
        $report_file_urls = GCSFileHandler::saveExportExcel($excel);
        $export_data = $report_file_urls;
        $export_data['no_of_record'] = $this->no_of_records;

        return $export_data;
    }

}