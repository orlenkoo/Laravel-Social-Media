<?php
$date = date('Y-m-d');

$date_from = Session::has('dashboard_from_date') ? Session::get('dashboard_from_date') : date('Y-m-d') ;
$date_to = Session::has('dashboard_to_date') ? Session::get('dashboard_to_date') : date('Y-m-d') ;
$date_range = Session::has('dashboard_date_range') ? Session::get('dashboard_date_range') : 'Today';
$date_readonly_status =  $date_range == "Custom"? "": "readonly";
?>

<div class="row filter-box">
    <div class="large-4 columns">
        <label>Date Period
            <select name="dashboard_filter_date_range" id="dashboard_filter_date_range" onchange="enableDatePickers(this.value); sessionDateFilter();">
                @foreach(DateFilterUtilities::$date_ranges as $date_range_value)
                    <option value="{{ $date_range_value }}" {{ $date_range == $date_range_value? "selected": "" }}>{{ $date_range_value }}</option>
                @endforeach
            </select>
        </label>
    </div>
    <div class="large-4 columns">
        <label>From Date
            <input type="text" class="datepicker" name="dashboard_filter_from_date" id="dashboard_filter_from_date" {{ $date_readonly_status }} value="{{ $date_from }}" onchange="sessionDateFilter();">
        </label>
    </div>
    <div class="large-4 columns">
        <label>To Date
            <input type="text" class="datepicker" name="dashboard_filter_to_date" id="dashboard_filter_to_date" {{ $date_readonly_status }} value="{{ $date_to }}" onchange="sessionDateFilter();">
        </label>
    </div>
</div>

<script>
    function enableDatePickers(filter_date_range) {
        if(filter_date_range == 'Custom') {
            $("#dashboard_filter_from_date").prop("readonly", false);
            $("#dashboard_filter_to_date").prop("readonly", false);
        } else {
            $("#dashboard_filter_from_date").prop("readonly", true).val('{{ $date }}');
            $("#dashboard_filter_to_date").prop("readonly", true).val('{{ $date }}');

            if(filter_date_range == 'Yesterday') {
                $("#dashboard_filter_from_date").prop("readonly", true).val('{{ date('Y-m-d', strtotime('-1 day')); }}');
                $("#dashboard_filter_to_date").prop("readonly", true).val('{{ date('Y-m-d', strtotime('-1 day')); }}');
            }

            if(filter_date_range == 'Last Week') {

                $("#dashboard_filter_from_date").prop("readonly", true).val('{{ date('Y-m-d',strtotime('monday last week')); }}');
                $("#dashboard_filter_to_date").prop("readonly", true).val('{{ date('Y-m-d',strtotime('sunday last week'));  }}');
            }

            if(filter_date_range == 'Last Month') {
                <?php $month_ini = new DateTime("first day of last month");
                  $month_end = new DateTime("last day of last month"); ?>

            $("#dashboard_filter_from_date").prop("readonly", true).val('{{ $month_ini->format('Y-m-d'); }}');
                $("#dashboard_filter_to_date").prop("readonly", true).val('{{ $month_end->format('Y-m-d'); }}');
            }

            if(filter_date_range == 'Last 7 Days') {
                $("#dashboard_filter_from_date").prop("readonly", true).val('{{ date('Y-m-d', strtotime('-1 week')); }}');
                $("#dashboard_filter_to_date").prop("readonly", true).val('{{ $date }}');
            }

            if(filter_date_range == 'Last 30 Days') {
                $("#dashboard_filter_from_date").prop("readonly", true).val('{{ date('Y-m-d', strtotime('-1 month')); }}');
                $("#dashboard_filter_to_date").prop("readonly", true).val('{{ $date }}');
            }
        }
    }

    function sessionDateFilter(){
        var dashboard_filter_date_range = $('#dashboard_filter_date_range').val();
        var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
        var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

        console.log('dashboard_filter_date_range -- ' + dashboard_filter_date_range);
        console.log('dashboard_filter_from_date -- ' + dashboard_filter_from_date);
        console.log('dashboard_filter_to_date -- ' + dashboard_filter_to_date);

        $.post("/common-utility/ajax/post-sessions-date-range",
            {
                dashboard_filter_date_range: dashboard_filter_date_range,
                dashboard_filter_from_date: dashboard_filter_from_date,
                dashboard_filter_to_date: dashboard_filter_to_date,
            },
            function (data, status) {
                $.notify(data, "success");
                loadDashboardData();
            });
    }
</script>