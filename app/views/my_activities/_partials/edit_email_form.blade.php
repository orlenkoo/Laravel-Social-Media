{{ Form::open(array('route' => 'my_activities.ajax.update_email', 'ajax' => 'true', 'id' => 'edit_email_form','autocomplete' => 'off')) }}
<input type="hidden" name="email_id" id="edit_email_form_email_id" value="{{ $email->id }}">
<input type="hidden" name="customer_id" id="edit_email_form_customer_id" value="{{ $email->customer_id }}">

<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Edit Meeting</h4>
        </div>
    </div>
</div>
<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="to">
                To
                <input type="text" name="to" id="edit_email_form_to" value="{{ $email->to }}" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="cc">
                CC
                <input type="text" name="cc" id="edit_email_form_cc"  value="{{ $email->cc }}" data-validation="">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="bcc">
                BCC
                <input type="text" name="bcc" id="edit_email_form_bcc"  value="{{ $email->bcc }}" data-validation="">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="subject">
                Subject
                <input type="text" name="subject" id="edit_email_form_subject" value="{{ $email->subject }}" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="body">
                Body
                <textarea name="body" id="edit_email_form_body" cols="30" rows="10" data-validation="required"></textarea>
            </label>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="large-8 columns">
            <h5>Email Attachments</h5>

            <?php $email_attachments = Email::getAttachmentList($email->id); ?>
            @if(count($email_attachments) > 0)
                <table class="basic-table">
                    @foreach($email_attachments as $email_attachment)
                        <tr>
                            @if($email->status == 'draft')
                                <th>
                                    <input type="button" class="button tiny alert" value="Remove" onclick="removeEmailAttachment(this, '{{$email_attachment['email_attachment_id']}}');">
                                </th>
                            @endif
                            <th align="left"><a href="{{$email_attachment['file_path']}}" target="_blank">{{$email_attachment['title']}}</a></th>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>

    <br>

    @if($email->status == 'draft')
        @include('my_activities._partials.email_attachment_form')
    @endif

</div>
<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-3 columns">
            &nbsp;
        </div>
        <div class="large-2 columns text-right">
            <input type="button" value="Cancel" class="button tiny alert" onclick="$('#popup_edit_email').foundation('close');">
        </div>
        <div class="large-3 columns text-right">
            <button class="button tiny float-left" type="button" data-open="reveal_add_new_task_edit_email_form_{{ $email->id }}">Create Task</button>
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Save', array("class" => "button tiny", "id" => "edit_email_form_draft_button_".$email->id)) }}
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Send', array("class" => "button tiny success", "id" => "edit_email_form_sent_button_".$email->id)) }}
        </div>
    </div>

    <div class="row loading_animation" style="display: none;">
        <div class="large-12 columns text-center">
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
        </div>
    </div>
</div>

<div class="reveal large reveal_add_new_task" id="reveal_add_new_task_edit_email_form_{{ $email->id }}" name="reveal_add_new_task_edit_email_form" data-reveal>
    <div class="panel-content">
        <div class="row">
            <div class="large-12 columns">
                @include('tasks._partials.add_new_task_form', [
                                                  'activity_type' => 'Email',
                                                  'activity_object' => $email
                                                  ])
            </div>
        </div>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

{{ Form::close() }}

<script>

    $(document).foundation();

    var body = '<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', trim($email->body)); ?>';
    setupCKEditor('edit_email_form_body',body,'');

    $(document).ready(function() {
        setUpAjaxForm('edit_email_form', 'create', '#popup_edit_email',
                function(){

                    @if($post_data_to_load == 'dashboard_lead_time_line')
                        ajaxLoadDashboardLeadTimeLine(1);
                    @elseif($post_data_to_load == 'my_activities')
                        var pagination_page  = $("#pagination_emails_list").find('li.active > span').html();
                        ajaxLoadEmailsList(pagination_page);
                    @elseif($post_data_to_load == 'customer-activity-list')
                        var pagination_page  = $("#pagination_emails_list").find('li.active > span').html();
                        ajaxLoadCustomerEmails(pagination_page);
                    @endif


                }
        );
    });

    function removeEmailAttachment(object,email_attachment_id){

        $.post("/my-activities/ajax/remove-email-attachment/", {
            email_attachment_id: email_attachment_id
        }, function (data) {

            $.notify('Email Attachment Removed Successfully.', "success");
            $(object).closest('tr').remove();
            return false;

        });
    }


</script>