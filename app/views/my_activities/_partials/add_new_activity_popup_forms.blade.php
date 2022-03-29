<div class="reveal panel" id="popup_add_new_call" data-reveal>
    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_add_new_call_form', 'class' => 'float-center', 'style' => 'display:none')) }}
    <div id="response_add_new_call_form">

    </div><!--end response_add_new_call_form-->

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal panel" id="popup_add_new_meeting" data-reveal>
    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_add_new_meeting_form', 'class' => 'float-center', 'style' => 'display:none')) }}
    <div id="response_add_new_meeting_form">

    </div><!--end response_add_new_meeting_form-->

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal panel" id="popup_add_new_email" data-reveal>
    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_add_new_email_form', 'class' => 'float-center', 'style' => 'display:none')) }}
    <div id="response_add_new_email_form">

    </div><!--end response_add_new_email_form-->

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<script>
    function ajaxGetAddNewCallForm(customer_id) {
        $('#loader_add_new_call_form').show();
        $('#response_add_new_call_form').html('');
        $('#response_add_new_call_form').hide();

        $.ajax({
            url: '/my-activities/ajax/get-add-new-call-form?' +
            'customer_id=' + customer_id +
            '&post_data_to_load={{ $post_data_to_load }}'
        }).done(function (data) {
            $('#response_add_new_call_form').html(data);
            $('#loader_add_new_call_form').hide();
            $('#response_add_new_call_form').show();
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
        });
    }

    function ajaxGetAddNewMeetingForm(customer_id) {
        $('#loader_add_new_meeting_form').show();
        $('#response_add_new_meeting_form').html('');
        $('#response_add_new_meeting_form').hide();

        $.ajax({
            url: '/my-activities/ajax/get-add-new-meeting-form?' +
            'customer_id=' + customer_id +
            '&post_data_to_load={{ $post_data_to_load }}'
        }).done(function (data) {
            $('#response_add_new_meeting_form').html(data);
            $('#loader_add_new_meeting_form').hide();
            $('#response_add_new_meeting_form').show();
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
        });
    }

    function ajaxGetAddNewEmailForm(customer_id) {
        $('#loader_add_new_email_form').show();
        $('#response_add_new_email_form').html('');
        $('#response_add_new_meeting_form').hide();

        $.ajax({
            url: '/my-activities/ajax/get-add-new-email-form?' +
            'customer_id=' + customer_id +
            '&post_data_to_load={{ $post_data_to_load }}'
        }).done(function (data) {
            $('#response_add_new_email_form').html(data);
            $('#loader_add_new_email_form').hide();
            $('#response_add_new_email_form').show();
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
        });
    }
</script>