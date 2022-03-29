@if(count($customers_list) > 0)
    <table>
        <tr>
            <th>Customer Name</th>
            <th>Website</th>
            <th>Select</th>
        </tr>
        @foreach($customers_list as $customer)
            <tr>
                <td>{{ $customer->customer_name }}</td>
                <td>{{ $customer->website }}</td>
                <td><input type="button" value="Select" id="dashboard_customer_select_button_{{ $customer->id }}" class="button tiny" onclick="ajaxAssignCustomerForLead({{ $customer->id }})"></td>
            </tr>
        @endforeach
    </table>
@endif

<input type="button" class="button tiny" value="Create a new customer for this lead" onclick="ajaxCreateNewCustomerForLead()" id="dashboard_customer_create_button">

<script>
    // if new customer this will save the customer name and show the customer details fields to enter
    function ajaxCreateNewCustomerForLead() {


        var customer_name = $('#change_customer_for_lead_customer_name').val();
        var lead_id = $('#dashboard_selected_lead_id').val();

        console.log('customer_name -- ' + customer_name);
        console.log('lead_id -- ' + lead_id);

        $('#dashboard_customer_create_button').prop('disabled', true);


        $.post("/leads/ajax/create-new-customer-for-lead",
            {
                lead_id: lead_id,
                customer_name: customer_name
            },
            function(data, status){

                $.notify(data, "success");

                $('#dashboard_customer_create_button').prop('disabled', false);


                ajaxLoadDashboardLeadDetails(lead_id);

                ajaxLoadDashboardLeadsList(1);

                closePopup('popup_change_customer_for_lead');
            });
    }

    // if existing customer this will set the particular customer to lead and mark the lead as duplicate
    function ajaxAssignCustomerForLead(customer_id) {

        var lead_id = $('#dashboard_selected_lead_id').val();

        $('#dashboard_customer_select_button_'+customer_id).prop('disabled', true);

        $.post("/leads/ajax/assign-customer-for-lead",
            {
                lead_id: lead_id,
                customer_id: customer_id
            },
            function(data, status){

                $.notify(data, "success");

                $('#dashboard_customer_select_button_'+customer_id).prop('disabled', false);

                if(typeof ajaxLoadDashboardLeadsList == 'function'){
                    var pagination_page  = $("#pagination_dashboard_leads_list").find('li.active > span').html();
                    ajaxLoadDashboardLeadsList(pagination_page);
                }

                if(typeof ajaxLoadDashboardLeadDetails == 'function'){
                    ajaxLoadDashboardLeadDetails(lead_id);
                }

                if(typeof ajaxLoadLeadsList == 'function'){
                    var pagination_page  = $("#pagination_leads_list").find('li.active > span').html();
                    ajaxLoadLeadsList(pagination_page);
                }

                if(typeof ajaxLoadLeadDetails == 'function'){
                    ajaxLoadLeadDetails(lead_id);
                }

                closePopup('popup_change_customer_for_lead');
            });

    }


</script>


