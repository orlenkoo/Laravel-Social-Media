{{ Form::open(array('route' => 'email_module.ajax.edit_email_content', 'ajax' => 'true', 'id' => 'edit_email_content_form','autocomplete' => 'off')) }}
<input type="hidden" name="email_campaign_id" id="email_campaign_id" value="{{ $email_campaign->id }}">
<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Update Email Content</h4>
        </div>
    </div>
</div>
<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="">
                Email Content
                {{ Form::textarea('email_campaign_email_content','', array('id' => 'email_campaign_email_content_'.$email_campaign->id,'data-validation'=>'')) }}
                <script>
                    $( document ).ready(function() {
                        var email_content = '<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', trim($email_campaign->email_content)); ?>';
                        setupCKEditor('email_campaign_email_content_<?php echo $email_campaign->id; ?>',email_content,'Full');
                    });
                </script>
            </label>
        </div>
    </div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns">
            <input type="button" value="Cancel" class="button tiny alert float-right" onclick="$('#popup_update_content').foundation('close');">
        </div>
        <div class="large-2 columns text-right">

            <div class="row save_bar">
                <div class="large-12 columnsr">
                    {{ Form::submit('Save', array("class" => "button success tiny float-right", "id" => "new_email_campaign_form_save_button")) }}

                </div>
            </div>

            <div class="row loading_animation" style="display: none;">
                <div class="large-12 columns text-center">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
                </div>
            </div>
        </div>
    </div>
</div>



{{ Form::close() }}

<script>
    $(document).ready(function() {
        setUpAjaxForm('edit_email_content_form', 'create', '#popup_update_content', function(){
            document.getElementById('email_content_preview_iframe').contentDocument.location.reload(true);
        }, function(){});
    });


</script>

