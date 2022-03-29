<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Task Calendar</h4>
        </div>
    </div>
</div>

<div class="panel-content">
    <div id='calendar'></div>
    <div class="reveal large" id="reveal_edit_calender_task" name="reveal_edit_calender_task" data-reveal>
        <div class="panel-content">
            <div class="row">
                <div class="large-12 columns" id="edit_calender_task_form">

                </div>
            </div>
            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>


<script>



    function getTasksCalender(){
        $('#calendar').fullCalendar({
            defaultDate: '{{ date("Y-m-d") }}',
            editable: true,
            eventLimit: true,
            events: '/tasks/ajax/get-all-tasks-for-calender',
            dayClick: function(date) {

                //set add form start and end date to the add task form
                $("#new_task_form_from_date_time").val(date.format());
                $("#new_task_form_to_date_time").val(date.format());
                $('#reveal_add_new_task').foundation('toggle');
            },
            eventResize: function(event, delta, revertFunc) {

                var task_id = event.id;
                var end = event.end.format();

                if (!confirm("is this okay?")) {
                    revertFunc();
                }else{
                    ajaxUpdateTaskDataTime(task_id,'resize','',end);
                }

            },
            eventDrop: function(event, delta, revertFunc) {

                var task_id = event.id;
                var start = event.start.format();
                var end = '';
                if(event.end){
                    end = event.end.format();
                }

                if (!confirm("Are you sure about this change?")) {
                    revertFunc();
                }else{
                    ajaxUpdateTaskDataTime(task_id,'drop',start,end);
                }


            },
            eventClick: function(calEvent, jsEvent, view) {

                console.log(calEvent);
                ajaxLoadUpdateTaskForm(calEvent.id);

            }
        });


    }

    function refreshTaskCalender(){
        $('#calendar').fullCalendar( 'refetchEvents' );
    }

    function ajaxUpdateTaskDataTime(id,event,start,end){

        $.post("/tasks/ajax/update-task-date-time",
                {
                    task_id: id,
                    event: event,
                    start: start,
                    end: end
                },
                function (data, status) {
                    $.notify(data,"success");
                    ajaxLoadTasksList(1);
                });

    }



</script>