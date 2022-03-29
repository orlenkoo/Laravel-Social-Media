<div class="row">
    <div class="large-12 columns">

        <label for="">
            Customer Name / Company Name
            <select class="lead_meta_loader" id="change_customer_for_lead_customer_name"
                    name="change_customer_for_lead_customer_name"></select>
            <input type="button" class="button tiny success" value="Check"
                   onclick="ajaxLoadDashboardCheckCustomerList()">
        </label>

    </div>
</div>

{{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_dashboard_check_customer_list', 'class' => 'float-center', 'style' => 'display:none')) }}
<div id="response_dashboard_check_customer_list">

</div><!--end response_dashboard_check_customer_list-->
<script>
    function ajaxLoadDashboardCheckCustomerList() {
        $('#loader_dashboard_check_customer_list').show();
        $('#response_dashboard_check_customer_list').hide();


        var customer_name = $('#change_customer_for_lead_customer_name').selectize()[0].selectize.getValue();

        console.log(customer_name);

        if(customer_name) {
            $.ajax({
                url: '/customers/ajax/load-dashboard-check-customer-list?' +
                '&customer_name=' + customer_name

            }).done(function (data) {
                $('#response_dashboard_check_customer_list').html(data);
                $('#loader_dashboard_check_customer_list').hide();
                $('#response_dashboard_check_customer_list').show();

            });
        } else {
            $.notify("Please enter customer name to search.", "error");
        }


    }


</script>
