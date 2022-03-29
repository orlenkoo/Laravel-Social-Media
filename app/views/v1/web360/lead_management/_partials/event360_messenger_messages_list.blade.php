<div class="row expanded">
    <div class="large-12 columns">
        <h5>Messages</h5>
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('style' => 'display: none;' ,'class' => '', 'id' => 'loader_messages_table_'.$event360_messenger_thread_id)) }}
        <div id="messages_table_{{ $event360_messenger_thread_id }}">


            @include('v1.web360.lead_management._partials.messages_table')

        </div>
        <script>
            function loadAjaxMessagesTable_<?php echo $event360_messenger_thread_id; ?> (event360_messenger_thread_id) {
                $('#loader_messages_table_<?php echo $event360_messenger_thread_id; ?>').show();
                $('#messages_table_<?php echo $event360_messenger_thread_id; ?>').hide();
                $('#messages_table_<?php echo $event360_messenger_thread_id; ?>').html('');

                $.ajax({
                    url: '/event360-leads/ajax/messages/table?event360_messenger_thread_id='+event360_messenger_thread_id
                }).done(function (data) {
                    $('#messages_table_<?php echo $event360_messenger_thread_id; ?>').html(data);
                    $('#loader_messages_table_<?php echo $event360_messenger_thread_id; ?>').hide();
                    $('#messages_table_<?php echo $event360_messenger_thread_id; ?>').show();


                });
            }

        </script>
    </div>
    </div>
<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::hidden('event360_messenger_thread_id', $event360_messenger_thread_id, array('id' => 'lead_id_'.$event360_messenger_thread_id)) }}
        <div class="row expanded" id="message_form_{{ $event360_messenger_thread_id }}">
            <div class="large-12 columns">
                {{ Form::label('message', 'Message *', array('class'=>'control-label')) }}
                <div class="controls">{{ Form::textarea('message', '', array('data-validation'=>'required', 'id' => 'message_'.$event360_messenger_thread_id)) }}</div>
                {{ $errors->first('message', '<p class="alert-box alert radius">:message</p>') }}
            </div>
            <div class="large-12 columns">
                {{ Form::button('Send', array("class" => "button success tiny", 'id' => 'button_lead_messages_'.$event360_messenger_thread_id, 'onclick' => 'saveAjaxMessage_'.$event360_messenger_thread_id.'('.$event360_messenger_thread_id.')')) }}
            </div>
        </div>

        {{ HTML::image('assets/img/loading.gif', 'Loading', array('style' => 'display: none;' ,'class' => '', 'id' => 'loader_message_form_'.$event360_messenger_thread_id)) }}



        <script>



            function saveAjaxMessage_<?php echo $event360_messenger_thread_id; ?> (event360_messenger_thread_id) {

                $('message_form_<?php echo $event360_messenger_thread_id; ?>').hide();
                $('loader_message_form_<?php echo $event360_messenger_thread_id; ?>').show();

                var message = $('textarea#message_<?php echo $event360_messenger_thread_id; ?>').val();


                if(message != '') {
                    var r = confirm("Would you like to send this Message?");
                    if (r == true) {
                        $.post("/event360-leads/ajax/save-message/", {event360_messenger_thread_id: event360_messenger_thread_id, message: message}, function (data) {
                            $.notify('Message Sent Successfully.','success');
                            $('textarea#message_<?php echo $event360_messenger_thread_id; ?>').val("");

                            $('message_form_<?php echo $event360_messenger_thread_id; ?>').show();
                            $('loader_message_form_<?php echo $event360_messenger_thread_id; ?>').hide();

                            loadAjaxMessagesTable_<?php echo $event360_messenger_thread_id; ?>(event360_messenger_thread_id);
                            getAjaxEvent360MessengerThreadLeadsList(1);
                        });
                    } else {
                        return false;
                    }
                } else {
                    $.notify("Message is a required field.");
                    return false;
                }




            }
        </script>
    </div>
</div>


