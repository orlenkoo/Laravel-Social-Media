<div class="row expanded">
    @if(Session::get('user-level') != 'sales')
        <div class="large-12 columns">
            <label for="my_sales_widget_assigned_to">
                Assigned To:
                <select name="my_sales_widget_assigned_to" id="my_sales_widget_assigned_to" multiple onchange="ajaxLoadMySalesDashboardChart()">
                </select>
            </label>
        </div>
    @else
        <input type="hidden" id="my_sales_widget_assigned_to" style="display: none;" value="{{ Session::get('user-id') }}">
    @endif
</div>

<div class="large-6 columns">
    <div class="panel">
        <div class="panel-heading">
            <h1>Sales Volume By Campaigns</h1>
        </div>
        <div class="panel-content">
            <div class="row">
                <div class="large-12 columns">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_sales_volume_by_campaign', 'style' => 'display:none')) }}
                    <div id="chart_sales_volume_by_campaign"></div>
                </div>
            </div>
        </div>
        <script>

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(ajaxLoadMySalesDashboardSalesVolumeByCampaignChart);

            function ajaxLoadMySalesDashboardSalesVolumeByCampaignChart(){

                var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
                var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                var my_sales_widget_assigned_to = $('#my_sales_widget_assigned_to').val();

                $('#loader_chart_sales_volume_by_campaign').show();
                $('#chart_sales_volume_by_campaign').hide();

                $.ajax({
                    url: '/quotations/ajax/load-my-sales-dashboard-sales-volume-by-campaign-chart?' +
                    'dashboard_filter_date_range=' + dashboard_filter_date_range +
                    '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                    '&dashboard_filter_to_date=' + dashboard_filter_to_date +
                    '&my_sales_widget_assigned_to=' + my_sales_widget_assigned_to

                }).done(function (data) {

                    if(data != '[]'){

                        var sales_volume_by_campaigns_chart_data = $.parseJSON(data);

                        /********  LEAD VOLUME BY CAMPAIGN  *********/

                        $('#loader_chart_sales_volume_by_campaign').hide();
                        $('#chart_sales_volume_by_campaign').show();

                        var sales_volume_by_campaign_chart_data_array = [['Campaign', 'Campaign Volume']];

                        for(var i=1; i<= sales_volume_by_campaigns_chart_data.length; i++) {
                            sales_volume_by_campaign_chart_data_array[i] = [sales_volume_by_campaigns_chart_data[i-1].campaign, parseInt(sales_volume_by_campaigns_chart_data[i-1].sales_volume)];
                        }

                        console.log(sales_volume_by_campaign_chart_data_array);

                        var sales_volume_by_campaign_data_chart = google.visualization.arrayToDataTable(sales_volume_by_campaign_chart_data_array);

                        console.log(sales_volume_by_campaign_data_chart);

                        var sales_volume_by_campaign_options = {
                            title: null,
                            pieHole: 0.4,
                            height:300,
                            sliceVisibilityThreshold: 0,
                            legend: {
                                position: 'labeled'
                            }
                        };
                        var sales_volume_by_campaign_chart = new google.visualization.PieChart(document.getElementById("chart_sales_volume_by_campaign"));
                        sales_volume_by_campaign_chart.draw(sales_volume_by_campaign_data_chart, sales_volume_by_campaign_options);

                        /********** END LEAD VOLUME BY CAMPAIGN  *********/


                    }else{

                        $('#loader_chart_sales_volume_by_campaign').hide();
                        $('#chart_sales_volume_by_campaign').html('<p class="alert-box info radius">No Sales Available For the Selected Time Period.</p>');
                        $('#chart_sales_volume_by_campaign').show();
                    }
                });
            }
        </script>
    </div>
