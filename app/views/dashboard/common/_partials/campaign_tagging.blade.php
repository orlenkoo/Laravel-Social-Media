@if($lead->auto_tagged_campaign)
    <p>Campaign Auto Tagged As: <strong>{{ $lead->auto_tagged_campaign }}</strong></p>
@endif
{{--<label for="">--}}
{{--Campaign:--}}
{{--<select name="campaign" id="campaign" onchange="ajaxUpdateCampaign()">--}}
{{--</select>--}}
{{--</label>--}}


<script>
    //getCampaignsList();
    function getCampaignsList() {
        $.ajax({
            url: '/campaigns/ajax/get-campaigns-list'
        }).done(function(data){
            $('#campaign').empty();
            data = $.parseJSON(data);
            $('#campaign').append("<option value=''>Select one</option>");

            for(var i in data)
            {
                if(data[i].id == '{{ $lead->campaign_id }}') {
                    $('#campaign').append("<option value='" + data[i].id +"' selected>" + data[i].campaign_name + "</option>");
                } else {
                    $('#campaign').append("<option value='" + data[i].id +"'>" + data[i].campaign_name + "</option>");
                }

            }

            $('#campaign').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }


    function ajaxUpdateCampaign() {
        var campaign = $('#campaign').val();

        $.post("/leads/ajax/update-campaign",
            {
                lead_id: '{{ $lead->id }}',
                campaign: campaign
            },
            function (data, status) {

                $.notify(data, 'success');
            });
    }
</script>