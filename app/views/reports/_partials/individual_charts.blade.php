<div class="row expanded">
    <div class="large-12 columns">
        <div class="panel">
            <div class="panel-heading">
                <h1>Activity</h1>
            </div>
            <div class="panel-content">
                <div class="row expanded chart-box">
                    <div class="large-6 columns">
                        <h4>Calls Per Person</h4>
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_call_count_per_person', 'style' => 'display:none')) }}
                        <div id="chart_call_count_per_person"></div>
                    </div>
                    <div class="large-6 columns">
                        <h4>Meetings Per Person</h4>
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_meeting_count_per_person', 'style' => 'display:none')) }}
                        <div id="chart_meeting_count_per_person"></div>
                    </div>
                </div>
                <div class="row expanded chart-box">
                    <div class="large-6 columns">
                        <h4>Contracts($) Per Person</h4>
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_contracts_value_per_person', 'style' => 'display:none')) }}
                        <div id="chart_contracts_value_per_person"></div>
                    </div>
                    <div class="large-6 columns">
                        <h4>Quotes($) Per Person</h4>
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_quotations_value_per_person', 'style' => 'display:none')) }}
                        <div id="chart_quotations_value_per_person"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function loadDashboardData() {
        ajaxLoadReportCharts();
    }
    loadDashboardData();


    // Draw the pie chart for Sarah's pizza when Charts is loaded.
    google.charts.setOnLoadCallback(ajaxLoadReportCharts);

    function ajaxLoadReportCharts(){

        var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
        var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
        var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

        $.ajax({
            url: '/reports/ajax/load-report-charts?' +
            'dashboard_filter_date_range=' + dashboard_filter_date_range +
            '&dashboard_filter_from_date=' + dashboard_filter_from_date +
            '&dashboard_filter_to_date=' + dashboard_filter_to_date

        }).done(function (data) {

            data = JSON.parse(data);


            var call_count_per_person = data['call_count_per_person'];
            var meeting_count_per_person = data['meeting_count_per_person'];
            var contracts_value_per_person = data['contracts_value_per_person'];
            var quotations_value_per_person = data['quotations_value_per_person'];


            // ---------------

            if(call_count_per_person != ''){

                console.log('call_count_per_person -- '+JSON.stringify(call_count_per_person));

                $('#loader_chart_call_count_per_person').hide();
                $('#chart_call_count_per_person').show();

                //console.log(data);
                var chart_data = call_count_per_person;
                var chart_data_array = [['Sales Staff', 'Number of Calls',{ role: 'style' }]];
                for(var i=1; i<= chart_data.length; i++) {
                    chart_data_array [i] = [chart_data[i-1].full_name, +chart_data[i-1].call_count, '#49bc1b'];
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
                    height: 1000,
                    hAxis: {
                        viewWindow: {
                            min: 0
                        }
                    },
                    bar: {groupWidth: "90%"},
                    legend: {position: "none"},
                };
                var chart = new google.visualization.BarChart(document.getElementById("chart_call_count_per_person"));
                chart.draw(view, options);
            }else{

                $('#loader_chart_call_count_per_person').hide();
                $('#chart_call_count_per_person').html('<p class="alert-box info radius">No Calls Available For The Given Period.</p>');
                $('#chart_call_count_per_person').show();
            }

            if(meeting_count_per_person != ''){

                console.log('meeting_count_per_person -- '+JSON.stringify(meeting_count_per_person));

                $('#loader_chart_meeting_count_per_person').hide();
                $('#chart_meeting_count_per_person').show();

                //console.log(data);
                var chart_data = meeting_count_per_person;
                var chart_data_array = [['Sales Staff', 'Number of Meetings',{ role: 'style' }]];
                for(var i=1; i<= chart_data.length; i++) {
                    chart_data_array [i] = [chart_data[i-1].full_name, +chart_data[i-1].meeting_count, '#49bc1b'];
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
                    height: 800,
                    hAxis: {
                        viewWindow: {
                            min: 0
                        }
                    },
                    bar: {groupWidth: "90%"},
                    legend: {position: "none"},
                };
                var chart = new google.visualization.BarChart(document.getElementById("chart_meeting_count_per_person"));
                chart.draw(view, options);
            }else{

                $('#loader_chart_meeting_count_per_person').hide();
                $('#chart_meeting_count_per_person').html('<p class="alert-box info radius">No Meetings Available For The Given Period.</p>');
                $('#chart_meeting_count_per_person').show();
            }



            if(contracts_value_per_person != ''){

                console.log('contracts_value_per_person -- '+JSON.stringify(contracts_value_per_person));

                $('#loader_chart_contracts_value_per_person').hide();
                $('#chart_contracts_value_per_person').show();

                //console.log(data);
                var chart_data = contracts_value_per_person;
                var chart_data_array = [['Sales Staff', 'Contract Values',{ role: 'style' }]];
                for(var i=1; i<= chart_data.length; i++) {
                    chart_data_array [i] = [chart_data[i-1].full_name, +chart_data[i-1].sub_total, '#49bc1b'];
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
                    height: 800,
                    hAxis: {
                        viewWindow: {
                            min: 0
                        }
                    },
                    bar: {groupWidth: "90%"},
                    legend: {position: "none"},
                };
                var chart = new google.visualization.BarChart(document.getElementById("chart_contracts_value_per_person"));
                chart.draw(view, options);
            }else{

                $('#loader_chart_contracts_value_per_person').hide();
                $('#chart_contracts_value_per_person').html('<p class="alert-box info radius">No Contracts Available For The Given Period.</p>');
                $('#chart_contracts_value_per_person').show();
            }



            if(quotations_value_per_person != ''){

                console.log('quotations_value_per_person -- '+JSON.stringify(quotations_value_per_person));

                $('#loader_chart_quotations_value_per_person').hide();
                $('#chart_quotations_value_per_person').show();

                //console.log(data);
                var chart_data = quotations_value_per_person;
                var chart_data_array = [['Sales Staff', 'Quotation Values',{ role: 'style' }]];
                for(var i=1; i<= chart_data.length; i++) {
                    chart_data_array [i] = [chart_data[i-1].full_name, +chart_data[i-1].sub_total, '#49bc1b'];
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
                    height: 800,
                    hAxis: {
                        viewWindow: {
                            min: 0
                        }
                    },
                    bar: {groupWidth: "90%"},
                    legend: {position: "none"},
                };
                var chart = new google.visualization.BarChart(document.getElementById("chart_quotations_value_per_person"));
                chart.draw(view, options);
            }else{

                $('#loader_chart_quotations_value_per_person').hide();
                $('#chart_quotations_value_per_person').html('<p class="alert-box info radius">No Quotations Available For The Given Period.</p>');
                $('#chart_quotations_value_per_person').show();
            }

        });

    }


</script>
