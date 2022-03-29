<?php

use google\appengine\api\cloud_storage\CloudStorageTools;

class DataExportRequestController extends BaseController
{

    public function export(){

        $type_of_data = Input::get('data_type');

        $excel = Excel::create('data_export_' . $type_of_data, function ($excel) {

            $excel->sheet('Sheet', function ($sheet) {

                $type_of_data = Input::get('data_type');
                $organization_id = Session::get('user-organization-id');

                if ($type_of_data == 'customer') {

                    $customers = Customer::where('organization_id',$organization_id)->get();
                    $sheet->loadView('data_exports._partials.customer_csv', ['customers' => $customers]);

                }
            });
        });

        // save the generated file to google cloud storage
        $report_file_urls = GCSFileHandler::saveExportExcel($excel);
        return $report_file_urls;

    }

    public function saveDataExportRequest(){

        date_default_timezone_set("Asia/Singapore");
        $generated_time = date('Y-m-d H:i:s');
        $type_of_data = Input::get('data_type');
        $export_parameters['data_type'] = $type_of_data;
        $export_parameters_names['data_type'] = DataExportRequest::$type_of_data[$type_of_data];
        $filter_date_range = Input::get('dashboard_filter_date_range');
        $filter_from_date = Input::get('dashboard_filter_from_date');
        $filter_to_date = Input::get('dashboard_filter_to_date');

        if($type_of_data == 'customer'){

            $export_parameters_names['date_range'] = $filter_date_range;
            $export_parameters_names['from_date'] = $filter_from_date;
            $export_parameters_names['to_date'] = $filter_to_date;
            $export_parameters_names['export_parameter'] = Input::get('customer_export_filter_export_parameter');
            $export_parameters_names['account_owner'] = Input::get('customer_export_filter_account_owner_name');
            $export_parameters_names['industry'] = Input::get('customer_export_filter_industry_name');
            $export_parameters_names['country'] = Input::get('customer_export_filter_country_name');
            $export_parameters_names['tags'] = Input::get('customer_export_filter_customer_tags_name');

            $export_parameters['customer_export_filter_date_range'] = $filter_date_range;
            $export_parameters['customer_export_filter_from_date'] = $filter_from_date;
            $export_parameters['customer_export_filter_to_date'] = $filter_to_date;
            $export_parameters['customer_export_filter_export_parameter'] = Input::get('customer_export_filter_export_parameter');
            $export_parameters['customer_export_filter_account_owner'] = Input::get('customer_export_filter_account_owner');
            $export_parameters['customer_export_filter_industry'] = Input::get('customer_export_filter_industry');
            $export_parameters['customer_export_filter_country'] = Input::get('customer_export_filter_country');
            $export_parameters['customer_export_filter_customer_tags'] = Input::get('customer_export_filter_customer_tags');

        }elseif($type_of_data == 'lead'){

            $export_parameters_names['export_parameter'] = Input::get('lead_export_filter_export_parameter');
            $export_parameters_names['customer'] = Input::get('lead_export_filter_customer_name');
            $export_parameters_names['campaign'] = Input::get('lead_export_filter_campaign_name');
            $export_parameters_names['assigned_to'] = Input::get('lead_export_filter_assigned_to_name');
            $export_parameters_names['status_updated_by'] = Input::get('lead_export_filter_status_updated_by_name');
            $export_parameters_names['lead_rating_last_updated_by'] = Input::get('lead_export_filter_lead_rating_last_updated_by_name');
            $export_parameters_names['lead_rating'] = Input::get('lead_export_filter_lead_rating_name');

            $export_parameters['lead_export_filter_export_parameter'] = Input::get('lead_export_filter_export_parameter');
            $export_parameters['lead_export_filter_customer'] = Input::get('lead_export_filter_customer');
            $export_parameters['lead_export_filter_campaign'] = Input::get('lead_export_filter_campaign');
            $export_parameters['lead_export_filter_assigned_to'] = Input::get('lead_export_filter_assigned_to');
            $export_parameters['lead_export_filter_status_updated_by'] = Input::get('lead_export_filter_status_updated_by');
            $export_parameters['lead_export_filter_lead_rating_last_updated_by'] = Input::get('lead_export_filter_lead_rating_last_updated_by');
            $export_parameters['lead_export_filter_lead_rating'] = Input::get('lead_export_filter_lead_rating');
        }

        $data_export_request = new DataExportRequest();
        $export_data = $data_export_request->generateExcel($export_parameters);
        $download_link = $export_data['public_url'];
        $no_of_record = $export_data['no_of_record'];

        $organization_id = Session::get('user-organization-id');
        $user_id = Session::get('user-id');

        $data = array(
            'organization_id' => $organization_id,
            'export_type' => $type_of_data,
            'status' => 'pending',
            'export_parameters' => json_encode($export_parameters_names),
            'requested_by' => $user_id,
            'no_of_records' => $no_of_record,
            'generated_date' => $generated_time,
            'download_link' => $download_link
        );

        DataExportRequest::create($data);

    }

    public function ajaxLoadDataExportList(){

        $organization_id = Session::get('user-organization-id');
        $build_query = DataExportRequest::where('organization_id',$organization_id);

        $data_export_requests = $build_query->orderBy('id','desc')->paginate(5);

        return View::make('data_exports._ajax_partials.data_export_requests_list', compact('data_export_requests'))->render();
    }
}

