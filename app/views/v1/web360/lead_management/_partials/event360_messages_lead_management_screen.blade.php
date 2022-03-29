@extends('layouts.v1_layout')

@section('content')

<div class="row page-title-bar">
    <div class="large-12 columns">
        <h1>Leads Management - Event360 Messages</h1>
    </div>
</div>

<div class="row expanded">
    <div class="large-6 columns">
        {{ link_to_route('leads.index', '< Back to Lead Management', null, array("class" => 'button tiny success')) }}
    </div>
</div>


<div class="panel">
    <div class="row expanded">
        <div class="large-6 columns">


            @include('v1.web360.lead_management._partials.event360_messenger_threads_table')


        </div>
        <div class="large-6 columns">
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_event360_messenger_thread_messages_list', 'style' => 'display:none')) }}

            <div id="event360_messenger_thread_messages_list">

            </div>

            <script>
                function loadEvent360MessengerThreadMessages(event360_messenger_thread_id) {



                    $('#loader_event360_messenger_thread_messages_list').show();
                    $('#event360_messenger_thread_messages_list').hide();
                    $('#event360_messenger_thread_messages_list').html('');


                    $.ajax({
                        url: '/event360-leads/ajax/get/event360_messenger_thread_messages?' +
                        'event360_messenger_thread_id=' + event360_messenger_thread_id

                    }).done(function (data) {
                        $('#event360_messenger_thread_messages_list').html(data);
                        $('#loader_event360_messenger_thread_messages_list').hide();
                        $('#event360_messenger_thread_messages_list').show();
                        $("#lead_details").tablesorter();
                        $('.datepicker').datetimepicker({
                            format: 'Y-m-d',
                            lang: 'en',
                            scrollInput: false
                        });
                        $(document).foundation();
                    });
                }
            </script>
        </div>
    </div>
</div>







@stop