</div>
<div class="large-6 columns">
    <div class="panel">
        <div class="panel-heading">
            <h1>Sales Value By Campaigns</h1>
        </div>
        <div class="panel-content">
            <div class="row">
                <div class="large-12 columns">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_sales_value_by_campaign', 'style' => 'display:none')) }}
                    <div id="chart_sales_value_by_campaign"></div>
                </div>
            </div>
        </div>
        <script>

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(ajaxLoadMySalesDashboardSalesValueByCampaignChart);

            function ajaxLoadMySalesDashboardSalesValueByCampaignChart(){

                var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
                var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

                var my_sales_widget_assigned_to = $('#my_sales_widget_assigned_to').val();

                $('#loader_chart_sales_value_by_campaign').show();
                $('#chart_sales_value_by_campaign').hide();

                $.ajax({
                    url: '/quotations/ajax/load-my-sales-dashboard-sales-value-by-campaign-chart?' +
                    'dashboard_filter_date_range=' + dashboard_filter_date_range +
                    '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                    '&dashboard_filter_to_date=' + dashboard_filter_to_date +
                    '&my_sales_widget_assigned_to=' + my_sales_widget_assigned_to

                }).done(function (data) {

                    if(data != '[]'){

                        var sales_value_by_campaigns_chart_data = $.parseJSON(data);

                        /********  LEAD VALUE BY CAMPAIGN  *********/

                        $('#loader_chart_sales_value_by_campaign').hide();
                        $('#chart_sales_value_by_campaign').show();

                        var sales_value_by_campaign_chart_data_array = [['Campaign', 'Campaign Value']];

                        for(var i=1; i<= sales_value_by_campaigns_chart_data.length; i++) {
                            sales_value_by_campaign_chart_data_array[i] = [sales_value_by_campaigns_chart_data[i-1].campaign, parseInt(sales_value_by_campaigns_chart_data[i-1].sales_value)];
                        }

                        console.log(sales_value_by_campaign_chart_data_array);

                        var sales_value_by_campaign_data_chart = google.visualization.arrayToDataTable(sales_value_by_campaign_chart_data_array);

                        console.log(sales_value_by_campaign_data_chart);

                        var sales_value_by_campaign_options = {
                            title: null,
                            height:300,
                            sliceVisibilityThreshold: 0,
                            legend: {
                                position: 'labeled'
                            },
                            pieSliceText: 'value'
                        };

                        var sales_value_by_campaign_chart = new google.visualization.PieChart(document.getElementById("chart_sales_value_by_campaign"));
                        sales_value_by_campaign_chart.draw(sales_value_by_campaign_data_chart, sales_value_by_campaign_options);

                        /********** END LEAD VALUE BY CAMPAIGN  *********/


                    }else{

                        $('#loader_chart_sales_value_by_campaign').hide();
                        $('#chart_sales_value_by_campaign').html('<p class="alert-box info radius">No Sales Available For the Selected Time Period.</p>');
                        $('#chart_sales_value_by_campaign').show();
                    }
                });
            }
        </script>
    </div>
</div>

@if(in_array(Session::get('user-level'), ['marketing', 'management']))

<div class="large-6 columns">
    <div class="panel">
        <div class="panel-heading">
            <h1>Sales Volume By Sales Person</h1>
        </div>
        <div class="panel-content">
            <div class="row">
                <div class="large-12 columns">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_sales_volume_by_sales_person', 'style' => 'display:none')) }}
                    <div id="chart_sales_volume_by_sales_person"></div>
                </div>
            </div>
        </div>
        <script>

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(ajaxLoadMySalesDashboardSalesVolumeBySalesPersonChart);

            function ajaxLoadMySalesDashboardSalesVolumeBySalesPersonChart(){

                var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
                var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();


                $('#loader_chart_sales_volume_by_sales_person').show();
                $('#chart_sales_volume_by_sales_person').hide();

                $.ajax({
                    url: '/quotations/ajax/load-my-sales-dashboard-sales-volume-by-sales-person-chart?' +
                    'dashboard_filter_date_range=' + dashboard_filter_date_range +
                    '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                    '&dashboard_filter_to_date=' + dashboard_filter_to_date

                }).done(function (data) {

                    if(data != '[]'){

                        var sales_volume_by_sales_persons_chart_data = $.parseJSON(data);

                        /********  LEAD VOLUME BY CAMPAIGN  *********/

                        $('#loader_chart_sales_volume_by_sales_person').hide();
                        $('#chart_sales_volume_by_sales_person').show();

                        var sales_volume_by_sales_person_chart_data_array = [['Campaign', 'Campaign Volume']];

                        for(var i=1; i<= sales_volume_by_sales_persons_chart_data.length; i++) {
                            sales_volume_by_sales_person_chart_data_array[i] = [sales_volume_by_sales_persons_chart_data[i-1].sales_person, parseInt(sales_volume_by_sales_persons_chart_data[i-1].sales_volume)];
                        }

                        console.log(sales_volume_by_sales_person_chart_data_array);

                        var sales_volume_by_sales_person_data_chart = google.visualization.arrayToDataTable(sales_volume_by_sales_person_chart_data_array);

                        console.log(sales_volume_by_sales_person_data_chart);

                        var sales_volume_by_sales_person_options = {
                            title: null,
                            pieHole: 0.4,
                            height:300,
                            sliceVisibilityThreshold: 0,
                            legend: {
                                position: 'labeled'
                            }
                        };
                        var sales_volume_by_sales_person_chart = new google.visualization.PieChart(document.getElementById("chart_sales_volume_by_sales_person"));
                        sales_volume_by_sales_person_chart.draw(sales_volume_by_sales_person_data_chart, sales_volume_by_sales_person_options);

                        /********** END LEAD VOLUME BY CAMPAIGN  *********/


                    }else{

                        $('#loader_chart_sales_volume_by_sales_person').hide();
                        $('#chart_sales_volume_by_sales_person').html('<p class="alert-box info radius">No Sales Available For the Selected Time Period.</p>');
                        $('#chart_sales_volume_by_sales_person').show();
                    }
                });
            }
        </script>
    </div>
