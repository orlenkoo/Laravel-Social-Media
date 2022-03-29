<?php
class CustomersController extends BaseController
{

    public function index()
    {
        return View::make('customers.index');
    }

    public function ajaxLoadCustomers()
    {
        $organization_id = Session::get('user-organization-id');
        $search_query = urldecode(Input::get('search_query'));
        $search_customer_tags = Input::get('search_customer_tags');

        $build_query = Customer::where('organization_id','!=','');

        syslog(LOG_INFO,"search_query -- $search_query");
        syslog(LOG_INFO,"search_customer_tags -- $search_customer_tags");

        if($search_query != '') {
            $customer_ids = Customer::where('customer_name', 'LIKE', '%' . $search_query . '%')->orWhere('website', 'LIKE', '%' . $search_query . '%')->orWhere('phone_number', 'LIKE', '%' . $search_query . '%')->lists('id');
            $build_query->whereIn('id',$customer_ids);
        }

        if($search_customer_tags != 'null'){
            $search_customer_tags=explode(",",$search_customer_tags);
            $customer_ids = CustomerTagAssignment::whereIn('customer_tag_id',$search_customer_tags)->lists('customer_id');
            $build_query->whereIn('id',$customer_ids);
        }

        $customers = $build_query->where('organization_id',$organization_id)->orderBy('customer_name','asc')->paginate(10);

        return View::make('customers._ajax_partials.customers_list', compact('customers'))->render();
    }

    public function viewCustomer($customer_id)
    {

        $customer = Customer::find($customer_id);

        return View::make('customers.view', compact('customer'));


    }

    public function ajaxLoadCustomerLeads()
    {
        $customer_id = Input::get('customer_id');
        $leads_assigned_to_filter = Input::get('leads_assigned_to_filter');
        $user_id = Session::get('user-id');
        $user_level = Session::get('user-level');

        $build_query = Lead::where('customer_id', $customer_id);


        // if sales only load own assigned leads
        if($user_level == 'sales') {
            $build_query->where('assigned_to', $user_id);
        }else{
            if($leads_assigned_to_filter != '') {
                $build_query->where('assigned_to', $leads_assigned_to_filter);
            }
        }

        $customer_leads = $build_query->paginate(10);

        return View::make('customers._ajax_partials.customer_leads_list', compact('customer_leads'))->render();
    }

    public function ajaxLoadCustomerActivities()
    {
        $customer_id = Input::get('customer_id');
        $activity_type = Input::get('activity_type');
        $created_by_filter = Input::get('created_by_filter');
        $search_by_call_or_agenda = Input::get('search_by_call_or_agenda');
        $search_by_meeting = Input::get('search_by_meeting');
        $search_by_email = Input::get('search_by_email');


        if($activity_type == 'calls') {
            $build_query = Call::where('customer_id',$customer_id);

            if($created_by_filter != '') {
                $build_query->where('assigned_to', $created_by_filter);
            }

            if($search_by_call_or_agenda != '') {
                $build_query->where('agenda', $search_by_call_or_agenda)->orWhere('summary', $search_by_call_or_agenda);
            }

            $calls = $build_query->paginate(10);

            return View::make('my_activities._ajax_partials.calls_list', compact('calls'))->render();
        } else if($activity_type == 'meetings') {
            $build_query = Meeting::where('customer_id',$customer_id);

            if($created_by_filter != '') {
                $build_query->where('assigned_to', $created_by_filter);
            }

            if($search_by_meeting != '') {
                $build_query->where('agenda', $search_by_meeting)->orWhere('venue', $search_by_meeting)->orWhere('summary', $search_by_meeting);
            }


            $meetings = $build_query->paginate(10);

            return View::make('my_activities._ajax_partials.meetings_list', compact('meetings'))->render();
        } else if($activity_type == 'emails') {
            $build_query = Email::where('customer_id',$customer_id);

            if($created_by_filter != '') {
                $build_query->where('sent_by_id', $created_by_filter);
            }

            if($search_by_email != '') {
                $build_query->where('subject', $search_by_email)->orWhere('body', $search_by_email);
            }

            $emails = $build_query->paginate(10);

            return View::make('my_activities._ajax_partials.emails_list', compact('emails'))->render();
        }


    }

    public function ajaxLoadCustomerQuotations()
    {
        $customer_id = Input::get('customer_id');
        $search_by_project_quote = Input::get('search_by_project_quote');
        $generated_by_filter = Input::get('generated_by_filter');
        $status_quotation_filter = Input::get('status_quotation_filter');

        $build_query = Quotation::where('customer_id', $customer_id);

        if($search_by_project_quote != '') {
            $build_query->where('project_quote', $search_by_project_quote);
        }

        if($status_quotation_filter != '') {
            $build_query->where('quotation_status', $status_quotation_filter);
        }

        if($generated_by_filter != '') {
            $build_query->where('quoted_by', $generated_by_filter);
        }

        $quotations = $build_query->paginate(10);


        return View::make('quotations._ajax_partials.quotations_list', compact('quotations'))->render();
    }

