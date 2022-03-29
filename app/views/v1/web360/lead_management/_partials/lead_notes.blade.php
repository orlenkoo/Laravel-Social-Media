
    <div class="row expanded">
        <div class="large-6 columns">
            <h5>Lead Notes</h5>
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('style' => 'display: none;' ,'class' => '', 'id' => 'loader_lead_notes_table_'.$lead->id)) }}
            <div id="lead_notes_{{ $lead->id }}">
                <?php $lead_notes = $lead->leadNotes; ?>

                @include('v1.web360.lead_management._partials.lead_notes_table')

            </div>
            <script>
                function loadAjaxLeadNotesTable_<?php echo $lead->id; ?> (lead_id) {
                    $('#loader_lead_notes_table_<?php echo $lead->id; ?>').show();
                    $('#lead_notes_<?php echo $lead->id; ?>').hide();
                    $('#lead_notes_<?php echo $lead->id; ?>').html('');

                    $.ajax({
                        url: '/event360-leads/ajax/notes/table?lead_id='+lead_id
                    }).done(function (data) {
                        $('#lead_notes_<?php echo $lead->id; ?>').html(data);
                        $('#loader_lead_notes_table_<?php echo $lead->id; ?>').hide();
                        $('#lead_notes_<?php echo $lead->id; ?>').show();


                    });
                }

            </script>
        </div>
        <div class="large-6 columns">
            {{ Form::hidden('lead_id', $lead->id, array('id' => 'lead_id_'.$lead->id)) }}
            <div class="row expanded" id="lead_note_form_{{ $lead->id }}">
                <div class="large-12 columns">
                    {{ Form::label('note', 'Note *', array('class'=>'control-label')) }}
                    <div class="controls">{{ Form::textarea('note', '', array('data-validation'=>'required', 'id' => 'note_'.$lead->id)) }}</div>
                    {{ $errors->first('note', '<p class="alert-box alert radius">:message</p>') }}
                </div>
                <div class="large-12 columns">
                    {{ Form::button('Save', array("class" => "button success tiny", 'id' => 'button_lead_note_details_'.$lead->id, 'onclick' => 'saveAjaxLeadNotes_'.$lead->id.'('.$lead->id.')')) }}
                </div>
            </div>

            {{ HTML::image('assets/img/loading.gif', 'Loading', array('style' => 'display: none;' ,'class' => '', 'id' => 'loader_lead_note_details_'.$lead->id)) }}



            <script>



                function saveAjaxLeadNotes_<?php echo $lead->id; ?> (lead_id) {

                    $('lead_note_form_<?php echo $lead->id; ?>').hide();
                    $('loader_lead_note_details_<?php echo $lead->id; ?>').show();

                    var note = $('#note_<?php echo $lead->id; ?>').val();

                    if(note != '') {
                        var r = confirm("Would you like to save this Note?");
                        if (r == true) {
                            $.post("/event360-leads/ajax/save-note/", {lead_id: lead_id, note: note}, function (data) {
                                $.notify('Lead Note Added Successfully.', "success");
                                $('#note_<?php echo $lead->id; ?>').val("");

                                $('lead_note_form_<?php echo $lead->id; ?>').show();
                                $('loader_lead_note_details_<?php echo $lead->id; ?>').hide();

                                loadAjaxLeadNotesTable_<?php echo $lead->id; ?>(lead_id);

                            });
                        } else {
                            return false;
                        }
                    } else {
                        $.notify("Note is a required field.");
                        return false;
                    }




                }
            </script>
        </div>
    </div>