</div>
<div class="large-6 columns">
    <div class="panel">
        <div class="panel-heading">
            <h1>Sales Value By Sales Person</h1>
        </div>
        <div class="panel-content">
            <div class="row">
                <div class="large-12 columns">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_chart_sales_value_by_sales_person', 'style' => 'display:none')) }}
                    <div id="chart_sales_value_by_sales_person"></div>
                </div>
            </div>
        </div>
        <script>

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(ajaxLoadMySalesDashboardSalesValueBySalesPersonChart);

            function ajaxLoadMySalesDashboardSalesValueBySalesPersonChart(){

                var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
                var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
                var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();



                $('#loader_chart_sales_value_by_sales_person').show();
                $('#chart_sales_value_by_sales_person').hide();

                $.ajax({
                    url: '/quotations/ajax/load-my-sales-dashboard-sales-value-by-sales-person-chart?' +
                    'dashboard_filter_date_range=' + dashboard_filter_date_range +
                    '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                    '&dashboard_filter_to_date=' + dashboard_filter_to_date

                }).done(function (data) {

                    if(data != '[]'){

                        var sales_value_by_sales_persons_chart_data = $.parseJSON(data);

                        /********  LEAD VALUE BY CAMPAIGN  *********/

                        $('#loader_chart_sales_value_by_sales_person').hide();
                        $('#chart_sales_value_by_sales_person').show();

                        var sales_value_by_sales_person_chart_data_array = [['Campaign', 'Campaign Value']];

                        for(var i=1; i<= sales_value_by_sales_persons_chart_data.length; i++) {
                            sales_value_by_sales_person_chart_data_array[i] = [sales_value_by_sales_persons_chart_data[i-1].sales_person, parseInt(sales_value_by_sales_persons_chart_data[i-1].sales_value)];
                        }

                        console.log(sales_value_by_sales_person_chart_data_array);

                        var sales_value_by_sales_person_data_chart = google.visualization.arrayToDataTable(sales_value_by_sales_person_chart_data_array);

                        console.log(sales_value_by_sales_person_data_chart);

                        var sales_value_by_sales_person_options = {
                            title: null,
                            height:300,
                            sliceVisibilityThreshold: 0,
                            legend: {
                                position: 'labeled'
                            },
                            pieSliceText: 'value'
                        };

                        var sales_value_by_sales_person_chart = new google.visualization.PieChart(document.getElementById("chart_sales_value_by_sales_person"));
                        sales_value_by_sales_person_chart.draw(sales_value_by_sales_person_data_chart, sales_value_by_sales_person_options);

                        /********** END LEAD VALUE BY CAMPAIGN  *********/


                    }else{

                        $('#loader_chart_sales_value_by_sales_person').hide();
                        $('#chart_sales_value_by_sales_person').html('<p class="alert-box info radius">No Sales Available For the Selected Time Period.</p>');
                        $('#chart_sales_value_by_sales_person').show();
                    }
                });
            }
        </script>
    </div>
</div>

@endif

<div class="large-12 columns">
    <div class="panel">
        <div class="panel-heading">
            <h1>Contracted Sales</h1>
        </div>
        <div class="panel-content">
           @include('dashboard.common._partials.contracted_sales_widget')
        </div>
    </div>
</div>