{{ Form::open(array('route' => 'landing_pages.ajax.update_landing_page_details', 'id' => 'landing_page_details_form','autocomplete' => 'off')) }}

<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Landing Page Details</h4>
        </div>
    </div>
</div>


<input type="hidden" name="landing_page_id" id="landing_page_id" value="{{ $landing_page->id }}">

<div class="panel-content">

    <div class="row">
        <div class="large-12 columns">
            <label for="">
                Page Name *:
                <input type="text" name="page_name" id="add_new_landing_page_page_name" value="{{ $landing_page->page_name }}" placeholder="" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="">
                Url:
                <input type="text" name="url" id="add_new_landing_page_url" value="{{ $landing_page->url }}" placeholder="">
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


</div>
<div class="panel-footer">
    <div class="row">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns">
            <input type="button" value="Cancel" class="button tiny alert float-right" onclick="$('#reveal_landing_page_details_form').foundation('close');">
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
         setUpAjaxForm('landing_page_details_form', 'update', '#reveal_landing_page_details_form', function(){});
     });


    function getLandingPageDetailsCampaignsList() {
        $.ajax({
            url: '/campaigns/ajax/get-campaigns-list'
        }).done(function(data){
            $('#add_new_landing_page_campaign_id').empty();
            data = $.parseJSON(data);
            $('#add_new_landing_page_campaign_id').append("<option value=''>Select Campaign</option>");

            for(var i in data)
            {
                if(data[i].id == '{{ $landing_page->campaign_id }}'){
                    $('#add_new_landing_page_campaign_id').append("<option value='" + data[i].id +"' selected>" + data[i].campaign_name + "</option>");
                }else{
                    $('#add_new_landing_page_campaign_id').append("<option value='" + data[i].id +"'>" + data[i].campaign_name + "</option>");
                }

            }

            $('#add_new_landing_page_campaign_id').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    getLandingPageDetailsCampaignsList();

</script>


</script>