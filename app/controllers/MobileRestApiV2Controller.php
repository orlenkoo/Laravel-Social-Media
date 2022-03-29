<?php

/**
 * Created by PhpStorm.
 * Event 360 Mobile Api
 * User: Roshane De Silva
 * Date: 07/09/2016
 * Time: 07:10
 */
class MobileRestApiV2Controller extends BaseController
{

    private $return_json = array(
        'status' => false,
        'message' => "",
        'data' => null
    );

    // function to first check if valid login
    public static function checkEmployeeLoginIsValid($employee) {

    }

    // login to the mobile app using username, password
    public function login()
    {

        try {
            $email = Input::get('email');
            $password = Input::get('password');

            $employee = Employee::where('email', '=', $email)->where('status', 1)->first();
            if (!is_object($employee)) {
                $this->return_json['message'] = 'User cannot be found.';
                return json_encode($this->return_json);
            }


            // get project ids list
            $project_ids = Organization::getProjectIds($employee->organization_id);

            if (Hash::check($password, $employee['password'])) {

                $token = Employee::createLoginToken($email);

                // update token on user table
                $data_employee = array('token' => $token);
                $employee->update($data_employee);

                $data = array(
                    'token' => $token,
                    'employee_data' => array(
                        'employee_id' => $employee->id,
                        'given_name' => $employee->given_name,
                        'surname' => $employee->surname,
                        'email' => $employee->email,
                        'contact_no' => $employee->contact_no,
                        'profile_image_file_url' => $employee->profile_image_file_url,
                        'user_level' => $employee->user_level,
                        'organization_id' => $employee->organization_id,
                        'organization_name' => $employee->organization->organization,
                        'project_ids' => $project_ids,
                        'lead_ratings' => array_keys(Lead::$lead_ratings),
                        'lead_sources' => Lead::$lead_sources,
                        'quotation_status' => array_keys(Quotation::$quotation_status),
                    )
                );

                $this->return_json['status'] = true;
                $this->return_json['data'] = $data;
                return json_encode($this->return_json);
            } else {
                $this->return_json['message'] = 'Wrong password.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            $this->return_json['message'] = 'Error: '. $e->getMessage();
            syslog(LOG_ERR, $e->getTraceAsString());
            return json_encode($this->return_json);
        }

    }

    public function forgotPassword()
    {
        try{
            $email = Input::get('email');

            // check if valid email address
            $employee = Employee::where('email', '=', $email)->where('status', 1)->first();
            if (!is_object($employee)) {
                $this->return_json['message'] = 'User cannot be found.';
                return json_encode($this->return_json);
            }



            // send password reset email

            Employee::sendPasswordresetEmail($email);

            // return message
            $this->return_json['status'] = true;
            $this->return_json['message'] = 'Password rest email has been sent. Please check.';
            return json_encode($this->return_json);
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getOrganizationEmployeesList()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {
                    $employees_list = Employee::where('organization_id', $employee->organization_id)->where('status', 1)->select(DB::raw('concat (given_name," ",surname) as full_name,id'))->get();

                    if(count($employees_list) == 0) {
                        $this->return_json['message'] = 'No employees found.';
                        return json_encode($this->return_json);
                    }

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('employees_list' => $employees_list);
                    return json_encode($this->return_json);
                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getLeadsList()
    {
        try {
            $token = Request::header('Authorization');

            $page = Input::get('page');

            $employee_id = Input::get('employee_id');
            $search_text = Input::get('search_text');
            $filter_lead_source = Input::get('filter_lead_source');
            $filter_lead_rating = Input::get('filter_lead_rating');

            $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
            $filter_from_date = Input::get('filter_from_date');
            $filter_to_date = Input::get('filter_to_date');

            $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

            $from_date = $date_range['from_date']. ' 00:00:00';
            $to_date = $date_range['to_date']. ' 23:59:59';

            $leads_list = null;

            $employee = Employee::find($employee_id);

            syslog(LOG_INFO, '$employee_id -- '.$employee_id);
            syslog(LOG_INFO, '$filter_date_range -- '.$filter_date_range);
            syslog(LOG_INFO, '$from_date -- '.$from_date);
            syslog(LOG_INFO, '$to_date -- '.$to_date);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $build_query = Lead::where('organization_id', $employee->organization_id);

                    if($filter_lead_source != '') {
                        $build_query->where('lead_source', $filter_lead_source);
                    }


                    if($search_text != '') {
                        $customer_ids = Customer::where('customer_name', 'LIKE', '%'. $search_text .'%')->lists('id');

                        $build_query->whereIn('customer_id', $customer_ids);
                    }

                    if($filter_lead_rating != '') {
                        $build_query->where('lead_rating', $filter_lead_rating);
                    }

                    $from_date = $date_range['from_date']. ' 00:00:00';
                    $to_date = $date_range['to_date']. ' 23:59:59';

                    // if sales only load own assigned leads
                    if($employee->user_level == 'sales') {
                        $build_query->where('assigned_to', $employee->id);
                    }

                    $build_query->whereBetween('datetime' , array($from_date, $to_date));

                    if($employee->user_level == 'sales') {
                        $build_query->orderBy('last_assignment_datetime', 'desc');
                    } else {
                        $build_query->orderBy('datetime', 'desc');
                    }


                    $leads = $build_query->paginate(10);


                    if(count($leads) == 0) {
                        $this->return_json['message'] = 'No leads found.';
                        return json_encode($this->return_json);
                    }

                    foreach($leads as $lead) {
                        $customer = null;
                        if(is_object($lead->customer)) {

                            $customer_object =$lead->customer;
                            $primary_contact = Contact::where('customer_id', $customer_object->id)->where('primary_contact', 1)->first();
                            if(is_object($primary_contact)) {
                                $customer = array(
                                    'customer_id' => $lead->customer->id,
                                    'customer_name' => $lead->customer->customer_name,
                                    'primary_contact_email' => $primary_contact->email,
                                );
                            }else{
                                $customer = array(
                                    'customer_id' => $lead->customer->id,
                                    'customer_name' => $lead->customer->customer_name,
                                    'primary_contact_email' => ''
                                );
                            }

                        }

                        $leads_list[] = array(
                            'lead_id' => $lead->id,
                            'date_time' => $lead->datetime,
                            'lead_source' => array_key_exists($lead->lead_source, Lead::$lead_sources)? Lead::$lead_sources[$lead->lead_source]: 'NA',
                            'lead_rating' => $lead->lead_rating,
                            'customer' => $customer,
                        );
                    }

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('leads_list' => $leads_list);
                    return json_encode($this->return_json);
                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getCampaignsList()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {
                    $campaigns_list = Campaign::where('organization_id', $employee->organization_id)->where('status', 1)->select('id', 'campaign_name')->get();

                    if(count($campaigns_list) == 0) {
                        $this->return_json['message'] = 'No campaigns have been setup.';
                        return json_encode($this->return_json);
                    }

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('campaigns_list' => $campaigns_list);
                    return json_encode($this->return_json);
                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getLeadDetails()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $lead_id = Input::get('lead_id');

            $employee = Employee::find($employee_id);




            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $lead = Lead::find($lead_id);

                    $lead_details = null;

                    if(is_object($lead)) {
                        $lead_customer = null;
                        $customer = $lead->customer;

                        if(is_object($customer)) {

                            $primary_contact = Contact::where('customer_id', $customer->id)->where('primary_contact', 1)->first();
                            $primary_contact_details = null;
                            if(is_object($primary_contact)) {
                                $primary_contact_details = array(
                                    'full_name' => $primary_contact->getContactFullName(),
                                    'email' => $primary_contact->email,
                                    'phone_number' => $primary_contact->phone_number,
                                );
                            }

                            $lead_customer = array(
                                'customer_id' => $customer->id,
                                'customer_name' => $customer->customer_name,
                                'full_address' => $customer->getAddress(),
                                'address_line_1' => $customer->address_line_1,
                                'address_line_2' => $customer->address_line_2,
                                'city' => $customer->city,
                                'postal_code' => $customer->postal_code,
                                'state' => $customer->state,
                                'country' => is_object($customer->country)? $customer->country->country: "",
                                'phone_number' => $customer->phone_number,
                                'fax_number' => $customer->fax_number,
                                'website' => $customer->website,
                                'primary_contact' => $primary_contact_details,

                            );
                        }

                        $lead_details = array(
                            'lead_customer' => $lead_customer,
                            'lead_campaign_id' => $lead->campaign_id,
                            'lead_assigned_to' => $lead->assigned_to
                        );

                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('lead_details' => $lead_details);
                        return json_encode($this->return_json);

                    } else {
                        $this->return_json['message'] = 'Lead does not exist.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }


    }

    public function updateLeadDetails()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $customer_id = Input::get('customer_id');

            $address_line_1 = Input::get('address_line_1');
            $address_line_2 = Input::get('address_line_2');
            $city = Input::get('city');
            $postal_code = Input::get('postal_code');
            $state = Input::get('state');
            $website = Input::get('website');
            $phone_number = Input::get('phone_number');
            $fax_number = Input::get('fax_number');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $customer = Customer::find($customer_id);

                    if(is_object($customer)) {
                        $data_customer = array(
                            'address_line_1' => $address_line_1,
                            'address_line_2' => $address_line_2,
                            'city' => $city,
                            'postal_code' => $postal_code,
                            'state' => $state,
                            'website' => $website,
                            'phone_number' => $phone_number,
                            'fax_number' => $fax_number,
                        );
                        $customer->update($data_customer);

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Lead details updated successfully.';
                        return json_encode($this->return_json);

                    } else {
                        $this->return_json['message'] = 'Customer does not exist.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function updateLeadCampaign()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $lead_id = Input::get('lead_id');
            $campaign_id = Input::get('campaign_id');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $lead = Lead::find($lead_id);

                    if(is_object($lead)) {
                        $lead->update(array('campaign_id' => $campaign_id));

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Lead campaign updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Lead does not exist.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function updateLeadAssignedTo()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $lead_id = Input::get('lead_id');
            $assigned_to = Input::get('assigned_to');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $lead = Lead::find($lead_id);

                    if(is_object($lead)) {
                        $lead->update(array('assigned_to' => $assigned_to));

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Lead assigned to updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Lead does not exist.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function updateLeadRating()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $lead_id = Input::get('lead_id');
            $lead_rating = Input::get('lead_rating');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $lead = Lead::find($lead_id);

                    if(is_object($lead)) {
                        $lead->update(array('lead_rating' => $lead_rating));

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Lead rating updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Lead does not exist.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getLeadNotesList()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $page = Input::get('page');
            $lead_id = Input::get('lead_id');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $lead_notes = LeadNote::where('lead_id', $lead_id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

                    if(count($lead_notes) > 0) {

                        $lead_notes_list= array();

                        foreach($lead_notes as $lead_note) {
                            $lead_notes_list[] = array(
                                'note' => $lead_note->note,
                                'added_by' => is_object($lead_note->leadNoteCreatedBy)? $lead_note->leadNoteCreatedBy->getEmployeeFullName(): "NA",
                                'added_on' => $lead_note->datetime
                            );
                        }

                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('lead_notes_list' => $lead_notes_list);
                        return json_encode($this->return_json);
                    }

                    $this->return_json['message'] = 'No lead notes found for this lead.';
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function saveLeadNote()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $lead_id = Input::get('lead_id');
            $note = Input::get('note');


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    LeadNote::createLeadNote($lead_id, $note, $employee_id);

                    $this->return_json['status'] = true;
                    $this->return_json['message'] = 'Lead note saved successfully.';
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getCustomerContactsList()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $page = Input::get('page');
            $customer_id = Input::get('customer_id');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $contacts = Contact::where('customer_id', $customer_id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

                    if(count($contacts) > 0) {

                        $contacts_list = array();

                        foreach($contacts as $contact) {
                            $contacts_list[] = array(
                                'contact_id' => $contact->id,
                                'given_name' => $contact->given_name,
                                'surname' => $contact->surname,
                                'designation' => $contact->designation,
                                'phone_number' => $contact->phone_number,
                                'email' => $contact->email,
                                'primary_contact' => $contact->primary_contact,
                            );
                        }

                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('contacts_list' => $contacts_list);
                        return json_encode($this->return_json);
                    }

                    $this->return_json['message'] = 'No contacts found for this customer.';
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function updateCustomerContactDetails()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $contact_id = Input::get('contact_id');
            $given_name = Input::get('given_name');
            $surname = Input::get('surname');
            $designation = Input::get('designation');
            $phone_number = Input::get('phone_number');
            $email = Input::get('email');


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $contact = Contact::find($contact_id);

                    if(is_object($contact)) {

                        $data_contact = array(
                            'given_name' => $given_name,
                            'surname' => $surname,
                            'designation' => $designation,
                            'phone_number' => $phone_number,
                            'email' => $email,
                        );

                        $contact->update($data_contact);


                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Contact details saved successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Contact does not exist.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function createNewCustomerContact()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $customer_id = Input::get('customer_id');
            $given_name = Input::get('given_name');
            $surname = Input::get('surname');
            $designation = Input::get('designation');
            $phone_number = Input::get('phone_number');
            $email = Input::get('email');


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $customer = Customer::find($customer_id);

                    if(is_object($customer)) {

                        $data_contact = array(
                            'customer_id' => $customer->id,
                            'given_name' => $given_name,
                            'surname' => $surname,
                            'designation' => $designation,
                            'phone_number' => $phone_number,
                            'email' => $email,
                            'primary_contact' => 0,
                        );

                        Contact::create($data_contact);


                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Contact details saved successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Customer does not exist.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function updateCustomerPrimaryContact()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $contact_id = Input::get('contact_id');


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $contact = Contact::find($contact_id);

                    if(is_object($contact)) {

                        // set all contact for this customer as secondary
                        DB::table('contacts')
                            ->where('customer_id', $contact->customer_id)
                            ->update(array('primary_contact' => 0));

                        // update this particular contact as primary

                        $data_contact = array(
                            'primary_contact' => 1,
                        );

                        $contact->update($data_contact);


                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Customer primary contact updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Contact does not exist.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getLeadMetaDetails()
    {
        try{
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $lead_id = Input::get('lead_id');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $lead = Lead::find($lead_id);

                    if(is_object($lead)) {

                        $lead_meta_details = "";
                        $call_recording_url = "";

                        if($lead->lead_source == 'web360_enquiries') {

                            $enquiry_details = $lead->web360Enquiry->enquiry_details;
                            if(is_array($enquiry_details)) {
                                $enquiry_details = implode(' ||', $enquiry_details);
                            } else {
                                $enquiry_details = json_decode($enquiry_details);
                            }

                            if (is_object($enquiry_details)) {
                                foreach($enquiry_details as $key => $value) {
                                    $key = ucwords(str_replace('_', ' ', $key));
                                    $value = is_array($value)? implode(",", $value) : $value;
                                    $lead_meta_details .=   "$key: $value ||";
                                }
                            } else {
                                $lead_meta_details .= $enquiry_details . " ||";
                            }


                        } else if ($lead->lead_source == 'event360_calls') {
                            $event360_call = $lead->event360Call;

                            if(is_object($event360_call)) {
                                $call_recording_url = $event360_call->recording_url;

                                $lead_meta_details .= "Incoming Call Number: ". $event360_call->incoming_call_number ." ||";
                                $lead_meta_details .= "Tracking Number: ". $event360_call->number1300 ." ||";
                                $lead_meta_details .= "Termination Number: ". $event360_call->transferred_number ." ||";
                                $lead_meta_details .= "Date & Time: ". $event360_call->time ." ||";
                                $lead_meta_details .= "Result: ". $event360_call->result ." ||";
                                $lead_meta_details .= "Vendor Name / Source: ". $event360_call->dealer_name ." ||";
                                $lead_meta_details .= "Call Duration: ". $event360_call->duration ." ||";
                                $lead_meta_details .= "Total Duration: ". $event360_call->durationof1300 ." ||";
                            } else {
                                $lead_meta_details .= " ||";
                            }


                        } else if ($lead->lead_source == 'novocall_leads') {
                            $enquiry_details = is_object($lead->novocallLead)? get_object_vars(json_decode($lead->novocallLead->lead_details)): '';

                            foreach($enquiry_details as $key => $value) {
                                $key = ucwords(str_replace('_', ' ', $key));
                                $value = $value;
                                $lead_meta_details .=   "$key: $value ||";
                            }
                        }

                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('lead_meta_details' => $lead_meta_details, 'call_recording_url' => $call_recording_url);
                        return json_encode($this->return_json);
                    }

                    $this->return_json['message'] = 'Lead does not exist.';
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        }  catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getCustomersListByName()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $search_text = Input::get('search_text');

            $customers_list = null;

            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $build_query = Customer::where('organization_id', $employee->organization_id);

                    if($search_text != '') {
                        $customers = $build_query->where('customer_name', 'LIKE', '%'. $search_text .'%')->select('id', 'customer_name')->get();
                    }

                    if(count($customers) == 0) {
                        $this->return_json['message'] = 'No customers found.';
                        return json_encode($this->return_json);
                    }

                    syslog(LOG_INFO, '$search_text -- '. $search_text);
                    syslog(LOG_INFO, '$employee_id -- '.$employee_id);
                    syslog(LOG_INFO, '$employee->organization_id -- '.$employee->organization_id);
                    syslog(LOG_INFO, 'customers -- '. json_encode($customers));

                    foreach($customers as $customer) {

                        if(is_object($customer)) {
                            $customers_list[] = array(
                                'customer_id' => $customer->id,
                                'customer_name' => $customer->customer_name
                            );
                        }
                    }

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('customers_list' => $customers_list);
                    return json_encode($this->return_json);
                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function createAndAssignNewCustomerForLead()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $lead_id = Input::get('lead_id');
            $customer_name = Input::get('customer_name');


            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $lead = Lead::find($lead_id);

                    if(is_object($lead)) {
                        $data_customer = array(
                            'organization_id' => $employee->organization_id,
                            'account_owner_id' => $employee->id,
                            'customer_name' => $customer_name,
                        );

                        $customer = Customer::create($data_customer);

                        // update lead
                        $lead->update(array('customer_id' => $customer->id));

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Customer created and assigned to lead successfully.';
                        $this->return_json['data'] = array('customer_id' => $customer->id);
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Lead does not exist.';
                        return json_encode($this->return_json);
                    }



                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function updateCustomerForLead()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $lead_id = Input::get('lead_id');
            $customer_id = Input::get('customer_id');


            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $lead = Lead::find($lead_id);

                    if(is_object($lead)) {

                        $customer = Customer::find($customer_id);

                        if(is_object($customer)) {
                            // update lead
                            $lead->update(array('customer_id' => $customer->id));

                            $this->return_json['status'] = true;
                            $this->return_json['message'] = 'Customer updated for the lead successfully.';
                            return json_encode($this->return_json);
                        } else {
                            $this->return_json['message'] = 'Customer does not exist.';
                            return json_encode($this->return_json);
                        }


                    } else {
                        $this->return_json['message'] = 'Lead does not exist.';
                        return json_encode($this->return_json);
                    }



                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function createNewLead()
    {
        try {
            date_default_timezone_set("Asia/Singapore");
            $datetime = date('Y-m-d H:i:s');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $customer_id = Input::get('customer_id');
            $customer_name = Input::get('customer_name');
            $given_name = Input::get('given_name');
            $surname = Input::get('surname');
            $phone_number = Input::get('phone_number');
            $email = Input::get('email');
            $designation = Input::get('designation');
            $website = Input::get('website');
            $lead_note = Input::get('lead_note');

            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    if($customer_id != null && $customer_name != null) {
                        $customer = Customer::find($customer_id);
                        $data_customer = array(
                            'customer_name'=> $customer_name,
                            'website'=> $website,
                        );
                        $customer->update($data_customer);
                    } else if($customer_id != null) {
                        $customer = Customer::find($customer_id);
                        $data_customer = array(
                            'website'=> $website,
                        );

                        $customer->update($data_customer);
                    } else if ($customer_name != null) {
                        $data_customer = array(
                            'organization_id' => $employee->organization_id,
                            'account_owner_id' => $employee->id,
                            'customer_name' => $customer_name,
                            'website'=> $website,
                        );
                        $customer = Customer::create($data_customer);
                    }


                    if(is_object($customer)) {


                        if($given_name != '' || $surname != '' || $phone_number != '' || $email != '') {
                            $data_contact = array(
                                'customer_id' => $customer->id,
                                'salutation' => '',
                                'given_name' => $given_name,
                                'surname' => $surname,
                                'designation' => $designation,
                                'phone_number' => $phone_number,
                                'other_phone_number' => "",
                                'mobile_number' => "",
                                'email' => $email,
                                'contact_status' => "Activated", // Activated, Left, Transferred
                                'primary_contact' => 1
                            );

                            Contact::create($data_contact);
                        }


                        // create lead

                        $data_lead = array(
                            'organization_id' => $employee->organization_id,
                            'customer_id' => $customer->id,
                            'campaign_id' => null,
                            'assigned_to' => $employee->id,
                            'last_assignment_datetime' => $datetime,
                            'datetime' => $datetime,
                            'lead_source' => 'direct',
                            'lead_source_id' => null,
                            'lead_rating' => 'Raw Lead',
                            'lead_rating_updated_datetime' => $datetime,
                            'lead_rating_updated_by' => $employee->id,
                        );

                        $lead = Lead::createLead($data_lead);

                        if($lead_note != '') {
                            // create lead note
                            $data_lead_note = array(
                                'lead_id' => $lead->id,
                                'note' => $lead_note,
                                'datetime' => $datetime,
                                'created_by' => $employee->id,
                            );

                            LeadNote::create($data_lead_note);
                        }


                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Customer created and assigned to lead successfully.';
                        $this->return_json['data'] = array('lead_id' => $lead->id);
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Customer does not exist.';
                        return json_encode($this->return_json);
                    }



                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getCustomerTimeLine()
    {
        try {
            date_default_timezone_set("Asia/Singapore");
            $datetime = date('Y-m-d H:i:s');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $customer_id = Input::get('customer_id');

            $employee = Employee::find($employee_id);

            $page = Input::get('page');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $customer_time_line_items_list = array();

                    $customer_time_line_items = CustomerTimeLineItem::where('customer_id', $customer_id)
                        ->orderby('datetime', 'desc')
                        ->paginate(5);

                    foreach($customer_time_line_items as $customer_time_line_item) {

                        $activity_type = $customer_time_line_item->time_line_item_source;
                        $activity_object = array();

                        if($activity_type == 'Call') {
                            $call = $customer_time_line_item->call;
                            if(is_object($call)) {
                                $activity_object = array(
                                    'call_id' => $call->id,
                                    'call_date' => $call->call_date,
                                    'agenda' => $call->agenda,
                                    'summary' => $call->summary,
                                    'scheduled_start_time' => $call->scheduled_start_time,
                                    'scheduled_end_time' => $call->scheduled_end_time,
                                    'call_with' => $call->call_with,
                                    'assigned_to' => $call->assigned_to,
                                    'task' => $call->task
                                );
                            }
                        } else if($activity_type == 'Email') {
                            $email = $customer_time_line_item->email;
                            if(is_object($email)) {
                                $activity_object = array(
                                    'email_id' => $email->id,
                                    'sent_on' => $email->sent_on,
                                    'to' => $email->to,
                                    'cc' => $email->cc,
                                    'bcc' => $email->bcc,
                                    'subject' => $email->subject,
                                    'body' => $email->body,
                                    'assigned_to' => $email->assigned_to,
                                );
                            }
                        } else if($activity_type == 'Meeting') {
                            $meeting = $customer_time_line_item->meeting;
                            if(is_object($meeting)) {
                                $activity_object = array(
                                    'meeting_id' => $meeting->id,
                                    'meeting_date' => $meeting->meeting_date,
                                    'agenda' => $meeting->agenda,
                                    'venue' => $meeting->venue,
                                    'summary' => $meeting->summary,
                                    'scheduled_start_time' => $meeting->scheduled_start_time,
                                    'scheduled_end_time' => $meeting->scheduled_end_time,
                                    'meeting_person' => $meeting->meeting_person,
                                    'assigned_to' => $meeting->assigned_to,
                                    'task' => $meeting->task
                                );
                            }
                        }


                        $customer_time_line_items_list[] = array(
                            'activity_datetime' => $customer_time_line_item->datetime,
                            'activity_type' => $activity_type,
                            'activity_object' => $activity_object
                        );
                    }

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('customer_time_line_items_list' => $customer_time_line_items_list);
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getMySalesPipelineData()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
            $filter_from_date = Input::get('filter_from_date');
            $filter_to_date = Input::get('filter_to_date');

            $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

            $from_date = $date_range['from_date']. ' 00:00:00';
            $to_date = $date_range['to_date']. ' 23:59:59';


            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {


                    $build_query = DB::table('leads')
                        ->where('organization_id', $employee->organization_id)
                        ->whereBetween('datetime' , array($from_date, $to_date));

                    // if sales only load own assigned leads
                    if($employee->user_level == 'sales') {
                        $build_query->where('assigned_to', $employee->id);
                    }



                    $lead_count = $build_query->where('lead_rating', 'Lead')->count();

                    $sales_pipeline_data[] = array('lead_rating' => 'Lead', 'number_of_leads' => $lead_count);

                    $quoted_count = $build_query->where('lead_rating', 'Quoted')->count();

                    $sales_pipeline_data[] = array('lead_rating' => 'Quoted', 'number_of_leads' => $quoted_count);

                    $quoted_negotiation_count = $build_query->where('lead_rating', 'Quoted - Negotiation')->count();

                    $sales_pipeline_data[] = array('lead_rating' => 'Quoted - Negotiation', 'number_of_leads' => $quoted_negotiation_count);

                    $quoted_call_count = $build_query->where('lead_rating', 'Quoted - Call')->count();

                    $sales_pipeline_data[] = array('lead_rating' => 'Quoted - Call', 'number_of_leads' => $quoted_call_count);

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('sales_pipeline_data' => $sales_pipeline_data);
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }


    }

    public function getMyContractedSalesData()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
            $filter_from_date = Input::get('filter_from_date');
            $filter_to_date = Input::get('filter_to_date');

            $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

            $from_date = $date_range['from_date']. ' 00:00:00';
            $to_date = $date_range['to_date']. ' 23:59:59';


            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $query_contracted_sales_list = "select company_name, sum(net_total) as `achieved_sales`, concat(employees.given_name,' ',employees.surname) as `closed_by`
                                    from quotations
                                    left join customers
                                    on quotations.customer_id = customers.id
                                    left join leads
                                    on quotations.lead_id = leads.id
                                    left join employees
                                    on quotations.quoted_by = employees.id
                                    where quotations.organization_id = $employee->organization_id
                                      and quoted_datetime between '$from_date' and '$to_date'
                                      and leads.lead_rating = 'Quoted - Won'
                                    group by company_name
                                    order by achieved_sales desc";


                    $contracted_sales_data = DB::select($query_contracted_sales_list);

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('contracted_sales_data' => $contracted_sales_data);
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }


    }

    public function getFullCustomerContactsListForPicker()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $customer_id = Input::get('customer_id');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $contacts_list = Contact::select(DB::raw('concat (given_name," ",surname, " <", email, ">") as full_name,id'))
                        ->where('customer_id', $customer_id)
                        ->orderBy('full_name', 'asc')
                        ->get();

                    if(count($contacts_list) > 0) {
                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('contacts_list' => $contacts_list);
                        return json_encode($this->return_json);
                    }

                    $this->return_json['message'] = 'No contacts found for this customer.';
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function addCall(){
        try {
            date_default_timezone_set("Asia/Singapore");
            $datetime = date('Y-m-d H:i:s');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $customer_id = Input::get('customer_id');
            $agenda = Input::get('agenda');
            $call_date = Input::get('call_date');
            $call_with = Input::get('call_with');
            $start_time = Input::get('start_time');
            $end_time = Input::get('end_time');
            $summary = Input::get('summary');
            $task = Input::get('task');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {


                    $data_calls = array(
                        'customer_id' => $customer_id,
                        'created_datetime' => $datetime,
                        'agenda' => $agenda,
                        'summary' => $summary,
                        'call_date' => $call_date,
                        'assigned_to' => $employee->id,
                        'task' => 0,
                        'scheduled_start_time' => $start_time,
                        'scheduled_end_time' => $end_time,
                        'call_with' => $call_with,
                        'task' => $task,
                    );

                    $call = Call::create($data_calls);

                    $audit_action = array(
                        'action' => 'create',
                        'model-id' => $call->id,
                        'data' =>  $data_calls
                    );
                    AuditTrail::addAuditEntry("Call", json_encode($audit_action), $employee_id);

                    $data_customer_time_line_items = array(
                        'customer_id' => $customer_id,
                        'time_line_item_source' => "Call",
                        'time_line_item_source_id' => $call->id,
                        'datetime' => $datetime
                    );

                    CustomerTimeLineItem::create($data_customer_time_line_items);

                    if($task){

                        $event = TasksController::setActivityDataForCalendar('Call',$call);

                        $task_data = array(
                            'customer_id' => $customer_id,
                            'contact_id' => 0,
                            'title' => $event['title'],
                            'from_date_time' => $event['date_start'],
                            'to_date_time' => $event['date_end'],
                            'assigned_to' => $employee->id,
                            'location' => $event['location'],
                            'description' => $event['description'],
                            'reminder_types' => [], //array
                            'times' => [], //array
                            'time_units' => [], //array
                            'guest_emails' => [], //array
                        );

                        Task::createTask($task_data);
                    }



                    $this->return_json['status'] = true;
                    $this->return_json['message'] = 'Call added successfully.';
                    return json_encode($this->return_json);




                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function addMeeting()
    {
        try {
            date_default_timezone_set("Asia/Singapore");
            $datetime = date('Y-m-d H:i:s');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $customer_id = Input::get('customer_id');
            $agenda = Input::get('agenda');
            $meeting_date = Input::get('meeting_date');
            $meeting_person = Input::get('meeting_person');
            $start_time = Input::get('start_time');
            $end_time = Input::get('end_time');
            $venue = Input::get('venue');
            $summary = Input::get('summary');
            $task = Input::get('task');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {


                    $data_meetings = array(
                        'customer_id' => $customer_id,
                        'created_datetime' => $datetime,
                        'agenda' => $agenda,
                        'venue' => $venue,
                        'summary' => $summary,
                        'meeting_date' => $meeting_date,
                        'assigned_to' => $employee_id,
                        'task' => 0,
                        'scheduled_start_time' => $start_time,
                        'scheduled_end_time' => $end_time,
                        'meeting_person' => $meeting_person,
                        'task' => $task
                    );

                    $meeting = Meeting::create($data_meetings);

                    $audit_action = array(
                        'action' => 'create',
                        'model-id' => $meeting->id,
                        'data' => $data_meetings
                    );
                    AuditTrail::addAuditEntry("Meeting", json_encode($audit_action), $employee_id);

                    $data_customer_time_line_items = array(
                        'customer_id' => $customer_id,
                        'time_line_item_source' => "Meeting",
                        'time_line_item_source_id' => $meeting->id,
                        'datetime' => $datetime
                    );

                    CustomerTimeLineItem::create($data_customer_time_line_items);


                    if($task){

                        $event = TasksController::setActivityDataForCalendar('Meeting',$meeting);

                        $task_data = array(
                            'customer_id' => $customer_id,
                            'contact_id' => 0,
                            'title' => $event['title'],
                            'from_date_time' => $event['date_start'],
                            'to_date_time' => $event['date_end'],
                            'assigned_to' => $employee->id,
                            'location' => $event['location'],
                            'description' => $event['description'],
                            'reminder_types' => [], //array
                            'times' => [], //array
                            'time_units' => [], //array
                            'guest_emails' => [], //array
                        );

                        Task::createTask($task_data);
                    }

                    $this->return_json['status'] = true;
                    $this->return_json['message'] = 'Meeting added successfully.';
                    return json_encode($this->return_json);




                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function addEmail()
    {
        try {
            date_default_timezone_set("Asia/Singapore");
            $datetime = date('Y-m-d H:i:s');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $customer_id = Input::get('customer_id');
            $to = Input::get('to');
            $cc = Input::get('cc');
            $bcc = Input::get('bcc');
            $subject = Input::get('subject');
            $body = Input::get('body');
            $email_action = Input::get('email_action');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {


                    if($email_action == 'Send'){
                        $status = 'sent';

                        $send_tos = Email::formatEmailsSendingArrayBeforeSending($to);
                        $send_cc = Email::formatEmailsSendingArrayBeforeSending($cc);
                        $send_bcc = Email::formatEmailsSendingArrayBeforeSending($bcc);

                        EmailsController::sendWeb360CustomerEmails($subject, $body, $send_tos, $employee, $send_cc, $send_bcc);
                        $message = "Email sent successfully.";
                    }
                    elseif($email_action == 'Save'){
                        $status = 'draft';
                        $message = "Email saved successfully.";
                    }

                    $data_emails = array(
                        'customer_id' => $customer_id,
                        'to' => $to,
                        'cc' => $cc,
                        'bcc' => $bcc,
                        'subject' => $subject,
                        'body' => $body,
                        'sent_by_id' => $employee->id,
                        'sent_on' => $datetime,
                        'status' => $status,
                        'attachment_urls_json' => '',
                        'email_id' => '',

                    );

                    Email::saveEmail($data_emails, $employee->id);

                    $this->return_json['status'] = true;
                    $this->return_json['message'] = $message;
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function editCall()
    {
        try {
            date_default_timezone_set("Asia/Singapore");
            $datetime = date('Y-m-d H:i:s');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $call_id = Input::get('call_id');
            $agenda = Input::get('agenda');
            $call_date = Input::get('call_date');
            $call_with = Input::get('call_with');
            $start_time = Input::get('start_time');
            $end_time = Input::get('end_time');
            $summary = Input::get('summary');
            $task = Input::get('task');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $call = Call::find($call_id);

                    if(is_object($call)) {
                        $data_calls = array(
                            'created_datetime' => $datetime,
                            'agenda' => $agenda,
                            'summary' => $summary,
                            'call_date' => $call_date,
                            'assigned_to' => $employee->id,
                            'task' => 0,
                            'scheduled_start_time' => $start_time,
                            'scheduled_end_time' => $end_time,
                            'call_with' => $call_with
                        );

                        $call->update($data_calls);



                        $audit_action = array(
                            'action' => 'update',
                            'model-id' => $call->id,
                            'data' => $data_calls
                        );
                        AuditTrail::addAuditEntry("Call", json_encode($audit_action), $employee_id);

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Call updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Call does not match.';
                        return json_encode($this->return_json);
                    }


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function editMeeting()
    {
        try {
            date_default_timezone_set("Asia/Singapore");
            $datetime = date('Y-m-d H:i:s');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $meeting_id = Input::get('meeting_id');
            $agenda = Input::get('agenda');
            $meeting_date = Input::get('meeting_date');
            $meeting_person = Input::get('meeting_person');
            $start_time = Input::get('start_time');
            $end_time = Input::get('end_time');
            $venue = Input::get('venue');
            $summary = Input::get('summary');
            $task = Input::get('task');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {


                    $meeting = Meeting::find($meeting_id);

                    if (is_object($meeting)) {
                        $data_meetings = array(
                            'created_datetime' => $datetime,
                            'agenda' => $agenda,
                            'venue' => $venue,
                            'summary' => $summary,
                            'meeting_date' => $meeting_date,
                            'assigned_to' => $employee_id,
                            'task' => 0,
                            'scheduled_start_time' => $start_time,
                            'scheduled_end_time' => $end_time,
                            'meeting_person' => $meeting_person
                        );

                        $meeting->update($data_meetings);

                        $audit_action = array(
                            'action' => 'update',
                            'model-id' => $meeting->id,
                            'data' => $data_meetings
                        );
                        AuditTrail::addAuditEntry("Meeting", json_encode($audit_action), $employee_id);

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Meeting updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Meeting does not match.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function editEmail()
    {
        try {
            date_default_timezone_set("Asia/Singapore");
            $datetime = date('Y-m-d H:i:s');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $customer_id = Input::get('customer_id');
            $to = Input::get('to');
            $cc = Input::get('cc');
            $bcc = Input::get('bcc');
            $subject = Input::get('subject');
            $body = Input::get('body');
            $email_action = Input::get('email_action');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {


                    if($email_action == 'Send'){
                        $status = 'sent';

                        $send_tos = Email::formatEmailsSendingArrayBeforeSending($to);
                        $send_cc = Email::formatEmailsSendingArrayBeforeSending($cc);
                        $send_bcc = Email::formatEmailsSendingArrayBeforeSending($bcc);

                        EmailsController::sendWeb360CustomerEmails($subject, $body, $send_tos, $employee, $send_cc, $send_bcc);
                        $message = "Email sent successfully.";
                    }
                    elseif($email_action == 'Save'){
                        $status = 'draft';
                        $message = "Email saved successfully.";
                    }

                    $data_emails = array(
                        'customer_id' => $customer_id,
                        'to' => $to,
                        'cc' => $cc,
                        'bcc' => $bcc,
                        'subject' => $subject,
                        'body' => $body,
                        'sent_by_id' => $employee->id,
                        'sent_on' => $datetime,
                        'status' => $status,
                        'attachment_urls_json' => '',
                        'email_id' => '',

                    );

                    Email::saveEmail($data_emails, $employee->id);

                    $this->return_json['status'] = true;
                    $this->return_json['message'] = $message;
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    // meeting status log api calls
    public function getMyMeetingsList()
    {
        try {
            $page = Input::get('page');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
            $filter_from_date = Input::get('filter_from_date');
            $filter_to_date = Input::get('filter_to_date');

            $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

            $from_date = $date_range['from_date']. ' 00:00:00';
            $to_date = $date_range['to_date']. ' 23:59:59';

            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $my_meetings_list = array();

                    $meetings = Meeting::whereBetween('meeting_date', array($from_date, $to_date))
                        ->where('assigned_to', $employee->id)
                        ->orderBy('meeting_date', 'desc')
                        ->paginate(10);

                    if(count($meetings) > 0) {
                        foreach($meetings as $meeting) {
                            $my_meetings_list[] = [
                                'meeting_id' => $meeting->id,
                                'customer_name' => is_object($meeting->customer)? $meeting->customer->customer_name: 'NA',
                                'meeting_person' => $meeting->meeting_person,
                                'agenda' => $meeting->agenda,
                                'meeting_date' => $meeting->meeting_date,
                                'start_time' => $meeting->scheduled_start_time,
                                'end_time' => $meeting->scheduled_end_time,
                                'venue' => $meeting->venue,
                                'summary' => $meeting->summary,
                                'meeting_status' => $meeting->meeting_status == null? 'stop': $meeting->meeting_status,
                            ];
                        }

                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('my_meetings_list' => $my_meetings_list);
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'No meetings found.';
                        return json_encode($this->return_json);
                    }
                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function updateMeetingStatus()
    {
        try {
            date_default_timezone_set("Asia/Singapore");
            $datetime = date('Y-m-d H:i:s');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $meeting_id = Input::get('meeting_id');
            $meeting_status = Input::get('meeting_status');
            $longitude = Input::get('longitude');
            $latitude = Input::get('latitude');
            $address = Input::get('address');

            $employee = Employee::find($employee_id);


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $meeting = Meeting::find($meeting_id);

                    if(is_object($meeting)) {

                        // check if there is a already started meeting
                        $started_meeting_count = Meeting::where('id' , '<>', $meeting_id)
                            ->where('assigned_to', $employee_id)
                            ->where('meeting_status', 'start')
                            ->count();

                        if($started_meeting_count > 0) {
                            $this->return_json['message'] = 'Meeting already started, Please stop existing meeting before starting new one.';
                            return json_encode($this->return_json);
                        }

                        $data_meeting = ['meeting_status' => $meeting_status];

                        $meeting->update($data_meeting);

                        $data_meeting_status_log = [
                            'meeting_id' => $meeting_id,
                            'status_updated_on' => $datetime,
                            'status' => $meeting_status,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'address' => $address,
                        ];

                        MeetingStatusLog::create($data_meeting_status_log);

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Meeting status updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Meeting not found.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function updateMeetingSummary()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $meeting_id = Input::get('meeting_id');
            $meeting_summary = Input::get('meeting_summary');

            $employee = Employee::find($employee_id);


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $meeting = Meeting::find($meeting_id);

                    if(is_object($meeting)) {
                        $data_meeting = [
                            'summary' => $meeting_summary,
                        ];

                        $meeting->update($data_meeting);

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Meeting summary updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Meeting not found.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getMeetingStatusLogsList()
    {
        try {
            $page = Input::get('page');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $meeting_id = Input::get('meeting_id');

            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $meeting_status_logs_list = array();

                    $meeting_status_logs = MeetingStatusLog::where('meeting_id', $meeting_id)
                        ->orderBy('status_updated_on', 'desc')
                        ->paginate(10);

                    if(count($meeting_status_logs) > 0) {
                        foreach($meeting_status_logs as $meeting_status_log) {
                            $meeting_status_logs_list[] = [
                                'status_updated_on' => $meeting_status_log->status_updated_on,
                                'status' => Meeting::$meeting_status[$meeting_status_log->status],
                                'address' => $meeting_status_log->address,
                            ];
                        }

                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('meeting_status_logs_list' => $meeting_status_logs_list);
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'No meeting status logs found.';
                        return json_encode($this->return_json);
                    }
                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function getEmployeeProfileDetails(){

        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $employee_profile_details = [
                        'given_name' => $employee->given_name,
                        'surname' => $employee->surname,
                        'designation' => $employee->designation,
                        'country_code' => $employee->country_code,
                        'contact_no' => $employee->contact_no,
                        'profile_image_file_url' => $employee->profile_image_file_url,
                    ];

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('employee_profile_details' => $employee_profile_details);
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }


    }

    public function updateEmployeeProfileDetails(){

        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $given_name = Input::get('given_name');
            $surname = Input::get('surname');
            $designation = Input::get('designation');
            $country_code = Input::get('country_code');
            $contact_no = Input::get('contact_no');
            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $data_employee = array(
                        'given_name' => $given_name,
                        'surname' => $surname,
                        'designation' => $designation,
                        'country_code' => $country_code,
                        'contact_no' => $contact_no,
                    );

                    if (Input::hasFile('profile_image')) {

                        $profile_image = Input::file('profile_image');
                        $file_name = uniqid("",true);

                        // generate file name
                        $file_name = 'profile_image_' . $employee->id. '_' . $file_name . '.' . $profile_image->getClientOriginalExtension();

                        $file_save_data = GCSFileHandler::saveFile($profile_image, $file_name);

                        $data_employee['profile_image_gcs_file_url'] = $file_save_data['gcs_file_url'];
                        $data_employee['profile_image_file_url'] = $file_save_data['image_url'];

                    }

                    $employee->update($data_employee);

                    $employee_profile_details = [
                        'given_name' => $employee->given_name,
                        'surname' => $employee->surname,
                        'designation' => $employee->designation,
                        'country_code' => $employee->country_code,
                        'contact_no' => $employee->contact_no,
                        'profile_image_file_url' => $employee->profile_image_file_url,
                    ];

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('employee_profile_details' => $employee_profile_details);
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }


    }

    public function getCountryCodeList(){

        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('country_code_list' => Employee::$country_codes);
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }


    }

    public function getMySalesDashboardSalesValueByCampaigns(){
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $organization_id = $employee->organization_id;
                    $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
                    $filter_from_date = Input::get('filter_from_date');
                    $filter_to_date = Input::get('filter_to_date');
                    $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
                    $from_date = $date_range['from_date']. ' 00:00:00';
                    $to_date = $date_range['to_date']. ' 23:59:59';

                    $user_id = $employee_id;
                    $user_level = $employee->user_level;

                    // if sales only load own assigned leads
                    $sales_filter_query = '';
                    if($user_level == 'sales') {
                        $sales_filter_query = "AND leads.assigned_to = '".$user_id."'";
                    }

                    $lead_value_by_campaigns = Quotation::salesValueByCampaignData($organization_id,$from_date,$to_date,$sales_filter_query);

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('sales_value_by_campaign' => $lead_value_by_campaigns);
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }



    }

    public function getMySalesDashboardSalesVolumeByCampaigns(){

        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $organization_id = $employee->organization_id;
                    $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
                    $filter_from_date = Input::get('filter_from_date');
                    $filter_to_date = Input::get('filter_to_date');
                    $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
                    $from_date = $date_range['from_date']. ' 00:00:00';
                    $to_date = $date_range['to_date']. ' 23:59:59';

                    $user_id = $employee_id;
                    $user_level = $employee->user_level;

                    // if sales only load own assigned leads
                    $sales_filter_query = '';
                    if($user_level == 'sales') {
                        $sales_filter_query = "AND leads.assigned_to = '".$user_id."'";
                    }

                    $lead_volume_by_campaigns = Quotation::salesVolumeByCampaignData($organization_id,$from_date,$to_date,$sales_filter_query);

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('sales_volume_by_campaign' => $lead_volume_by_campaigns);
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }


    }

    public function getMySalesDashboardSalesValueBySalesPersons(){
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $organization_id = $employee->organization_id;
                    $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
                    $filter_from_date = Input::get('filter_from_date');
                    $filter_to_date = Input::get('filter_to_date');
                    $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
                    $from_date = $date_range['from_date']. ' 00:00:00';
                    $to_date = $date_range['to_date']. ' 23:59:59';

                    $lead_value_by_campaigns = Quotation::salesValueBySalesPersonData($organization_id,$from_date,$to_date);

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('sales_value_by_sales_person' => $lead_value_by_campaigns);
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }



    }

    public function getMySalesDashboardSalesVolumeBySalesPersons(){

        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');
            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $organization_id = $employee->organization_id;
                    $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
                    $filter_from_date = Input::get('filter_from_date');
                    $filter_to_date = Input::get('filter_to_date');
                    $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);
                    $from_date = $date_range['from_date']. ' 00:00:00';
                    $to_date = $date_range['to_date']. ' 23:59:59';



                    $lead_volume_by_campaigns = Quotation::salesVolumeBySalesPersonData($organization_id,$from_date,$to_date);

                    $this->return_json['status'] = true;
                    $this->return_json['data'] = array('sales_volume_by_sales_person' => $lead_volume_by_campaigns);
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }


    }

    // Quotation API calls
    public function getQuotationsList()
    {
        try {
            $page = Input::get('page');

            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');


            $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
            $filter_from_date = Input::get('filter_from_date');
            $filter_to_date = Input::get('filter_to_date');

            $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

            $from_date = $date_range['from_date']. ' 00:00:00';
            $to_date = $date_range['to_date']. ' 23:59:59';

            $filter_customer_name = Input::get('filter_customer_name');
            $filter_project_quote = Input::get('filter_project_quote');
            $filter_generated_by = Input::get('filter_generated_by');
            $filter_quotation_status = Input::get('filter_quotation_status');

            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $organization_id = $employee->organization_id;

                    $quotations_list = array();


                    $build_query = Quotation::where('organization_id',$organization_id);

                    if($filter_generated_by != '') {
                        $build_query->where('quoted_by', $filter_generated_by);
                    }

                    if($filter_project_quote != '') {
                        $build_query->where('project_quote', 'LIKE', '%' . $filter_project_quote . '%');
                    }

                    if($filter_quotation_status != '' && $filter_quotation_status != 'null') {
                        $build_query->where('quotation_status', $filter_quotation_status);
                    }

                    if($filter_customer_name != '' && $filter_customer_name != 'null') {
                        $build_query->where('company_name', 'LIKE', '%' . $filter_customer_name . '%');
                    }

                    $quotations = $build_query->whereBetween('quoted_datetime', array($from_date, $to_date))
                        ->orderBy('quoted_datetime', 'desc')
                        ->paginate(10);



                    if(count($quotations) > 0) {
                        foreach($quotations as $quotation) {

                            $quotation_items = [];
                            foreach($quotation->quotationItems as $quotation_item)
                            $quotation_items[] = [
                                "description" => $quotation_item->description,
                                "unit_of_measure" => $quotation_item->unit_of_measure,
                                "no_of_units" => $quotation_item->no_of_units,
                                "unit_cost" => $quotation_item->unit_cost,
                                "taxable" => $quotation_item->taxable,
                                "tax" => $quotation_item->tax,
                                "cost" => $quotation_item->cost,
                            ];

                            $quotations_list[] = [
                                'quotation_id' => $quotation->id,
                                'quoted_datetime' => $quotation->quoted_datetime,
                                'quoted_by' => is_object($quotation->quotedBy)? $quotation->quotedBy->getEmployeeFullName(): "NA",
                                'project_quote' => $quotation->project_quote,
                                'company_name' => $quotation->company_name,
                                'address' => $quotation->address,
                                'contact_person' => $quotation->contact_person,
                                'email' => $quotation->email,
                                'phone_number' => $quotation->phone_number,
                                'fax' => $quotation->fax,
                                'sub_total' => $quotation->sub_total,
                                'discount_percentage' => $quotation->discount_percentage,
                                'discount_value' => $quotation->discount_value,
                                'total_taxes' => $quotation->total_taxes,
                                'net_total' => $quotation->net_total,
                                'payment_terms' => $quotation->payment_terms,
                                'quotation_status' => $quotation->quotation_status,
                                'view_pdf_link' => route('mobile_api.v2.quotations.generate_pdf', array('quotation_id' => $quotation->id, 'employee_id' => $employee_id, 't' => $token)),
                                'quotation_items' => $quotation_items,

                            ];
                        }

                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('quotations_list' => $quotations_list);
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'No quotations found.';
                        return json_encode($this->return_json);
                    }
                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }

    }

    public function generateQuotationPDF()
    {
        try {

            $token = Input::get('t');
            $employee_id = Input::get('employee_id');

            $quotation_id = Input::get('quotation_id');

            $employee = Employee::find($employee_id);

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $file_save_data = Quotation::generateQuotationPDF($quotation_id, null);

                    $response = Response::make(file_get_contents($file_save_data['image_url']), '200');

                    $response->header('Content-Type', 'application/pdf');

                    return $response;
                } else {
                    return "Invalid Credentials. Please go back and relogin to app and try.";
                }
            } else {
                return "Invalid Credentials. Please go back and relogin to app and try.";
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            return "Cannot retrieve this quotation at this time.";
        }
    }
    
    // tasks and reminder end points

    public function getTasksList()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $page = Input::get('page');

            $search_task_lists = Input::get('search_task_lists');
            $completed_tasks = Input::get('completed_tasks');
            $filter_by_date_type = Input::get('filter_by_date_type');
            $sort_by = Input::get('sort_by');
            $sort_order = Input::get('sort_order');

            $filter_date_range = Input::get('filter_date_range'); // can be Today, Yesterday, Last Week, Last Month, Last 7 Days, Last 30 Days
            $filter_from_date = Input::get('filter_from_date');
            $filter_to_date = Input::get('filter_to_date');

            $date_range = DateFilterUtilities::getDateRange($filter_date_range, $filter_from_date, $filter_to_date);

            $from_date = $date_range['from_date']. ' 00:00:00';
            $to_date = $date_range['to_date']. ' 23:59:59';

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $tasks = Task::where('assigned_to', $employee_id);

                    if($completed_tasks){
                        $tasks = $tasks->where('status', 0);
                    }else{
                        $tasks = $tasks->where('status', 1);
                    }

                    if($search_task_lists != ''){
                        $tasks = $tasks->where('title', 'LIKE', '%' . $search_task_lists . '%');
                    }

                    if($filter_by_date_type == 'Creation'){
                        if($from_date != '' && $to_date != ''){
                            $tasks = $tasks->where('created_at' ,'>=',$from_date)->where('created_at' ,'<=',$to_date) ;
                        }
                    }else if($filter_by_date_type == 'Due Date'){
                        if($from_date != '' && $to_date != ''){
                            $tasks = $tasks->where('to_date_time' ,'>=',$from_date)->where('to_date_time' ,'<=',$to_date) ;
                        }
                    }


                    if($sort_by != ''){
                        if($sort_by == 'title'){
                            $tasks = $tasks->orderBy('title',$sort_order);
                        }elseif($sort_by == 'location'){
                            $tasks = $tasks->orderBy('location',$sort_order);
                        }elseif($sort_by == 'date'){
                            $tasks = $tasks->orderBy('from_date_time',$sort_order);
                        }

                    }

                    $tasks = $tasks->paginate(10);

                    if(count($tasks) > 0) {

                        $tasks_list= array();

                        foreach($tasks as $task) {
                            $tasks_list[] = array(
                                'organization_id' => $task->organization_id,
                                'title' => $task->title,
                                'customer' => is_object($task->customer)? $task->customer->customer_name: 'NA',
                                'contact' => is_object($task->contact)? $task->contact->getContactFullName(): 'NA',
                                'created_by' => is_object($task->createdBy)? $task->createdBy->getEmployeeFullName(): "NA",
                                'assigned_to' => is_object($task->assignedTo)? $task->assignedTo->getEmployeeFullName(): "NA",
                                'from_date_time' => $task->from_date_time,
                                'to_date_time' => $task->to_date_time,
                                'location' => $task->location,
                                'description' => $task->description,
                                'status' => $task->status,
                                'created_at' => $task->created_at
                            );
                        }

                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('tasks_list' => $tasks_list);
                        return json_encode($this->return_json);
                    }

                    $this->return_json['message'] = 'No tasks found.';
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }
    }

    public function createTask()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $title = Input::get('title');
            $from_date_time = Input::get('from_date_time');
            $to_date_time = Input::get('to_date_time');
            $assigned_to = Input::get('assigned_to');
            $location = Input::get('location');
            $description = Input::get('description');
            $customer_id = Input::get('customer_id');
            $contact_id = Input::get('contact_id');


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $task_data = array(
                        'title' => $title,
                        'from_date_time' => $from_date_time,
                        'to_date_time' => $to_date_time,
                        'assigned_to' => $assigned_to,
                        'location' => $location,
                        'description' => $description,
                        'customer_id' => $customer_id,
                        'contact_id' => $contact_id,
                        'reminder_types' => [], //array
                        'times' => [], //array
                        'time_units' => [], //array
                        'guest_emails' => [], //array
                    );

                    Task::createTask($task_data);

                    $this->return_json['status'] = true;
                    $this->return_json['message'] = 'Task saved successfully.';
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }
    }

    public function updateTask()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $task_id = Input::get('task_id');
            $title = Input::get('title');
            $from_date_time = Input::get('from_date_time');
            $to_date_time = Input::get('to_date_time');
            $assigned_to = Input::get('assigned_to');
            $location = Input::get('location');
            $description = Input::get('description');
            $customer_id = Input::get('customer_id');
            $contact_id = Input::get('contact_id');
            $mark_done = Input::get('mark_done');


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $task = Task::find($task_id);

                    if(is_object($task)) {
                        $task_data = array(
                            'title' => $title,
                            'from_date_time' => $from_date_time,
                            'to_date_time' => $to_date_time,
                            'assigned_to' => $assigned_to,
                            'location' => $location,
                            'description' => $description,
                            'customer_id' => $customer_id,
                            'contact_id' => $contact_id,
                            'mark_done' => $mark_done,
                            'reminder_types' => [], //array
                            'times' => [], //array
                            'time_units' => [], //array
                            'guest_emails' => [], //array
                        );

                        $task->update($task_data);

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Task updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Task does not exist.';
                        return json_encode($this->return_json);
                    }

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }
    }

    public function getRemindersList()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $task_id = Input::get('task_id');

            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $reminders = TaskReminder::where('task_id', $task_id)->get();

                    if(count($reminders) > 0) {

                        $reminders_list= array();

                        foreach($reminders as $reminder) {
                            $reminders_list[] = array(
                                'reminder_type' => $reminder->reminder_type,
                                'time' => $reminder->time,
                                'time_unit' => $reminder->time_unit
                            );
                        }

                        $this->return_json['status'] = true;
                        $this->return_json['data'] = array('reminders_list' => $reminders_list);
                        return json_encode($this->return_json);
                    }

                    $this->return_json['message'] = 'No Reminders found.';
                    return json_encode($this->return_json);


                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }
    }

    public function createReminder()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $task_id = Input::get('task_id');
            $reminder_type = Input::get('reminder_type');
            $time = Input::get('time');
            $time_unit = Input::get('time_unit');


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $reminder_data = array(
                        'task_id' => $task_id,
                        'reminder_type' => $reminder_type,
                        'time' => $time,
                        'time_unit' => $time_unit,
                    );

                    $task_reminder = TaskReminder::create($reminder_data);

                    $task = $task_reminder->task;

                    if(is_object($task)) {
                        TaskReminder::addTaskToReminderQueue($task_reminder->id, $task->from_date_time, $task_reminder->time, $task_reminder->time_unit);
                    }

                    $this->return_json['status'] = true;
                    $this->return_json['message'] = 'Reminder saved successfully.';
                    return json_encode($this->return_json);

                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }
    }

    public function updateReminder()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $reminder_id = Input::get('reminder_id');
            $reminder_type = Input::get('reminder_type');
            $time = Input::get('time');
            $time_unit = Input::get('time_unit');


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $reminder = TaskReminder::find($reminder_id);

                    if(is_object($reminder)) {
                        $reminder_data = array(
                            'reminder_type' => $reminder_type,
                            'time' => $time,
                            'time_unit' => $time_unit,
                        );

                        $reminder->update($reminder_data);

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Reminder updated successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Reminder not found.';
                        return json_encode($this->return_json);
                    }



                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }
    }

    public function deleteReminder()
    {
        try {
            $token = Request::header('Authorization');

            $employee_id = Input::get('employee_id');

            $employee = Employee::find($employee_id);

            $reminder_id = Input::get('reminder_id');


            if(is_object($employee)) {
                if($employee->token == $token && $employee->token != null) {

                    $reminder = TaskReminder::find($reminder_id);

                    if(is_object($reminder)) {

                        $reminder->delete();

                        $this->return_json['status'] = true;
                        $this->return_json['message'] = 'Reminder deleted successfully.';
                        return json_encode($this->return_json);
                    } else {
                        $this->return_json['message'] = 'Reminder not found.';
                        return json_encode($this->return_json);
                    }



                } else {
                    $this->return_json['message'] = 'Token does not match.';
                    return json_encode($this->return_json);
                }
            } else {
                $this->return_json['message'] = 'User does not exist.';
                return json_encode($this->return_json);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, "Issue in mobile API: " . $e->getTraceAsString());
            $this->return_json['message'] = 'There was an issue retrieving the requested information. Please try again.';
            return json_encode($this->return_json);
        }
    }

}