    public function ajaxLoadCustomerList()
    {
        $customers = Customer::select(DB::raw('customer_name ,id'))
            ->where('organization_id',Session::get('user-organization-id'))
            ->get();

        return Response::make(json_encode($customers));

    }

    public function ajaxGetCustomersListByName()
    {
        $organization_id = Session::get('user-organization-id');

        $customer_name = urldecode(Input::get('customer_name'));

        $customers_list = array();

        if($customer_name != '') {
            syslog(LOG_INFO, '$organization_id -- ' . $organization_id);
            syslog(LOG_INFO, '$customer_name -- ' . $customer_name);

            $customers_list = DB::table('customers')
                ->where('customer_name', 'LIKE', '%'. $customer_name . '%')
                ->where('organization_id', $organization_id)
                ->select('id', 'customer_name', 'website')
                ->get();

            $queries = DB::getQueryLog();
            $last_query = end($queries);

            syslog(LOG_INFO, $last_query['query']);
        }



        return Response::make(json_encode($customers_list));

    }

    public function ajaxLoadDashboardCheckCustomerList()
    {
        $organization_id = Session::get('user-organization-id');

        $customer_name = Input::get('customer_name');

        $customers_list = array();

        if($customer_name != '') {
            syslog(LOG_INFO, '$organization_id -- ' . $organization_id);
            syslog(LOG_INFO, '$customer_name -- ' . $customer_name);

            $customers_list = DB::table('customers')
                ->where('customer_name', 'LIKE', '%'. $customer_name . '%')
                ->where('organization_id', $organization_id)
                ->select('id', 'customer_name', 'website')
                ->get();

            $queries = DB::getQueryLog();
            $last_query = end($queries);

            syslog(LOG_INFO, $last_query['query']);
        }

        return View::make('dashboard.common._ajax_partials.check_customer_list', compact('customers_list'))->render();

    }

    //Get all tags for organization
    public static function getTagsForOrganization()
    {
        $organization_id = Session::get('user-organization-id');
        $customer_tags = CustomerTag::where('organization_id',$organization_id)
            ->select('id','tag')
            ->get();

        return $customer_tags;
    }

    //Get tags assigned to a given customer
    public static function getTagsForCustomer($customer_id)
    {
        $organization_id = Session::get('user-organization-id');

        //get all tags for organization
        $customer_tags = CustomerTag::where('organization_id',$organization_id)
            ->select('id','tag')
            ->get();

        //get all tags assigned for customer
        $customer_tag_assignments = CustomerTagAssignment::where('customer_id',$customer_id)
            ->lists('customer_tag_id');

        $tags_assigned_for_customer = array();

        //for each tag for organization check if if assigned to customer
        foreach($customer_tags as $customer_tag){
            if(!empty($customer_tag_assignments) && in_array($customer_tag->id,$customer_tag_assignments)){//if assigned to customer set true
                array_push($tags_assigned_for_customer,array(
                    'id' => $customer_tag->id,
                    'tag' =>  $customer_tag->tag,
                    'assigned' =>  true
                ));
            }else{//if not assigned to customer set false
                array_push($tags_assigned_for_customer,array(
                    'id' => $customer_tag->id,
                    'tag' =>  $customer_tag->tag,
                    'assigned' =>  false
                ));
            }
        }

        return $tags_assigned_for_customer;
    }

    public function assignCustomerTags(){

        $organization_id = Session::get('user-organization-id');
        $customer_id = Input::get('customer_id');
        $customer_tags = Input::get('customer_tags');//selected customer tags

        //remove all existing tags assigned for customer
        CustomerTagAssignment::where('customer_id',$customer_id)->delete();

        $new_customer_tag_ids = array(); // array for new tags to be assigned

        //check if no tags were selected
        if(empty($customer_tags)){
            return 'Customer updated successfully';
        }

        foreach($customer_tags as $key=>$value){

            //check for existing customer tags

            if (is_numeric ($value)){ //if existing push to array

                array_push($new_customer_tag_ids,$value);

            }else{//if new tag

                $existing_tag = CustomerTag::where('tag',$value)->pluck('id');

                if($existing_tag){//Check if existing tag
                    array_push($new_customer_tag_ids,$existing_tag);
                }else{//else create new tag
                    $customer_tag = CustomerTag::create(array(
                        'organization_id' => $organization_id,
                        'tag' => $value,
                        'status' => 1
                    ));
                    array_push($new_customer_tag_ids,$customer_tag->id);
                }
            }
        }

        //assigning the new tags for the customer
        foreach($new_customer_tag_ids as $key=>$value){
            CustomerTagAssignment::create(array(
                'customer_id' => $customer_id,
                'customer_tag_id' => $value,
            ));
        }

        return 'Customer updated successfully';
    }

    public function ajaxLoadCustomerTags()
    {
        $organization_id = Session::get('user-organization-id');
        $customer_tags = CustomerTag::select(DB::raw('tag ,id'))->where('organization_id',$organization_id)->orderBy('tag', 'asc')->get();
        $customer_tags_json = json_encode($customer_tags);
        return Response::make($customer_tags_json);
    }

}

