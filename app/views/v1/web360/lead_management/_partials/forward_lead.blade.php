
    <div class="row expanded">
        <div class="large-6 columns">
            <h5>Lead Forwards</h5>
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('style' => 'display: none;' ,'class' => '', 'id' => 'loader_lead_forwards_table_'.$lead->id)) }}
            <div id="lead_forwards_{{ $lead->id }}">
                <?php $lead_forwards = $lead->leadForwards; ?>
                @include('v1.web360.lead_management._partials.lead_forwards_table')
            </div>
        </div>
        <div class="large-6 columns">
            {{ Form::hidden('lead_id', $lead->id, array('id' => 'lead_id_'.$lead->id)) }}
            <div class="row expanded" id="lead_forward_form_{{ $lead->id }}">
                <div class="large-12 columns">
                    {{ Form::label('to_email', 'To Email *', array('class'=>'control-label')) }}
                    <div class="controls">{{ Form::text('to_email', '', array('data-validation'=>'required', 'id' => 'to_email_'.$lead->id)) }}</div>
                    {{ $errors->first('to_email', '<p class="alert-box alert radius">:message</p>') }}
                </div>
                <div class="large-12 columns">
                    {{ Form::label('message', 'Message *', array('class'=>'control-label')) }}
                    <div class="controls">{{ Form::text('message', '', array('data-validation'=>'required', 'id' => 'message_'.$lead->id)) }}</div>
                    {{ $errors->first('message', '<p class="alert-box alert radius">:message</p>') }}
                </div>
                <div class="large-12 columns">
                    {{ Form::button('Send', array("class" => "button success tiny", 'id' => 'button_forward_lead_details_'.$lead->id, 'onclick' => 'forwardLeadDetails_'.$lead->id.'('.$lead->id.')')) }}
                </div>
            </div>
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('style' => 'display: none;' ,'class' => '', 'id' => 'loader_forward_lead_details_'.$lead->id)) }}
        </div>
    </div>


<script>

    function loadAjaxLeadForwardTable_<?php echo $lead->id; ?> (lead_id) {
        $('#loader_lead_forwards_table_').show();
        $('#lead_forwards_<?php echo $lead->id; ?>').hide();
        $('#lead_forwards_<?php echo $lead->id; ?>').html('');

        var search_query = $('#search_query').val();


        $.ajax({
            url: '/event360-leads/ajax/forwards/table?lead_id='+lead_id
        }).done(function (data) {
            $('#lead_forwards_<?php echo $lead->id; ?>').html(data);
            $('#loader_lead_forwards_table_<?php echo $lead->id; ?>').hide();
            $('#lead_forwards_<?php echo $lead->id; ?>').show();


        });
    }

    function forwardLeadDetails_<?php echo $lead->id; ?> (lead_id) {

        $('lead_forward_form_<?php echo $lead->id; ?>').hide();
        $('loader_forward_lead_details_<?php echo $lead->id; ?>').show();

        var to_email = $('#to_email_<?php echo $lead->id; ?>').val();
        var message = $('#message_<?php echo $lead->id; ?>').val();

        if(to_email != '' && message != '') {
            var r = confirm("Would you like to Forward this Lead?");
            if (r == true) {
                $.post("/event360-leads/ajax/forward/", {lead_id: lead_id, to_email: to_email, message: message}, function (data) {
                    $.notify('Forwarded Successfully.', 'success');
                    $('#to_email_<?php echo $lead->id; ?>').val("");
                    $('#message_<?php echo $lead->id; ?>').val("");

                    $('lead_forward_form_<?php echo $lead->id; ?>').show();
                    $('loader_forward_lead_details_<?php echo $lead->id; ?>').hide();

                    loadAjaxLeadForwardTable_<?php echo $lead->id; ?>(lead_id);

                });
            } else {
                return false;
            }
        } else {
            $.notify("Email and Message are both required fields.");
            return false;
        }




    }
</script>

