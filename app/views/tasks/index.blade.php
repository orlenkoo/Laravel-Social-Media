@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-3 columns">
            <div class="panel">
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Search by Title:
                                <input type="text" id="search_task_lists" name="search_task_lists" value="" placeholder="Search by Title" data-validation="required">
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Filter by Date Type:
                                <select name="filter_by_date_type" id="filter_by_date_type" >
                                    <option value="Creation" selected="selected" >Creation</option>
                                    <option value="Due Date">Due Date</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Sort by:
                                <select name="sort_by" id="sort_by" >
                                    <option value="date" selected="selected" >Date</option>
                                    <option value="title">Title</option>
                                    <option value="location">Location</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Sort Order:
                                <select name="sort_order" id="sort_order" >
                                    <option value="asc" selected="selected" >Ascending</option>
                                    <option value="desc">Descending</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <label for="">
                                Completed Tasks:
                                <input type="checkbox" name="completed_tasks_checkbox" id="completed_tasks_checkbox" value="" onclick="" >
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-6 columns text-right">
                            <input type="button" value="Clear Filters" class="alert button tiny" style="width: 100%;" onclick="clearFilters()">
                        </div>
                        <div class="large-6 columns text-right">
                            <input type="button" class="button tiny success" value="Search" style="margin-top: 0px; width: 100%;" onclick="loadDashboardData()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="large-9 columns">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row expanded">
                        <div class="large-8 columns">
                            <h1>Tasks</h1>
                        </div>
                        <div class="large-2 columns">
                            <button class="button tiny success float-right" type="button" data-open="reveal_calendar_view" onclick="getTasksCalender();">Calendar View</button>
                            <div class="reveal panel large reveal_calendar_view" id="reveal_calendar_view" name="reveal_calendar_view" data-reveal>
                                @include('tasks._partials.calendar')
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="large-2 columns">
                            <button class="button tiny float-right" type="button" data-open="reveal_add_new_task">Add New Task</button>
                            <div class="reveal panel large reveal_add_new_task" id="reveal_add_new_task" name="reveal_add_new_task" data-reveal>
                                @include('tasks._partials.add_new_task_form', [
                                                              'activity_type' => '',
                                                              'activity_object' => ''
                                                          ])
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-content">
                    <div class="row expanded">
                        <div class="large-12 columns">
                            <input type="button" value="Mark as Done" class="button tiny warning" onclick="ajaxUpdateMarkAsDoneStatus();">
                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_tasks_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                            <div id="response_tasks_list">

                            </div><!--end response_email_templates_tags_list-->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        /*==================== PAGINATION =========================*/

        $(document).on('click', '#pagination_tasks_list a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            ajaxLoadTasksList(page);
        });


        function loadDashboardData() {
            ajaxLoadTasksList(1);
        }

        function ajaxLoadTasksList(page) {
            $('#loader_tasks_list').show();
            $('#response_tasks_list').hide();

            var search_task_lists = $("#search_task_lists").val();
            var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
            var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
            var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();
            var completed_tasks = (document.getElementById("completed_tasks_checkbox").checked) ? 1 : 0 ;
            var filter_by_date_type = $("#filter_by_date_type").val();
            var sort_by = $("#sort_by").val();
            var sort_order = $("#sort_order").val();

            $.ajax({
                url: '/tasks/ajax/load-tasks-list?' +
                'page=' + page +
                '&dashboard_filter_date_range=' + dashboard_filter_date_range +
                '&dashboard_filter_from_date=' + dashboard_filter_from_date +
                '&dashboard_filter_to_date=' + dashboard_filter_to_date +
                '&search_task_lists=' + search_task_lists +
                '&filter_by_date_type=' + filter_by_date_type +
                '&sort_by=' + sort_by +
                '&sort_order=' + sort_order +
                '&completed_tasks=' + completed_tasks

            }).done(function (data) {
                $('#response_tasks_list').html(data);
                $('#loader_tasks_list').hide();
                $('#response_tasks_list').show();
                $(document).foundation();
                $('.datetimepicker').datetimepicker({
                    datepicker: true,
                    format: 'Y-m-d H:i',
                    step : 30,
                    scrollInput: false
                });
            });
        }

        $( document ).ready(function() {
            loadDashboardData();
        });

        function ajaxUpdateMarkAsDoneStatus(task_id){

            var mark_done = [];

            if (task_id !== undefined) { //selected to un mark as done
                mark_done.push(task_id);
            }else{ //selected ones to mark as done
                $("input:checkbox[name=mark_as_done]:checked").each(function () {
                    mark_done.push($(this).val());
                });
            }

            if(mark_done.length == 0){
                $.notify('Please select task.');
                return;
            }

            $.post("/tasks/ajax/update-mark-as-done-status",
                {
                    mark_done: mark_done
                },
                function (data, status) {
                    ajaxLoadTasksList(1);
                    $.notify(data,"success");
                    refreshTaskCalender();
                });

        }

        function markAllAsDone(){
            $("input:checkbox[name=mark_as_done]").each(function () {
                $(this).prop('checked', 'checked');
            });
        }

        function UnmarkAllAsDone(){
            $("input:checkbox[name=mark_as_done]").each(function () {
                $(this).prop('checked', '');
            });
        }


        function clearFilters(){
            $("#search_task_lists").val('');
            $("#filter_by_date_type").val('Creation');
            $("#sort_by").val('date');
            $("#sort_order").val('ascending');
            $("#completed_tasks_checkbox").prop('checked', '');
        }

        function ajaxLoadUpdateTaskForm(id){

            $('#edit_calender_task_form').html('')

            $.ajax({
                url: '/tasks/ajax/load-update-task-form?' +
                'task_id=' + id

            }).done(function (data) {

                $('#edit_calender_task_form').html(data);
                $(document).foundation();
                $('.datetimepicker').datetimepicker({
                    datepicker: true,
                    format: 'Y-m-d H:i',
                    step : 30,
                    scrollInput: false
                });
                $('#reveal_edit_calender_task').foundation('open');

            });


        }

    </script>

@stop