<div class="reveal panel" id="popup_edit_call" data-reveal>
    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_edit_call_form', 'class' => 'float-center', 'style' => 'display:none')) }}
    <div id="response_edit_call_form">

    </div><!--end response_edit_call_form-->

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal panel" id="popup_edit_meeting" data-reveal>
    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_edit_meeting_form', 'class' => 'float-center', 'style' => 'display:none')) }}
    <div id="response_edit_meeting_form">

    </div><!--end response_edit_meeting_form-->

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="reveal panel" id="popup_edit_email" data-reveal>
    {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_edit_email_form', 'class' => 'float-center', 'style' => 'display:none')) }}
    <div id="response_edit_email_form">

    </div><!--end response_edit_email_form-->

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<script>
    function ajaxGetEditCallForm(call_id) {
        $('#loader_edit_call_form').show();
        $('#response_edit_call_form').html('');
        $('#response_edit_call_form').hide();

        $.ajax({
            url: '/my-activities/ajax/get-edit-call-form?' +
            'call_id=' + call_id +
            '&post_data_to_load={{ $post_data_to_load }}'
        }).done(function (data) {
            $('#response_edit_call_form').html(data);
            $('#loader_edit_call_form').hide();
            $('#response_edit_call_form').show();
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

    function ajaxGetEditMeetingForm(meeting_id) {
        $('#loader_edit_meeting_form').show();
        $('#response_edit_meeting_form').html('');
        $('#response_edit_meeting_form').hide();

        $.ajax({
            url: '/my-activities/ajax/get-edit-meeting-form?' +
            'meeting_id=' + meeting_id +
            '&post_data_to_load={{ $post_data_to_load }}'
        }).done(function (data) {
            $('#response_edit_meeting_form').html(data);
            $('#loader_edit_meeting_form').hide();
            $('#response_edit_meeting_form').show();
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

    function ajaxGetEditEmailForm(email_id) {
        $('#loader_edit_email_form').show();
        $('#response_edit_email_form').html('');
        $('#response_edit_meeting_form').hide();

        $.ajax({
            url: '/my-activities/ajax/get-edit-email-form?' +
            'email_id=' + email_id +
            '&post_data_to_load={{ $post_data_to_load }}'
        }).done(function (data) {
            $('#response_edit_email_form').html(data);
            $('#loader_edit_email_form').hide();
            $('#response_edit_email_form').show();
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