{{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_contracted_sales_widget', 'style' => 'display:none')) }}
<div id="response_contracted_sales_widget"></div>
<script>
    function ajaxLoadDashboardContractedSalesWidget() {
        $('#response_contracted_sales_widget').hide();
        $('#loader_contracted_sales_widget').show();

        var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
        var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
        var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

        var my_sales_widget_assigned_to = $('#my_sales_widget_assigned_to').val();

        $.ajax({
            url: '/quotations/ajax-load-dashboard-contracted-sales-widget?' +
            'dashboard_filter_date_range=' + dashboard_filter_date_range +
            '&dashboard_filter_from_date=' + dashboard_filter_from_date +
            '&dashboard_filter_to_date=' + dashboard_filter_to_date +
            '&my_sales_widget_assigned_to=' + my_sales_widget_assigned_to
        }).done(function (data) {
            $('#response_contracted_sales_widget').html(data);
            $('#loader_contracted_sales_widget').hide();
            $('#response_contracted_sales_widget').show();
            $(document).foundation();
        });
    }
</script>