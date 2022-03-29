<table id="contacts_list">
    <tr>
        <th>Given Name</th>
        <th>Surname</th>
        <th>Phone Number</th>
        <th>Email</th>
        <th>Designation</th>
        <th>Primary Contact</th>
    </tr>
    @foreach($contacts as $contact)
        <tr>
            <td><input type="text" value="{{ $contact->given_name }}" id="customer_contact_given_name_{{ $contact->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('contacts', '{{ $contact->id }}', 'given_name', this.value, 'customer_contact_given_name_{{ $contact->id }}','Contact')" autocomplete="off"></td>
            <td><input type="text" value="{{ $contact->surname }}" id="customer_contact_surname_{{ $contact->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('contacts', '{{ $contact->id }}', 'surname', this.value, 'customer_contact_surname_{{ $contact->id }}','Contact')" autocomplete="off"></td>
            <td><input type="text" value="{{ $contact->phone_number }}" id="customer_contact_phone_number_{{ $contact->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('contacts', '{{ $contact->id }}', 'phone_number', this.value, 'customer_contact_phone_number_{{ $contact->id }}','Contact');updateCustomerPrimaryContactDetail()" autocomplete="off"></td>
            <td><input type="text" value="{{ $contact->email }}" id="customer_contact_email_{{ $contact->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('contacts', '{{ $contact->id }}', 'email', this.value, 'customer_contact_email_{{ $contact->id }}','Contact');updateCustomerPrimaryContactDetail()" autocomplete="off"></td>
            <td><input type="text" value="{{ $contact->designation }}" id="customer_contact_designation_{{ $contact->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('contacts', '{{ $contact->id }}', 'designation', this.value, 'customer_contact_designation_{{ $contact->id }}','Contact')" autocomplete="off"></td>
            <td><input type="radio" {{ $contact->primary_contact == 1 ? "checked": "" }} name="primary_contact_id" onchange="ajaxUpdateCustomerPrimaryContactStatus();" value="{{ $contact->id }}"></td>
        </tr>
    @endforeach
</table>

<script>

    function updateCustomerPrimaryContactDetail(){
        @if(Route::currentRouteName() != 'customers.view')
            var contact_id = $('input[name=primary_contact_id]:checked').val()
            var customer_email = $("#customer_contact_email_"+contact_id).val();
            var customer_phone = $("#customer_contact_phone_number_"+contact_id).val();
            $("#primary_contact_email_link").html('<a href="mailto:'+customer_email+'">'+customer_email+'</a>');
            $("#primary_contact_phone_link").html('<a href="tel:'+customer_phone+'">'+customer_phone+'</a>');
        @endif
    }


    function ajaxUpdateCustomerPrimaryContactStatus() {
        var primary_contact_id = $('input[name=primary_contact_id]:checked', '#contacts_list').val();
        var customer_id = '{{ $customer_id }}';

        $.post("/contacts/ajax/update-customer-primary-contact",
                {
                    primary_contact_id: primary_contact_id,
                    customer_id: customer_id
                },
                function (data, status) {

                    $.notify(data, "success");

                    updateCustomerPrimaryContactDetail();

                });
    }
</script>