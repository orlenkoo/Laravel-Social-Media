{{ Form::open(array('route' => 'campaigns.ajax.campaign', 'ajax' => 'true', 'id' => 'add_new_campaign_form','autocomplete' => 'off')) }}
<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Add New Campaign</h4>
        </div>
    </div>
</div>

<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="campaign_form_campaign_name">
                Campaign Name *
                <input type="text" id="campaign_form_campaign_name" name="campaign_form_campaign_name" value="" placeholder="Campaign Name" data-validation="required">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="campaign_form_campaign_content">
                Campaign Content *
                <input type="text" id="campaign_form_campaign_content" name="campaign_form_campaign_content" value="" placeholder="Campaign Content" data-validation="required">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="campaign_form_call_tracking_number">
                Call Tracking Number *
                <input type="text" id="campaign_form_call_tracking_number" name="campaign_form_call_tracking_number" value="" placeholder="Call Tracking Number" data-validation="required">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="campaign_form_cost">
                Cost *
                <input type="text" id="campaign_form_cost" name="campaign_form_cost" value="" placeholder="Cost" class="numbersonly" data-validation="required">
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <label for="campaign_form_media_channels">
                Media Channels *
                <select name="campaign_form_media_channels[]" id="campaign_form_media_channels" data-validation="required" multiple>
                    <?php
                    $media_channels = json_decode(MediaChannel::getMediaChannelsFilters());
                    ?>
                    <option value=''>Media Channels</option>
                    @foreach($media_channels as $media_channel)
                        <option value='{{ $media_channel->id }}'>{{ $media_channel->media_channel }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="large-6 columns">
            <label for="campaign_form_start_date">
                Start Date *
                <input type="text" id="campaign_form_start_date" name="campaign_form_start_date" value="" placeholder="Start Date" class="datepicker" data-validation="required">
            </label>
        </div>
        <div class="large-6 columns">
            <label for="campaign_form_end_date">
                End Date *
                <input type="text" id="campaign_form_end_date" name="campaign_form_end_date" value="" placeholder="End Date" class="datepicker" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="campaign_form_point_of_contact">
                Point of Contact *
                <input type="text" id="campaign_form_point_of_contact" name="campaign_form_point_of_contact" value="" placeholder="Point of Contact" data-validation="required">
            </label>
        </div>
    </div>

</div>
<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-8 columns">
            &nbsp;
        </div>

        <div class="large-2 columns text-right">
            <input type="button" value="Clear" class="button tiny alert"
                   onclick="clearCampaignForm()">
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Save Campaign', array("class" => "button tiny", "id" => "campaign_form_save_button")) }}
        </div>
    </div>

    <div class="row loading_animation" style="display: none;">
        <div class="large-12 columns text-center">
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
        </div>
    </div>

</div>

{{ Form::close() }}


<script>
    $(document).ready(function() {
        setUpAjaxForm('add_new_campaign_form', 'create', '#reveal_add_new_campaign',
            function(){
                clearCampaignForm();
                ajaxLoadCampaignsList(1);
            }
        );
    });



    function clearCampaignForm() {
        $('#campaign_form_campaign_name').val('');
        $('#campaign_form_campaign_content').val('');
        $('#campaign_form_call_tracking_number').val('');
        $('#campaign_form_cost').val('');
        $('#campaign_form_start_date').val('');
        $('#campaign_form_end_date').val('');
        $('#campaign_form_point_of_contact').val('');
        var media_channels = $('#campaign_form_media_channels').selectize();
        var control_media_channels = media_channels[0].selectize;
        control_media_channels.clear();
    }


    function ajaxUpdateCampaignTableMediaChannel(campaign_id){

        var campaign_form_media_channels = $('#campaign_media_channels_'+campaign_id).val();

        $.post("/campaigns/ajax/update-campaign-table-media-channel",
            {
                campaign_id: campaign_id,
                campaign_form_media_channels: campaign_form_media_channels
            },
            function (data, status) {

                $.notify(data, "success");

            });

    }

    $(document).ready(function(){
        $('#campaign_form_media_channels').selectize({
            create: false,
            sortField: 'text'
        });
    });



</script>