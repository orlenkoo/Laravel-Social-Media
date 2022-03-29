{{ Form::open(array('route' => 'settings.ajax.save_media_channel', 'ajax' => 'true', 'id' => 'add_new_media_channel','autocomplete' => 'off')) }}
<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h5>Add Media Channel</h5>
        </div>
    </div>
</div>

<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="media_channel">
                Media Channel *
                <input type="text" id="media_channel_form_media_channel" name="media_channel" data-validation="required">
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
            <input type="button" value="Clear" class="button tiny alert float-right" onclick="clearMediaChannelForm()">
        </div>
        <div class="large-2 columns text-right">

            <div class="row save_bar">
                <div class="large-12 columns text-center">
                    {{ Form::submit('Save', array("class" => "button success tiny float-right", "id" => "media_channel_form_save_button")) }}
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
        setUpAjaxForm('add_new_media_channel', 'create', '#newMediaChannelForm',
            function(){
                clearMediaChannelForm();
                ajaxLoadMediaChannelsList(1);
            }, function(){}
        );
    });


    function clearMediaChannelForm() {
        $('#media_channel_form_media_channel').val('');
    }
</script>
