<script>
    function ajaxGetLeadMetaDetailsForDropDown() {
        $.ajax({
            url: '/leads/ajax/get-lead-meta-details-for-drop-down?lead_id={{ $lead->id }}'
        }).done(function(data){
            console.log(data);

            if(data != '') {
                $('.lead_meta_loader').empty();
                data = $.parseJSON(data);
                $('.lead_meta_loader').append("<option value=''>Type and Add or Pick From Lead Meta Details Below:</option>");

                for(var i in data)
                {
                    $('.lead_meta_loader').append("<option value='" + data[i].value +"'>" + data[i].key + ': ' + data[i].value + "</option>");
                }
            }

            $('.lead_meta_loader').selectize({
                create: true,
                sortField: 'text'
            });

        });
    }

    ajaxGetLeadMetaDetailsForDropDown();
</script>