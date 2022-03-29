{{ Form::open(array('route' => 'email_templates.ajax.save_user_defined_tag', 'ajax' => 'true', 'id' => 'email_templates_add_new_user_defined_tag','autocomplete' => 'off')) }}
<div class="row expanded">
    <div class="large-12 columns">
        <h4>Add Tag</h4>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <input type="text" id="email_template_user_defined_tag_form_name" name="name" value="" placeholder="Name" data-validation="required" oninput="checkUserDefinedTagExists(this.value)">
    </div>
    <div class="large-12 columns" id="already_exists_tag_div" style="display: none">
        <div class="callout small alert">
            <p>This Tag already exists.</p>
        </div>
    </div>
    <div class="large-12 columns">
        <input type="text" id="email_template_user_defined_tag_form_value" name="value" value="" placeholder="Value" data-validation="required">
    </div>
</div>

<div class="row save_bar">
    <div class="large-8 columns">
        &nbsp;
    </div>
    <div class="large-2 columns text-right">
        <input type="button" value="Clear" class="button tiny alert"
               onclick="clearEmailTemplateUserDefinedTagForm()">
    </div>
    <div class="large-2 columns text-right" >
        {{ Form::submit('Save', array("class" => "button tiny", "id" => "email_templates_form_user_defined_tag_save_button", "disabled" => "disabled")) }}
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
        setUpAjaxForm('email_templates_add_new_user_defined_tag', 'create', '',
            function(){
                clearEmailTemplateUserDefinedTagForm();
                ajaxLoadEmailTemplateUserDefinedTagsList(1);
            }
        );
    });

    function checkUserDefinedTagExists(tag_name){

        $("#email_templates_form_user_defined_tag_save_button").prop('disabled', true);
        $("#already_exists_tag_div").hide();

        $.ajax({
            url: '/email-templates/ajax/check-user-defined-tag-exists?' +
            'name=' + tag_name

        }).done(function (data) {

            var response = JSON.parse(data);

            console.log(response.status);

            if(response.status){
                $("#email_templates_form_user_defined_tag_save_button").prop('disabled', true);
                $("#already_exists_tag_div").show();
            }else{
                $("#email_templates_form_user_defined_tag_save_button").prop('disabled', false);
                $("#already_exists_tag_div").hide();
            }

        });
    }

    function clearEmailTemplateUserDefinedTagForm() {
        $('#email_template_user_defined_tag_form_name').val('');
        $('#email_template_user_defined_tag_form_value').val('');
    }

</script>