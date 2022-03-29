{{ Form::open(array('route' => 'email_module.ajax.save_new_email_campaign', 'ajax' => 'true', 'id' => 'new_email_campaign_form','autocomplete' => 'off')) }}
    <div class="row">
        <div class="large-12 columns">
            <h5>Create New Email Campaign</h5>
            <em>Enter following values to get started.</em>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="campaign_name">
                Campaign Name *
                <input type="text" name="campaign_name" id="campaign_name" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="start_date_time">
                Start Date Time *
                <input type="text" name="start_date_time" id="start_date_time" class="datetimepicker" data-validation="required">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="end_date_time">
                End Date Time *
                <input type="text" name="end_date_time" id="end_date_time" class="datetimepicker" data-validation="required">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns text-right">
            <input type="button" value="Cancel" class="button tiny alert" onclick="$('#popup_add_new_campaign_form').foundation('close');">
        </div>
        <div class="large-2 columns text-right">

            <div class="row save_bar">
                <div class="large-12 columns text-center">
                    {{ Form::submit('Save', array("class" => "button success tiny", "id" => "new_email_campaign_form_save_button")) }}

                </div>
            </div>

            <div class="row loading_animation" style="display: none;">
                <div class="large-12 columns text-center">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
                </div>
            </div>
        </div>
    </div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        setUpAjaxForm('new_email_campaign_form', 'create', '#popup_add_new_email_campaign_form', function(response){
            afterSavingNewEmailCampaign(response);
        });
        resetForm('new_email_campaign_form');
    });

    function afterSavingNewEmailCampaign(response) {
        var email_campaign_id = response.email_campaign_id
        console.log(email_campaign_id);
        if (email_campaign_id) {
           window.location = '/email-module/email-campaign-management-screen?email_campaign_id=' + email_campaign_id;
        }
    }
</script>