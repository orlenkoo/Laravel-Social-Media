<table>
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($quotation_attachments as $quotation_attachment)
        <tr>
            <td>{{ $quotation_attachment->title }}</td>
            <td>{{ $quotation_attachment->description }}</td>
            <td>
                {{ Form::button('Edit', array("class" => "button tiny", 'id' => 'button_quotation_attachments_edit_'.$quotation_attachment->id, 'onclick' => 'loadEditForm_'.$quotation_attachment->id.'('.$quotation_attachment.')')) }}
            </td>
            <td>
                @if($quotation_attachment->quotation_file_url != '')
                    <a class="button tiny" href="{{ $quotation_attachment->quotation_file_url }}" target="_blank">View Attachment</a>
                @else
                    Attachment not Uploaded.
                @endif
            </td>
            <script>

                function loadEditForm_<?php echo $quotation_attachment->id; ?> (quotation_attachments) {

                    $('#quotation_attachment_save_method_<?php echo $quotation->id; ?>').val("edit");
                    $('#button_quotation_attachments_details_<?php echo $quotation->id; ?>').val("Save");

                    $('#quotation_attachment_id_<?php echo $quotation->id; ?>').val(quotation_attachments.id);

                    $('#title_<?php echo $quotation->id; ?>').val(quotation_attachments.title);
                    $('#description_<?php echo $quotation->id; ?>').val(quotation_attachments.description);
                }

            </script>
        </tr>
    @endforeach
</table>


