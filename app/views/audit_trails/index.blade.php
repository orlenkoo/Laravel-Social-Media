@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="employee_filter">
                                Employee:
                                <select name="employee_filter" id="employee_filter" multiple>
                                </select>
                            </label>
                            <label for="user_level_filter">
                                User Level:
                                <select name="user_level_filter" id="user_level_filter" multiple>
                                    <option value="">User Level</option>
                                    @foreach( Employee::$user_levels as $key => $user_level)
                                        <option value="{{ $key }}">{{ $user_level }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-6 columns text-right">
                            <input type="button" value="Clear Filter" class="alert button tiny" style="width: 100%;" onclick="clearLeadsFilter()">
                        </div>
                        <div class="large-6 columns text-right">
                            <input type="button" class="button tiny success" value="Search" style="margin-top: 0px; width: 100%;" onclick="ajaxLoadAuditTrailsList(1)">
                        </div>
                    </div>

                    <script>
                        function clearLeadsFilter() {
                            var user_level_filter = $('#user_level_filter').selectize();
                            var control_user_level_filter = user_level_filter[0].selectize;
                            control_user_level_filter.clear();

                            var employee_filter = $('#employee_filter').selectize();
                            var control_employee_filter = employee_filter[0].selectize;
                            control_employee_filter.clear();

                            ajaxLoadAuditTrailsList(1);
                        }
                    </script>
                </div>
            </div>
        </div>
        <div class="large-9 columns">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row expanded">
                        <div class="large-6 columns">
                            <h1>Audit Trails</h1>
                        </div>
                    </div>
                </div>
                <div class="panel-content">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_audit_trails_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                    <div id="response_audit_trails_list_div">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        getAssignedToEmployeesList();
        ajaxLoadAuditTrailsList(1);

        /*==================== PAGINATION =========================*/

        $(document).on('click', '#pagination_audit_trails_list a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            //location.hash = page;
            ajaxLoadAuditTrailsList(page);
        });

        function ajaxLoadAuditTrailsList(page) {
            $('#loader_audit_trails_list').show();
            $('#response_audit_trails_list_div').hide();

            var employee_filter = $("#employee_filter").val();
            var user_level_filter = $("#user_level_filter").val();
            var dashboard_filter_date_range = $("#dashboard_filter_date_range").val();
            var dashboard_filter_from_date = $("#dashboard_filter_from_date").val();
            var dashboard_filter_to_date = $("#dashboard_filter_to_date").val();

            $.ajax({
                url: '/audit-trails/ajax/load-audit-trails-list?' +
                'page=' + page +
                '&employee_filter=' + employee_filter +
                '&user_level_filter=' + user_level_filter +
                '&dashboard_filter_date_range=' + dashboard_filter_date_range +
                '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                '&dashboard_filter_to_date=' + dashboard_filter_to_date

            }).done(function (data) {
                $('#response_audit_trails_list_div').html(data);
                $('#loader_audit_trails_list').hide();
                $('#response_audit_trails_list_div').show();
                $(document).foundation();
                $('.datepicker').datetimepicker({
                    timepicker: false,
                    format: 'Y-m-d',
                    lang: 'en',
                    scrollInput: false
                });
            });
        }

        function loadDashboardData() {
            ajaxLoadAuditTrailsList(1);
        }

        function getAssignedToEmployeesList() {
            $.ajax({
                url: '/employees/ajax/get-employees-list'
            }).done(function (data) {
                $('#employee_filter').empty();
                data = $.parseJSON(data);
                $('#employee_filter').append("<option value=''>Employee</option>");

                for (var i in data) {
                    $('#employee_filter').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                }

                $('#employee_filter').selectize({
                    create: false,
                    sortField: 'text'
                });
                $('#user_level_filter').selectize({
                    create: false,
                    sortField: 'text'
                });
            });
        }

    </script>

@stop