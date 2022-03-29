{{ Form::open(array('route' => 'campaigns.ajax.campaign_url', 'ajax' => 'true', 'id' => 'add_new_campaign_url_form_'.$campaign->id,'autocomplete' => 'off')) }}
<input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
<div class="row">
    <div class="large-12 columns">
        <label for="">
            Website URL * <span data-tooltip tabindex="1" title="The full website URL (e.g. https://www.example.com)">?</span>
            <input type="text" name="website_url" id="campaign_url_website_url_{{ $campaign->id }}" value="">
        </label>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <label for="">
            Campaign Source * <span data-tooltip tabindex="1" title="The referrer: (e.g. google, newsletter)">?</span>
            <input type="text" name="campaign_source" id="campaign_url_campaign_source_{{ $campaign->id }}" value="">
        </label>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <label for="">
            Campaign Medium <span data-tooltip tabindex="1" title="Marketing medium: (e.g. cpc, banner, email)">?</span>
            <input type="text" name="campaign_medium" id="campaign_url_campaign_medium_{{ $campaign->id }}" value="">
        </label>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <label for="">
            Campaign Name <span data-tooltip tabindex="1" title="Product, promo code, or slogan (e.g. spring_sale)">?</span>
            <input type="text" name="campaign_name" id="campaign_url_campaign_name_{{ $campaign->id }}" value="{{ $campaign->campaign_name }}">
        </label>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <label for="">
            Campaign Term <span data-tooltip tabindex="1" title="Identify the paid keywords">?</span>
            <input type="text" name="campaign_term" id="campaign_url_campaign_term_{{ $campaign->id }}" value="">
        </label>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <label for="">
            Campaign Content <span data-tooltip tabindex="1" title="Use to differentiate ads">?</span>
            <input type="text" name="campaign_content" id="campaign_url_campaign_content_{{ $campaign->id }}" value="{{ $campaign->campaign_content }}">
        </label>
    </div>
</div>
<div class="row save_bar">
    <div class="large-8 columns">
        &nbsp;
    </div>
    <div class="large-2 columns text-right">
        <input type="button" value="Clear" class="button tiny alert"
               onclick="clearCampaignURLForm_{{ $campaign->id }}()">
    </div>
    <div class="large-2 columns text-right">
        {{ Form::submit('Save', array("class" => "button success tiny")) }}
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
        setUpAjaxForm('add_new_campaign_url_form_{{ $campaign->id }}', 'create', '',
            function(){
                clearCampaignURLForm_{{ $campaign->id }}();
                //ajaxLoadCampaignsList(1);
                ajaxLoadCampaignsURLsList_{{ $campaign->id }}(1);
            }
        );
    });



    function clearCampaignURLForm_{{ $campaign->id }}() {
        $('#campaign_url_website_url_{{ $campaign->id }}').val('');
        $('#campaign_url_campaign_source_{{ $campaign->id }}').val('');
        $('#campaign_url_campaign_medium_{{ $campaign->id }}').val('');
        $('#campaign_url_campaign_name_{{ $campaign->id }}').val('');
        $('#campaign_url_campaign_term_{{ $campaign->id }}').val('');
        $('#campaign_url_campaign_content_{{ $campaign->id }}').val('');
    }
</script>