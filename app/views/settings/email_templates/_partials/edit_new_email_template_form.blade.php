{{ Form::open(array('route' => 'email_templates.ajax.update_email_template', 'ajax' => 'true', 'id' => 'email_templates_add_new_email_template_'.$email_template->id,'autocomplete' => 'off')) }}
<input type="hidden" name="email_template_id" value="{{ $email_template->id }}">


<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Add Email Template</h4>
        </div>
    </div>
</div>

<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="name">
                Name
                <input type="text" id="add_email_template_form_name_{{ $email_template->id }}" name="name" value="{{ $email_template->name}}" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="subject">
                Subject
                <input type="text" id="add_email_template_form_subject_{{ $email_template->id }}" name="subject" value="{{ $email_template->subject}}" data-validation="">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="to">
                To
                <input type="text" id="add_email_template_form_to_{{ $email_template->id }}" name="to" value="{{ $email_template->to}}" data-validation="">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="cc">
                CC
                <input type="text" id="add_email_template_form_cc_{{ $email_template->id }}" name="cc" value="{{ $email_template->cc}}" data-validation="">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="bcc">
                BCC
                <input type="text" id="add_email_template_form_bcc_{{ $email_template->id }}" name="bcc" value="{{ $email_template->bcc}}" data-validation="">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-4 columns">
            <h5>Tags</h5>
            <select name="tags" id="selected_email_template_tag_{{ $email_template->id }}" onchange="displaySelectedTag('selected_email_template_tag_label_{{ $email_template->id }}',this.value)">
                <option value="">Select Tag</option>
                @foreach(EmailTemplatePreDefinedTag::getAllEmailTemplatePreDefinedTags() as $pre_defined_email_template_tag)
                    <option value="{{ $pre_defined_email_template_tag->tag }}">{{ $pre_defined_email_template_tag->name }}</option>
                @endforeach
                @foreach(EmailTemplateUserDefinedTag::getAllEmailTemplateUserDefinedTagsForOrganization() as $email_template_tag)
                    <option value="{{ $email_template_tag->tag }}">{{ $email_template_tag->name }}</option>
                @endforeach
            </select>

            <div class="callout small secondary">
                <p id="selected_email_template_tag_label_{{ $email_template->id }}" >Selected Tag: </p>
            </div>
            <input type="button" value="Insert Into Template" class="button tiny primary" onclick ="insertEmailTemplateSelectedTag('selected_email_template_tag_{{ $email_template->id }}','body_{{ $email_template->id }}')">

            <h5>Action buttons</h5>
            <select name="action_buttons" id="selected_email_template_action_button_{{ $email_template->id }}" onchange="displaySelectedTag('selected_email_template_action_button_label_{{ $email_template->id }}',this.value)">
                <option value="">Select Action Button</option>
                @foreach(EmailTemplatePreDefinedActionButton::getAllEmailTemplatePreDefinedActionButtonsForOrganization() as $pre_defined_email_template_action_button)
                    <option value="{{ $pre_defined_email_template_action_button->button_tag }}">{{ $pre_defined_email_template_action_button->button_name }}</option>
                @endforeach
                @foreach(EmailTemplateUserDefinedActionButton::getAllEmailTemplateUserDefinedActionButtonsForOrganization() as $email_template_action_button)
                    <option value="{{ $email_template_action_button->button_tag }}">{{ $email_template_action_button->button_name }}</option>
                @endforeach
            </select>

            <div class="callout small secondary">
                <p id="selected_email_template_action_button_label_{{ $email_template->id }}" >Selected Tag: </p>
            </div>
            <input type="button" value="Insert Into Template" class="button tiny primary" onclick ="insertEmailTemplateSelectedTag('selected_email_template_action_button_{{ $email_template->id }}','body_{{ $email_template->id }}')">


        </div>
        <div class="large-8 columns">
            <label for="body">
                Body
                <div class="controls">{{ Form::textarea('body','', array('id' => 'body_'.$email_template->id,'data-validation'=>'required')) }}</div>
            </label>
            
            <script>
                $( document ).ready(function() {
                    var body = '<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', trim($email_template->body)); ?>';
                    setupCKEditor('body_{{ $email_template->id }}',body,'Basic');
                });
            </script>
            <br>
            <div class="callout small primary">
                <p>Email Signature will be added automatically when rendering template.</p>
            </div>
        </div>
    </div>
</div>

<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns text-right">
            <input type="button" value="Cancel" class="button tiny alert" onclick="$('#popup_edit_email_template').foundation('close');">
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
</div>

{{ Form::close() }}
<script>

    $(document).ready(function() {
        setUpAjaxForm('email_templates_add_new_email_template_{{ $email_template->id }}', 'create', '#reveal_edit_email_template_{{ $email_template->id }}',
            function(){
                ajaxLoadEmailTemplatesList(1);
            }
        );

        $('#add_email_template_form_to_{{ $email_template->id }}').selectize({
            delimiter: ', ',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $('#add_email_template_form_cc_{{ $email_template->id }}').selectize({
            delimiter: ', ',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $('#add_email_template_form_bcc_{{ $email_template->id }}').selectize({
            delimiter: ', ',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

    });




</script>