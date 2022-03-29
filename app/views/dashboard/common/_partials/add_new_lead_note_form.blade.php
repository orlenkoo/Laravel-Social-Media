{{ Form::open(array('route' => 'leads.ajax.save_lead_note', 'ajax' => 'true', 'id' => 'new_note_form_'.$lead->id,'autocomplete' => 'off')) }}
<input type="hidden" name="lead_id" id="new_lead_note_form_lead_id_{{ $lead->id }}" value="{{ $lead->id }}">
<div class="row">
    <div class="large-12 columns">
        <h4>Add Note *</h4>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
                                <textarea name="lead_note"
                                          id="dashboard_lead_note_form_lead_note" rows="3"
                                          style="width: 100%; float: left;" placeholder="Enter lead note" data-validation="required"></textarea>
    </div>
</div>

<div class="row save_bar">
    <div class="large-8 columns">
        &nbsp;
    </div>
    <div class="large-2 columns text-right">
        <input type="button" value="Clear" class="button tiny alert"
               onclick="clearLeadNoteForm()">
    </div>
    <div class="large-2 columns text-right">
        {{ Form::submit('Save', array("class" => "button success tiny", "id" => "dashboard_lead_note_form_save_button_".$lead->id)) }}
    </div>
</div>
<div class="row loading_animation" style="display: none;">
    <div class="large-12 columns text-center">
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
    </div>
</div>

{{ Form::close() }}


<script>

    $(document).ready(function() {
        setUpAjaxForm('<?php echo 'new_note_form_'.$lead->id; ?>', 'create', '',
            function(){
                clearLeadNoteForm();
                ajaxLoadDashboardLeadNotesList(1);
            }
        );
    });


    function clearLeadNoteForm() {
        $('#dashboard_lead_note_form_lead_note').val('');
    }
</script>