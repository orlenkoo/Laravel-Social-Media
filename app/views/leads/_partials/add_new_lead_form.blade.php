<form id="add_new_lead_form" autocomplete="off" >
    <input type="hidden" id="add_new_lead_form_customer_id" value="">
    <div class="row">
        <div class="large-12 columns">
            <label for="company_name">
                Customer Name / Company Name
                <input type="text" name="company_name" id="add_new_lead_form_company_name">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns text-right">
            <input type="button" onclick="ajaxLoadAddNewLeadCheckCustomerList()" value="Check" class="button tiny success">
        </div>
    </div>
    <hr>
    <div id="add_new_lead_check_customer_list_section">
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_add_new_lead_check_customer_list', 'class' => 'float-center', 'style' => 'display:none')) }}
        <div id="response_add_new_lead_check_customer_list">

        </div><!--end add_new_lead_check_customer_list-->
    </div>
    <script>
        // check if existing customer. If not show the below part.
        function ajaxLoadAddNewLeadCheckCustomerList() {
            $('#loader_add_new_lead_check_customer_list').show();
            $('#response_add_new_lead_check_customer_list').hide();
            $('#add_new_lead_form_lead_details_section').hide();
            $('#add_new_lead_check_customer_list_section').show();

            var customer_name = $('#add_new_lead_form_company_name').val();

            $.ajax({
                url: '/customers/ajax/get-customers-list-by-name?' +
                '&customer_name=' + encodeURI(customer_name)

            }).done(function (data) {
                //console.log(data);
                var customers_list = JSON.parse(data);

                var customers_list_table = "<table>";

                customers_list_table += '<tr><th>Customer Name</th><th>Website</th><th></th></tr>';

                for (var i = 0; i < customers_list.length; i++) {
                    var customer_id = customers_list[i].id;
                    var customer_name = customers_list[i].customer_name;
                    var website = customers_list[i].website;
                    customers_list_table += '<tr><td>'+ customer_name +'</td><td>'+ website +'</td><td><input type="button" onclick="addNewLeadSelectCustomer('+ customer_id +', \''+ customer_name +'\')" value="Select" class="button tiny"></td></tr>';
                }

                customers_list_table += '</table>';

                customers_list_table += '<p><input type="button" onclick="addNewLeadSelectCustomer(null, null)" value="Create a new customer for this lead" class="button tiny"></p>';

                $('#response_add_new_lead_check_customer_list').html(customers_list_table);
                $('#loader_add_new_lead_check_customer_list').hide();
                $('#response_add_new_lead_check_customer_list').show();

            });
        }

        function addNewLeadSelectCustomer(customer_id, customer_name) {
            $('#add_new_lead_check_customer_list_section').hide();

            $('#add_new_lead_form_customer_id').val(customer_id);

            if(customer_id != null) {
                $('#add_new_lead_form_company_name').val(customer_name);
            }

            $('#add_new_lead_form_lead_details_section').show();
        }
    </script>
    <div id="add_new_lead_form_lead_details_section" style="display: none;">
        <div class="row">
            <div class="large-6 columns">
                <label for="given_name">
                    Given Name
                    <input type="text" name="given_name" id="add_new_lead_form_given_name">
                </label>
            </div>
            <div class="large-6 columns">
                <label for="surname">
                    Surname
                    <input type="text" name="surname" id="add_new_lead_form_surname">
                </label>
            </div>
        </div>
        <div class="row">
            <div class="large-6 columns">
                <label for="designation">
                    Designation
                    <input type="text" name="designation" id="add_new_lead_form_designation">
                </label>
            </div>
            <div class="large-6 columns">
                <label for="phone_number">
                    Phone Number
                    <input type="text" name="phone_number" id="add_new_lead_form_phone_number">
                </label>
            </div>
        </div>
        <div class="row">
            <div class="large-6 columns">
                <label for="email">
                    Email
                    <input type="text" name="email" id="add_new_lead_form_email">
                </label>
            </div>
            <div class="large-6 columns">
                <label for="company_website">
                    Company Website
                    <input type="text" name="company_website" id="add_new_lead_form_company_website">
                </label>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <label for="lead_note">
                    Note
                    <textarea name="lead_note" id="add_new_lead_form_lead_note" cols="30" rows="4"></textarea>
                </label>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="large-8 columns">
                &nbsp;
            </div>
            <div class="large-2 columns text-right">
                <input type="button" value="Cancel" class="button tiny alert" onclick="$('#reveal_add_new_lead').foundation('close');">
            </div>
            <div class="large-2 columns text-right">
                <input type="button" value="Save" class="button tiny success" onclick="ajaxSaveNewLead()" id="add_new_lead_form_save_button">
            </div>
        </div>
    </div>

</form>

<script>
    function ajaxSaveNewLead() {
        var customer_id = $('#add_new_lead_form_customer_id').val();
        var customer_name = $('#add_new_lead_form_company_name').val();
        var given_name = $('#add_new_lead_form_given_name').val();
        var surname = $('#add_new_lead_form_surname').val();
        var designation = $('#add_new_lead_form_designation').val();
        var phone_number = $('#add_new_lead_form_phone_number').val();
        var email = $('#add_new_lead_form_email').val();
        var company_website = $('#add_new_lead_form_company_website').val();
        var lead_note = $('#add_new_lead_form_lead_note').val();

        $('#add_new_lead_form_save_button').prop('disabled', true);


        $.post("/leads/ajax/save-new-lead",
            {
                customer_id: customer_id,
                customer_name: customer_name,
                given_name: given_name,
                surname: surname,
                designation: designation,
                phone_number: phone_number,
                email: email,
                company_website: company_website,
                lead_note: lead_note
            },
            function (data, status) {

                $.notify(data, "success");

                $('#add_new_lead_form_save_button').prop('disabled', false);

                $('#add_new_lead_check_customer_list_section').hide();
                $('#add_new_lead_form_lead_details_section').hide();

                clearNewLeadForm();

                $('#reveal_add_new_lead').foundation('close');


                loadDashboardData();



            });
    }

    function clearNewLeadForm() {
        document.getElementById("add_new_lead_form").reset();
    }
</script>