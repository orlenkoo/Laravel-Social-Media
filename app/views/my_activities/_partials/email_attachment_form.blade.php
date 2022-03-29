<?php  $dynamic_form_id  = uniqid(); ?>

<div class="row">
    <div class="large-12 columns">

        <p><input type="button" value="Add Email Attachment" class="button tiny success" onclick="addEmailAttachment_{{ $dynamic_form_id }}()"></p>
        <table class="basic-table" id="email_attachments_table_{{ $dynamic_form_id }}">
            <tr>
                <th width="10%"></th>
                <th width="45%">Title</th>
                <th width="45%">Attachment File</th>
            </tr>
            <tr>
                <td width="10%"></td>
                <td width="45%">
                    <input type="text" name="email_attachment_titles[]" id="email_attachment_titles_{{ $dynamic_form_id }}" class="email_attachment_titles_{{ $dynamic_form_id }}">
                </td>
                <td width="45%">
                    <input type="file" name="email_attachment_files[]" id="email_attachment_files_{{ $dynamic_form_id }}" onchange="setEmailAttachmentTitle(this)">
                </td>
            </tr>
        </table>
    </div>

    <script>

        function addEmailAttachment_{{ $dynamic_form_id }}(){
            $('#email_attachments_table_{{ $dynamic_form_id }} tr:last').after('<tr> <td width="10%"><input type="button" class="remove_email_attachment button tiny alert" value="X"></td> <td width="45%"> <input type="text" name="email_attachment_titles[]" id="email_attachment_titles_{{ $dynamic_form_id }}" class="email_attachment_titles_{{ $dynamic_form_id }}"> </td> <td width="45%"> <input type="file" name="email_attachment_files[]" id="email_attachment_files_{{ $dynamic_form_id }}" onchange="setEmailAttachmentTitle(this);"> </td> </tr>');
            return false;
        }

        $(document).on('click', '.remove_email_attachment', function () {
            $(this).closest('tr').remove();
            return false;
        });

        function setEmailAttachmentTitle(object){
            var attachment_title = $(object).val().split(/(\\|\/)/g).pop().replace(/\.[^/.]+$/, "");
            $(object).closest("tr")
                    .find(".email_attachment_titles_{{ $dynamic_form_id }}")
                    .val(attachment_title);
        }

    </script>
</div>