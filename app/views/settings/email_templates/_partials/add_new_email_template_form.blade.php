{{ Form::open(array('route' => 'email_templates.ajax.save_email_template', 'ajax' => 'true', 'id' => 'email_templates_add_new_email_template','autocomplete' => 'off')) }}
<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h5>Add Email Template</h5>
        </div>
    </div>
</div>

<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="name">
                Name *
                <input type="text" id="add_email_template_form_name" name="name" data-validation="required">
            </label>
        </div>

        <div class="large-12 columns">
            <label for="subject">
                Subject
                <input type="text" id="add_email_template_form_subject" name="subject" data-validation="">
            </label>
        </div>

        <div class="large-12 columns">
            <label for="to">
                To
                <input type="text" id="add_email_template_form_to" name="to" data-validation="">
            </label>
        </div>

        <div class="large-12 columns">
            <label for="cc">
                CC
                <input type="text" id="add_email_template_form_cc" name="cc" data-validation="">
            </label>
        </div>

        <div class="large-12 columns">
            <label for="bcc">
                BCC
                <input type="text" id="add_email_template_form_bcc" name="bcc" data-validation="">
            </label>
        </div>


        <div class="large-4 columns">
            <h5>Tags</h5>
            <select name="tags" id="selected_email_template_tag" onchange="displaySelectedTag('selected_email_template_tag_label',this.value)">
                <option value="">Select Tag</option>
                @foreach(EmailTemplatePreDefinedTag::getAllEmailTemplatePreDefinedTags() as $pre_defined_email_template_tag)
                    <option value="{{ $pre_defined_email_template_tag->tag }}">{{ $pre_defined_email_template_tag->name }}</option>
                @endforeach
                @foreach(EmailTemplateUserDefinedTag::getAllEmailTemplateUserDefinedTagsForOrganization() as $email_template_tag)
                    <option value="{{ $email_template_tag->tag }}">{{ $email_template_tag->name }}</option>
                @endforeach
            </select>

            <div class="callout small secondary">
                <p id="selected_email_template_tag_label" >Selected Tag: </p>
            </div>
            <input type="button" value="Insert Into Template" class="button tiny primary" onclick ="insertEmailTemplateSelectedTag('selected_email_template_tag','body')">

            <h5>Action buttons</h5>
            <select name="action_buttons" id="selected_email_template_action_button" onchange="displaySelectedTag('selected_email_template_action_button_label',this.value)">
                <option value="">Select Action Button</option>
                @foreach(EmailTemplatePreDefinedActionButton::getAllEmailTemplatePreDefinedActionButtonsForOrganization() as $pre_defined_email_template_action_button)
                    <option value="{{ $pre_defined_email_template_action_button->button_tag }}">{{ $pre_defined_email_template_action_button->button_name }}</option>
                @endforeach
                @foreach(EmailTemplateUserDefinedActionButton::getAllEmailTemplateUserDefinedActionButtonsForOrganization() as $email_template_action_button)
                    <option value="{{ $email_template_action_button->button_tag }}">{{ $email_template_action_button->button_name }}</option>
                @endforeach
            </select>

            <div class="callout small secondary">
                <p id="selected_email_template_action_button_label" >Selected Tag: </p>
            </div>
            <input type="button" value="Insert Into Template" class="button tiny primary" onclick ="insertEmailTemplateSelectedTag('selected_email_template_action_button','body')">

        </div>
        <div class="large-8 columns">
            <label for="body">Body</label>
            <div class="controls">{{ Form::textarea('body','', array('id' => 'body','data-validation'=>'required')) }}</div>
            <script>
                $( document ).ready(function() {
                    setupCKEditor('body','','Basic');
                });
            </script>
            <br>
            <div class="callout small primary">
                <p>Email Signature will be added automatically when rendering template.</p>
            </div>
        </div>
    </div>
</div>


<br>

<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns">
            <input type="button" value="Cancel" class="button tiny alert float-right" onclick="$('#popup_add_email_template').foundation('close');">
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Save', array("class" => "button success tiny float-right")) }}
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
        setUpAjaxForm('email_templates_add_new_email_template', 'create', '#reveal_add_email_template',
            function(){
                clearAddEmailTemplateForm();
                ajaxLoadEmailTemplatesList(1);
            }
        );

        $('#add_email_template_form_to').selectize({
            delimiter: ', ',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $('#add_email_template_form_cc').selectize({
            delimiter: ', ',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $('#add_email_template_form_bcc').selectize({
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

    function clearAddEmailTemplateForm() {
        $('#add_email_template_form_name').val('');
        $('#add_email_template_form_subject').val('');

        var add_email_template_form_to = $('#add_email_template_form_to').selectize();
        var control_add_email_template_form_to = add_email_template_form_to[0].selectize;
        control_add_email_template_form_to.clear();

        var add_email_template_form_cc = $('#add_email_template_form_cc').selectize();
        var control_add_email_template_form_cc = add_email_template_form_cc[0].selectize;
        control_add_email_template_form_cc.clear();

        var add_email_template_form_bcc = $('#add_email_template_form_bcc').selectize();
        var control_add_email_template_form_bcc = add_email_template_form_bcc[0].selectize;
        control_add_email_template_form_bcc.clear();


        $('#selected_email_template_tag').val('');
        $('#selected_email_template_action_button').val('');
        $('#body').val('');
    }



</script>