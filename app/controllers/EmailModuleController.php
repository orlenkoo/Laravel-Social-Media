<?php

class EmailModuleController extends BaseController
{
    public function index()
    {
        return View::make('email_module.index')->render();
    }

    public function ajaxLoadEmailCampaignsList()
    {
        $search_query = Input::get('search_query');
        $organization_id = Session::get('user-organization-id');

        $build_query = EmailModuleEmailCampaign::where('organization_id', $organization_id);

        if($search_query != '') {
            $build_query->where('campaign_name', 'LIKE', '%' . $search_query . '%');
        }

        $email_campaigns = $build_query->orderBy('id','desc')->paginate(5);

        return View::make('email_module._ajax_partials.email_campaigns_list', compact('email_campaigns'))->render();
    }

    public function emailCampaignManagementScreen()
    {
        $email_campaign_id = Input::get('email_campaign_id');

        $email_campaign = EmailModuleEmailCampaign::find($email_campaign_id);

        if(is_object($email_campaign)) {
            return View::make('email_module._partials.email_campaign_management_screen', ['email_campaign' => $email_campaign])->render();
        } else {
            return "Email campaign cannot be found.";
        }


    }

    public function ajaxSaveNewEmailCampaign()
    {
        $organization_id = Session::get('user-organization-id');

        $campaign_name = Input::get('campaign_name');
        $start_date_time = Input::get('start_date_time');
        $end_date_time = Input::get('end_date_time');

        $data_email_campaign = array(
            'organization_id' => $organization_id,
            'campaign_id' => null,
            'campaign_name' => $campaign_name,
            'subject' => "",
            'from_name' => Session::get('user-name'),
            'from_email_address' => Session::get('user-email'),
            'status' => "New",
            'start_date_time'=> $start_date_time,
            'end_date_time' => $end_date_time,
            'start_automatically' => 1,
            'email_module_email_list_id' => null,
            'email_content' => null,
        );

        $email_campaign = EmailModuleEmailCampaign::create($data_email_campaign);

        return Response::json(array("status" => "success", "message" => "Email Campaign Created Successfully", "email_campaign_id" => $email_campaign->id));


    }

    public function ajaxLoadEmailLists()
    {
        $organization_id = Session::get('user-organization-id');

        $search_query = urldecode(Input::get('search_query'));
        $email_campaign_id = Input::get('email_campaign_id');

        $email_campaign = EmailModuleEmailCampaign::find($email_campaign_id);

        $selected_email_id = $email_campaign->email_module_email_list_id;

        $email_lists = EmailModuleEmailList::where('organization_id', $organization_id)
            ->where('email_list_name', 'LIKE', '%'. $search_query . '%')
            ->paginate(10);

        return View::make('email_module._ajax_partials.email_lists_list', compact('email_lists', 'selected_email_id'))->render();
    }

    public function ajaxSearchAndLoadContactsList()
    {
        $organization_id = Session::get('user-organization-id');

        $search_query = urldecode(Input::get('search_query'));
        $search_by = Input::get('search_by');
        $selected_contents_reload_function = Input::get('selected_contents_reload_function');
        $selected_contents_list_id = Input::get('selected_contents_list_id');
        $unique_id = uniqid();

        if($search_by == "customer_name") {
            $customer_ids = Customer::where('customer_name', 'LIKE', '%'. $search_query . '%')
                ->where('organization_id', $organization_id)
                ->lists('id');

            $contacts = Contact::whereIn('customer_id', $customer_ids)->paginate(10);
        } else if ($search_by == "customer_tags") {

            $customer_tag_ids = CustomerTag::where('tag', 'LIKE', '%'. $search_query . '%')->where('organization_id', $organization_id)->lists('id');

            $customer_ids = CustomerTagAssignment::whereIn('customer_tag_id', $customer_tag_ids)->lists('customer_id');

            $contacts = Contact::whereIn('customer_id', $customer_ids)->paginate(10);

        } else if ($search_by == "contact_name") {

            $customer_ids = Customer::where('organization_id', $organization_id)->lists('id');

            $contacts = Contact::where('given_name', 'LIKE', '%'. $search_query . '%')
                                ->orwhere('surname ', 'LIKE', '%'. $search_query . '%');


            $contacts = $contacts->whereIn('customer_id', $customer_ids)->paginate(10);

        } else if ($search_by == "contact_email") {

            $customer_ids = Customer::where('organization_id', $organization_id)->lists('id');

            $contacts = Contact::whereIn('customer_id', $customer_ids)->where('email', 'LIKE', '%'. $search_query . '%')->paginate(10);

        } else if ($search_by == "contact_phone_number") {
            $customer_ids = Customer::where('organization_id', $organization_id)->lists('id');

            $contacts = Contact::whereIn('customer_id', $customer_ids)->where('phone_number', 'LIKE', '%'. $search_query . '%')->paginate(10);
        }

        return View::make('email_module._ajax_partials.search_contacts_list', compact('contacts','selected_contents_reload_function','selected_contents_list_id','unique_id'))->render();
    }

