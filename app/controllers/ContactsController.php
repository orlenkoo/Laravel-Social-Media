<?php

class ContactsController extends \BaseController {

    public function ajaxLoadContactsList()
    {
        $customer_id = Input::get('customer_id');
        $screen = Input::get('screen');

        $search_by = Input::get('search_by');

        $build_query = Contact::where('customer_id', $customer_id);

        if($search_by != '') {
            $build_query->where('given_name', 'LIKE', '%' . $search_by . '%')
                ->orWhere('surname', 'LIKE', '%' . $search_by . '%')
            ->orWhere('phone_number', 'LIKE', '%' . $search_by . '%')
            ->orWhere('email', 'LIKE', '%' . $search_by . '%')
            ->orWhere('designation', 'LIKE', '%' . $search_by . '%');
        }
        $contacts = $build_query->orderBy('id', 'desc')->paginate(10);

        return View::make('contacts._ajax_partials.contacts_list', compact('contacts', 'customer_id'))->render();
    }

    public function ajaxSaveNewCustomerContact()
    {
        $customer_id = Input::get('customer_id');
        $given_name = Input::get('given_name');
        $surname = Input::get('surname');
        $phone_number = Input::get('phone_number');
        $email = Input::get('email');
        $designation = Input::get('designation');

        $data_contact = array(
            'customer_id' => $customer_id,
            'given_name' => $given_name,
            'surname' => $surname,
            'phone_number' => $phone_number,
            'email' => $email,
            'designation' => $designation,
        );

        $contact_count = Contact::where('customer_id',$customer_id)->count();
        if($contact_count == 0 ){
            $data_contact['primary_contact'] = 1;
        }

        $contact = Contact::create($data_contact);
        $audit_action = array(
            'action' => 'create',
            'model-id' => $contact->id,
            'data' => $data_contact
        );
        AuditTrail::addAuditEntry("Contact", json_encode($audit_action));

        return "Successfully Updated";
    }

    public function ajaxUpdateCustomerPrimaryContact()
    {
        $customer_id = Input::get('customer_id');
        $primary_contact_id = Input::get('primary_contact_id');

        // first reset old record
        DB::table('contacts')
            ->where('customer_id', $customer_id)
            ->where('primary_contact', 1)
            ->update(array('primary_contact' => 0));

        // set new primary contact
        DB::table('contacts')
            ->where('id', $primary_contact_id)
            ->update(array('primary_contact' => 1));

        return "Successfully Updated";
    }

    public function ajaxGetCustomerPrimaryContactDetails(){
         $customer_id = Input::get('customer_id');
         $customer = Customer::find($customer_id);
         $contact_details_array = array(
             'email' => '',
             'phone_number' => '',
             'address' => '',
         );


         $primary_contact = Contact::getPrimaryContact($customer_id);
         if(is_object($primary_contact)){
                $contact_details_array['email'] = $primary_contact->email;
                $contact_details_array['phone_number'] = $primary_contact->phone_number;
                $contact_details_array['address'] = $customer->getAddress();
         }

        return json_encode($contact_details_array);

    }

    public function ajaxLoadContactsListByCustomer()
    {
        $customer_id = Input::get('customer_id');

        $contacts = Contact::select(DB::raw("concat(given_name, ' ', surname) as full_name, id"))
            ->where('customer_id',$customer_id)
            ->get();

        return Response::make(json_encode($contacts));
    }

}
