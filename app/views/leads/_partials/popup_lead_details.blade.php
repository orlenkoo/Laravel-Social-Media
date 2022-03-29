<div class="panel-heading">
    <h4>Lead Details</h4>
</div>
<div class="panel-content">

    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_lead_details', 'class' => 'float-center', 'style' => 'display:none')) }}
    <div id="response_lead_details">

    </div><!--end response_lead_details-->

</div>

    <script>
        function ajaxLoadLeadDetails(lead_id) {

            $('#loader_lead_details').show();
            $('#response_lead_details').hide();

            $.ajax({
                url: '/leads/ajax/load-dashboard-lead-details?' +
                'lead_id=' + lead_id

            }).done(function (data) {
                openPopup('popup_lead_details');
                $('#response_lead_details').html(data);
                $('#loader_lead_details').hide();
                $('#response_lead_details').show();
                $('.datepicker').datetimepicker({
                    timepicker: false,
                    format: 'Y-m-d',
                    lang: 'en',
                    scrollInput: false
                });
                $('.timepicker').datetimepicker({
                    datepicker: false,
                    format: 'g:i A',
                    formatTime: 'g:i A',
                    lang: 'en',
                    scrollInput: false
                });
                // $(document).foundation();
            });

        }
    </script>