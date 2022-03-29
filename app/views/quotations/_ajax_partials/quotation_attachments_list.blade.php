<div class="row">

    <div class="large-8 columns text-center">
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('style' => 'display: none;' ,'class' => '', 'id' => 'loader_quotation_attachments_table_'.$quotation->id)) }}
        <div id="quotation_attachments_{{ $quotation->id }}"></div>

        <script>

            function loadAjaxQuotationAttachmentsTable_<?php echo $quotation->id; ?>(quotation_id) {

                $('#loader_quotation_attachments_table_<?php echo $quotation->id; ?>').show();
                $('#quotation_attachments_<?php echo $quotation->id; ?>').hide();
                $('#quotation_attachments_<?php echo $quotation->id; ?>').html('');

                $.ajax({
                    url: '/quotations/ajax/load-quotation-attachments-table?quotation_id=' + quotation_id
                }).done(function (data) {

                    $('#quotation_attachments_<?php echo $quotation->id; ?>').html(data);
                    $('#loader_quotation_attachments_table_<?php echo $quotation->id; ?>').hide();
                    $('#quotation_attachments_<?php echo $quotation->id; ?>').show();

                });
            }

            loadAjaxQuotationAttachmentsTable_<?php echo $quotation->id; ?>('<?php echo $quotation->id; ?>');

        </script>
    </div>

    <div class="large-4 columns">

        {{ Form::open(array('url' => '/quotations/ajax/save-quotation-attachment', 'files' => true , 'id' =>'quotation_attachment_form_'.$quotation->id ,'autocomplete' => 'off')) }}

        {{ Form::hidden('quotation_attachment_save_method', 'create', array('id' => 'quotation_attachment_save_method_'.$quotation->id)) }}
        {{ Form::hidden('quotation_attachment_id', '', array('id' => 'quotation_attachment_id_'.$quotation->id)) }}
        {{ Form::hidden('quotation_id', $quotation->id) }}

        <div class="row">
            <div class="row">
                <div class="large-12 columns">
                    {{ Form::label('title', 'Title *', array('class'=>'control-label')) }}
                    <div class="controls">{{ Form::text('title', Input::old('title'), array('data-validation'=>'required', 'id' => 'title_'.$quotation->id)) }}</div>
                    {{ $errors->first('title', '<p class="alert-box alert radius">:message</p>') }}
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    {{ Form::label('description', 'Description *', array('class'=>'control-label')) }}
                    <div class="controls">{{ Form::textarea('description', Input::old('description'), array('data-validation'=>'required', 'id' => 'description_'.$quotation->id , 'rows' => '4')) }}</div>
                    {{ $errors->first('description', '<p class="alert-box alert radius">:message</p>') }}
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    {{ Form::label('attachment', 'Attachment', array('class'=>'control-label')) }}
                    <div class="controls">{{ Form::file('attachment', array('data-validation'=>'required')) }}</div>
                    {{ $errors->first('description', '<p class="alert-box alert radius">:message</p>') }}
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    {{ Form::submit('Create', array("class" => "button success tiny right", 'id' => 'button_quotation_attachments_details_'.$quotation->id)) }}
                    {{ Form::button('Clear', array("class" => "button alert tiny right", 'id' => 'clear_button_quotation_attachments_details_'.$quotation->id ,'onclick' => 'clearQuotationAttachmentForm_'.$quotation->id.'('.$quotation->id.')', 'style' => 'margin-right:10px')) }}
                </div>
            </div>
        </div>

        {{ Form::close() }}




        <script>

            function clearQuotationAttachmentForm_<?php echo $quotation->id; ?> () {

                $('#quotation_attachment_save_method_<?php echo $quotation->id; ?>').val("create");
                $('#button_quotation_attachments_details_<?php echo $quotation->id; ?>').val("Create");
                $("#quotation_attachment_form_<?php echo $quotation->id; ?>")[0].reset();
            }

            $( document ).ready(function() {

                $("#quotation_attachment_form_<?php echo $quotation->id; ?>").submit(function(e) {

                    e.preventDefault();
                    var url = "/quotations/ajax/save-quotation-attachment";
                    var formData = new FormData(this);

                    var quotation_attachment = $("quotation_attachment_form_<?php echo $quotation->id; ?> input[type=file]").val();
                    var title = $('#title_<?php echo $quotation->id; ?>').val();
                    var description = $('#description_<?php echo $quotation->id; ?>').val();


                    if(title == '' || description == ''){
                        $.notify("Fill required fields.");
                        return false;
                    }

                    var r = confirm("Would you like to save this Quotation Attachment?");
                    if (r == true) {

                        $('#button_quotation_attachments_details_<?php echo $quotation->id; ?>').prop('disabled', true);
                        $('#clear_button_quotation_attachments_details_<?php echo $quotation->id; ?>').prop('disabled', true);

                        $.ajax({
                            type: "POST",
                            url: $(this).attr('action'),
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: function(data)
                            {
                                $.notify(data, "success");
                                clearQuotationAttachmentForm_<?php echo $quotation->id; ?>();

                               $('#button_quotation_attachments_details_<?php echo $quotation->id; ?>').prop('disabled', false);
                               $('#clear_button_quotation_attachments_details_<?php echo $quotation->id; ?>').prop('disabled', false);

                                loadAjaxQuotationAttachmentsTable_<?php echo $quotation->id; ?>('<?php echo $quotation->id; ?>');
                            }
                        });
                    } else {
                        return false;
                    }


                });

            });





            function saveQuotationAttachmentForm_<?php echo $quotation->id; ?> (quotation_id) {

                $('quotation_attachments_form_<?php echo $quotation->id; ?>').hide();
                $('loader_quotation_attachments_details_<?php echo $quotation->id; ?>').show();

                var title = $('#title_<?php echo $quotation->id; ?>').val();
                var description = $('#description_<?php echo $quotation->id; ?>').val();

                var save_method = $('#quotation_attachment_save_method_<?php echo $quotation->id; ?>').val();
                var quotation_attachment_id = $('#quotation_attachment_id_<?php echo $quotation->id; ?>').val();

                if (title != '' && description != '') {
                    var r = confirm("Would you like to save this Code?");
                    if (r == true) {

                        $.post("/quotations/ajax/save-quotation-attachment/", {
                            quotation_id: quotation_id,
                            title: title,
                            description: description,
                            save_method: save_method,
                            quotation_attachment_id: quotation_attachment_id
                        }, function (data) {

                            $.notify('Saved Successfully.', "success");
                            clearQuotationAttachmentForm_<?php echo $quotation->id; ?>();

                            $('quotation_attachments_form_<?php echo $quotation->id; ?>').show();
                            $('loader_quotation_attachments_details_<?php echo $quotation->id; ?>').hide();

                            loadAjaxQuotationAttachmentsTable_<?php echo $quotation->id; ?>(quotation_id);

                        });

                    } else {
                        return false;
                    }

                } else {

                    $.notify("Fill required fields.");
                    return false;

                }
            }

        </script>
    </div>
</div>



