{{ Form::open(array('route' => 'email_templates.ajax.save_user_defined_action_button', 'ajax' => 'true', 'id' => 'email_templates_add_new_user_defined_action_button','autocomplete' => 'off')) }}
<div class="row expanded">
    <div class="large-12 columns">
        <h4>Add Action Button</h4>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <input type="text" id="email_template_user_defined_action_button_form_button_name" name="button_name" value="" placeholder="Button Name" data-validation="required" oninput="checkUserDefinedActionButtonExists(this.value)">
    </div>
    <div class="large-12 columns" id="already_exists_action_button_div" style="display: none">
        <div class="callout small alert">
            <p>This Action Button already exists.</p>
        </div>
    </div>
    <div class="large-12 columns">
        <input type="text" id="email_template_user_defined_action_button_form_url" name="url" value="" placeholder="URL" data-validation="required">
    </div>
    <div class="large-12 columns">
        <input type="text" id="email_template_user_defined_action_button_form_value_text" name="value_text" value="" placeholder="Value Text" data-validation="required">
    </div>
    <div class="large-12 columns">
        <select name="style" id="email_template_user_defined_action_button_form_style" data-validation="required" >
            <?php
                $styles = EmailTemplateUserDefinedActionButton::$styles;
            ?>
            <option value=''>Style</option>
            @foreach($styles as $key => $value)
                <option value='{{ $key }}'>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row save_bar">
    <div class="large-8 columns">
        &nbsp;
    </div>
    <div class="large-2 columns text-right">
        <input type="button" value="Clear" class="button tiny alert"
               onclick="clearEmailTemplateUserDefinedActionButtonForm()">
    </div>
    <div class="large-2 columns text-right">
        {{ Form::submit('Save', array("class" => "button tiny", "id" => "email_templates_form_user_defined_action_button_save_button", "disabled" => "disabled")) }}
    </div>
</div>
<div class="row loading_animation" style="display: none;">
    <div class="large-12 columns">
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
    </div>
</div>
{{ Form::close() }}
<script>

    $(document).ready(function() {
        setUpAjaxForm('email_templates_add_new_user_defined_action_button', 'create', '',
            function(){
                clearEmailTemplateUserDefinedActionButtonForm();
                ajaxLoadEmailTemplateUserDefinedActionButtonsList(1);
            }
        );
    });

    function checkUserDefinedActionButtonExists(button_name){

        $("#email_templates_form_user_defined_action_button_save_button").prop('disabled', true);
        $("#already_exists_action_button_div").hide();

        $.ajax({
            url: '/email-templates/ajax/check-user-defined-action-button-exists?' +
            'button_name=' + button_name

        }).done(function (data) {

            var response = JSON.parse(data);

            console.log(response.status);

            if(response.status){
                $("#email_templates_form_user_defined_action_button_save_button").prop('disabled', true);
                $("#already_exists_action_button_div").show();
            }else{
                $("#email_templates_form_user_defined_action_button_save_button").prop('disabled', false);
                $("#already_exists_action_button_div").hide();
            }

        });
    }

    function clearEmailTemplateUserDefinedActionButtonForm() {
        $('#email_template_user_defined_action_button_form_button_name').val('');
        $('#email_template_user_defined_action_button_form_url').val('');
        $('#email_template_user_defined_action_button_form_value_text').val('');
        $('#email_template_user_defined_action_button_form_style').val('');
    }

</script>