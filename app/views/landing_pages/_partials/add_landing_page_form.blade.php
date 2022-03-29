{{ Form::open(array('route' => 'landing_pages.ajax.add_landing_page', 'id' => 'add_landing_page_form','autocomplete' => 'off')) }}

<div class="row">
    <div class="large-12 columns">
        <h4>Add Landing Page</h4>
        <hr>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <label for="">
            Page Name *:
            <input type="text" name="page_name" id="add_new_landing_page_page_name" value="" placeholder="" data-validation="required">
        </label>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <label for="">
            Url:
            <input type="text" name="url" id="add_new_landing_page_url" value="" placeholder="">
        </label>
    </div>
</div>


<div class="row">
    <div class="large-12 columns">
        <label for="">
            Campaign:
            <select name="campaign_id" id="add_new_landing_page_campaign_id">

            </select>
        </label>
    </div>
</div>




<div class="row">
    <div class="large-8 columns">
        <label for="">
            Host:
            <input type="text" name="ftp_host" id="add_new_landing_page_host" value="" placeholder="">
        </label>
    </div>
    <div class="large-4 columns">
        <label for="">
            Port:
            <input type="text" name="ftp_port" id="add_new_landing_page_port" value="" placeholder="">
        </label>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <label for="">
            Username:
            <input type="text" name="ftp_user_name" id="add_new_landing_page_username" value="" placeholder="">
        </label>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <label for="">
            Password:
            <input type="text" name="ftp_password" id="add_new_landing_page_password" value="" placeholder="">
        </label>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <label for="">
            Path:
            <input type="text" name="ftp_path" id="add_new_landing_page_path" value="" placeholder="">
        </label>
    </div>
</div>

<div class="row">
    <div class="large-8 columns">
        &nbsp;
    </div>
    <div class="large-2 columns text-right">
        <input type="button" value="Cancel" class="button tiny alert" onclick="$('#reveal_add_new_landing_page').foundation('close');">
    </div>
    <div class="large-2 columns text-right">

        <div class="row save_bar">
            <div class="large-12 columns text-center">
                {{ Form::submit('Next', array("class" => "button success tiny", "id" => "")) }}

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

    function getAddNewLandingPageCampaignsList() {
        $.ajax({
            url: '/campaigns/ajax/get-campaigns-list'
        }).done(function(data){
            $('#add_new_landing_page_campaign_id').empty();
            data = $.parseJSON(data);
            $('#add_new_landing_page_campaign_id').append("<option value=''>Select Campaign</option>");

            for(var i in data)
            {
                $('#add_new_landing_page_campaign_id').append("<option value='" + data[i].id +"'>" + data[i].campaign_name + "</option>");
            }

            $('#add_new_landing_page_campaign_id').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }
    getAddNewLandingPageCampaignsList();

</script>