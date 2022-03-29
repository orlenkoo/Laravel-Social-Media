{{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_sales_pipeline', 'style' => 'display:none')) }}
<div id="chart_sales_pipeline"></div>
<script>
    // Draw the pie chart for Sarah's pizza when Charts is loaded.
    google.charts.setOnLoadCallback(ajaxLoadSalesDashboardSalesPipelineChart);

    function ajaxLoadSalesDashboardSalesPipelineChart(){

        var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
        var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
        var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();
        $('#loader_chart_sales_pipeline').show();

        $.ajax({
            url: '/leads/ajax/load-sales-dashboard-sales-pipeline-chart?' +
            'dashboard_filter_date_range=' + dashboard_filter_date_range +
            '&dashboard_filter_from_date=' + dashboard_filter_from_date +
            '&dashboard_filter_to_date=' + dashboard_filter_to_date

        }).done(function (data) {

            if(data != '[]'){

                $('#loader_chart_sales_pipeline').hide();
                $('#chart_sales_pipeline').show();

                console.log(data);
                var chart_data = $.parseJSON(data);

                var chart_data_array = [['Sales Pipeline', 'Number of Leads',{ role: 'style' }]];
                for(var i=1; i<= chart_data.length; i++) {
                    chart_data_array [i] = [chart_data[i-1].lead_rating, chart_data[i-1].number_of_leads, '#49bc1b'];
                }

                var data_chart = google.visualization.arrayToDataTable(chart_data_array);

                var view = new google.visualization.DataView(data_chart);
                view.setColumns([0, 1,
                    {
                        calc: "stringify",
                        sourceColumn: 1,
                        type: "string",
                        role: "annotation"
                    },
                    2]);

                var options = {
                    title: null,
                    width: "100%",
                    height: 300,
                    bar: {groupWidth: "90%"},
                    legend: {position: "none"},
                };
                var chart = new google.visualization.ColumnChart(document.getElementById("chart_sales_pipeline"));
                chart.draw(view, options);
            }else{

                $('#loader_chart_sales_pipeline').hide();
                $('#chart_sales_pipeline').html('<p class="alert-box info radius">No Leads Available For the Selected Time Period.</p>');
                $('#chart_sales_pipeline').show();
            }
        });
    }
</script>