    public function ajaxLoadSelectedContactsList()
    {
        $selected_contacts_ids = json_decode(Input::get('selected_contacts_ids'));
        $selected_contents_reload_function = Input::get('selected_contents_reload_function');
        $selected_contents_list_id = Input::get('selected_contents_list_id');
        $unique_id = uniqid();

        $contacts = Contact::whereIn('id', $selected_contacts_ids)->paginate(20);

        return View::make('email_module._ajax_partials.selected_contacts_list', compact('contacts','selected_contents_reload_function','selected_contents_list_id','unique_id'))->render();
    }

    public function ajaxSaveNewEmailList()
    {
        $organization_id = Session::get('user-organization-id');
        $email_list_name = Input::get('email_list_name');
        $selected_contacts_ids = json_decode(Input::get('selected_contacts_ids'));

        $data_email_list = array(
            'organization_id' => $organization_id,
            'email_list_name' => $email_list_name,
            'status' => 1,
        );

        $email_list = EmailModuleEmailList::create($data_email_list);

        foreach($selected_contacts_ids as $contact_id) {
            $data_email_list_contact_assignment = array(
                'email_module_email_list_id' => $email_list->id,
                'contact_id' => $contact_id,
            );
            EmailModuleEmailListContactAssignment::create($data_email_list_contact_assignment);
        }

        return "Successfully Created Email List.";

    }

    public function ajaxEditEmailList()
    {
        $email_list_id = Input::get('email_list_id');
        $email_list_name = Input::get('email_list_name');
        $selected_contacts_ids = json_decode(Input::get('selected_contacts_ids'));

        $email_list = EmailModuleEmailList::find($email_list_id);

        $data_email_list = array(
            'email_list_name' => $email_list_name,
        );

        $email_list->update($data_email_list);

        EmailModuleEmailListContactAssignment::where('email_module_email_list_id', $email_list_id)->delete();

        foreach($selected_contacts_ids as $contact_id) {
            $data_email_list_contact_assignment = array(
                'email_module_email_list_id' => $email_list->id,
                'contact_id' => $contact_id,
            );
            EmailModuleEmailListContactAssignment::create($data_email_list_contact_assignment);
        }

        return "Successfully Created Email List.";

    }

    public function ajaxLoadEmailContentPreview(){

        $email_campaign_id = Input::get('email_campaign_id');

        $email_campaign = EmailModuleEmailCampaign::find($email_campaign_id);

        return View::make('email_module._partials.email_content_preview', compact('email_campaign'))->render();

    }

    public function ajaxEditEmailContent(){

        $email_campaign_id = Input::get('email_campaign_id');
        $email_campaign_email_content = Input::get('email_campaign_email_content');

        $email_campaign = EmailModuleEmailCampaign::find($email_campaign_id);

        $email_campaign->update([
            'email_content' => $email_campaign_email_content
        ]);


        return "Successfully Updated Email Content.";
    }

}
