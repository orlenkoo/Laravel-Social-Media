{{ Form::open(array('route' => 'my_activities.ajax.save_new_email', 'ajax' => 'true', 'id' => 'add_new_email_form','autocomplete' => 'off')) }}
<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Add New Email</h4>
        </div>
    </div>
</div>

<input type="hidden" name="customer_id" id="new_email_form_customer_id" value="{{ $customer_id  }}">

<div class="panel-content">
    <div class="row">
        <div class="large-12 columns">
            <label for="email_template">
                Email Template

                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_email_template', 'class' => 'float-center', 'style' => 'display:none')) }}
                <select name='email_template' id='email_template' onchange='setSelectedTemplateData()'>
                </select>
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="to">
                To
                <input type="text" name="to" id="new_email_form_to" value="{{ $contacts }}" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="cc">
                CC
                <input type="text" name="cc" id="new_email_form_cc" value="{{ $contacts }}" data-validation="">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="bcc">
                BCC
                <input type="text" name="bcc" id="new_email_form_bcc" value="{{ $contacts }}" data-validation="">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="subject">
                Subject
                <input type="text" name="subject" id="new_email_form_subject" data-validation="required">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="body">
                Body
                <textarea name="body" id="new_email_form_body" cols="30" rows="10" data-validation="required"></textarea>
            </label>
        </div>
    </div>

    <br>

    @include('my_activities._partials.email_attachment_form')


    <br>


    <div class="row">
        <div class="large-12 columns">
            <legend>Create Task</legend>
            <input type="checkbox" name="create_task" value="1" id="create_task">
        </div>
    </div>


</div>

<div class="panel-footer">
    <div class="row save_bar">
        <div class="large-6 columns">
            &nbsp;
        </div>
        <div class="large-2 columns text-right">
            <input type="button" value="Cancel" class="button tiny alert" onclick="$('#popup_add_email').foundation('close');">
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Save', array("class" => "button tiny")) }}
        </div>
        <div class="large-2 columns text-right">
            {{ Form::submit('Send', array("class" => "button tiny success")) }}
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

        setupCKEditor('new_email_form_body','','');

        setUpAjaxForm('add_new_email_form', 'create', '#popup_add_new_email',
                function(){
                    console.log("Reload Emails");
                    clearNewEmailForm();
                    @if($post_data_to_load == 'dashboard_lead_time_line')
                        ajaxLoadDashboardLeadTimeLine(1);
                    @else
                        ajaxLoadCustomerEmails(1);
                    @endif
                }
        );
    });

    function clearNewEmailForm() {
        document.getElementById("add_new_email_form").reset();

        var email_to = $('#new_email_form_to').selectize();
        var control_email_to = email_to[0].selectize;
        control_email_to.clear();

        var email_cc = $('#new_email_form_cc').selectize();
        var control_email_cc = email_cc[0].selectize;
        control_email_cc.clear();

        var email_bcc = $('#new_email_form_bcc').selectize();
        var control_email_bcc = email_bcc[0].selectize;
        control_email_bcc.clear();

        var email_signiture = '<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', trim(Employee::getEmailSignatureHTML())); ?>';
        CKEDITOR.instances['new_email_form_body'].setData(email_signiture);

    }

    $('#new_email_form_to').selectize({
        delimiter: ', ',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

    $('#new_email_form_cc').selectize({
        delimiter: ', ',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

    $('#new_email_form_bcc').selectize({
        delimiter: ', ',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

    var email_cc = $('#new_email_form_cc').selectize();
    var control_email_cc = email_cc[0].selectize;
    control_email_cc.clear();

    var email_bcc = $('#new_email_form_bcc').selectize();
    var control_email_bcc = email_bcc[0].selectize;
    control_email_bcc.clear();


    getEmailTemplateList();
    function getEmailTemplateList() {
        $.ajax({
            url: '/email-templates/ajax/get-templates-list'
        }).done(function(data){
            $('#email_template').empty();
            data = $.parseJSON(data);
            $('#email_template').append("<option value=''>Select one</option>");

            for(var i in data)
            {
                $('#email_template').append("<option value='"+ data[i].id +"' >" + data[i].name + "</option>");
            }

            $('#email_template').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function setSelectedTemplateData(){
        var email_template_id = $('#email_template').val();

        if(!email_template_id){

            $("#new_email_form_subject").val('');

            var email_signiture = '<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', trim(Employee::getEmailSignatureHTML())); ?>';
            CKEDITOR.instances['new_email_form_body'].setData(email_signiture);

            return ;
        }

        $.ajax({
            url: '/email-templates/ajax/get-template-details?email_template_id='+email_template_id
        }).done(function(data){

            data = $.parseJSON(data);

            var body = data.body;
            var subject = data.subject;

            var bcc = data.bcc;
            var bcc_array = convertCommaSeparatedListToSelectizeOptions(bcc);

            var cc = data.cc;
            var cc_array = convertCommaSeparatedListToSelectizeOptions(cc);

            var to = data.to;
            var to_array = convertCommaSeparatedListToSelectizeOptions(to);


            $("#new_email_form_subject").val(subject);

            CKEDITOR.instances['new_email_form_body'].setData(body);

            var email_to = $('#new_email_form_to').selectize();
            var control_email_to = email_to[0].selectize;
            // control_email_to.clearOptions();
            control_email_to.addOption(to_array);


            var email_cc = $('#new_email_form_cc').selectize();
            var control_email_cc = email_cc[0].selectize;
            // control_email_cc.clearOptions();
            control_email_cc.addOption(cc_array);

            var email_bcc = $('#new_email_form_bcc').selectize();
            var control_email_bcc = email_bcc[0].selectize;
            // control_email_bcc.clearOptions();
            control_email_bcc.addOption(bcc_array);

        });


        function convertCommaSeparatedListToSelectizeOptions(comma_seperated_list) {

            var list_array = comma_seperated_list.split(',');
            var selectize_options = [];

            for (var i in list_array) {
                selectize_options.push({value : list_array[i], text : list_array[i] })
            }

            return selectize_options;

        }

    }
</script>