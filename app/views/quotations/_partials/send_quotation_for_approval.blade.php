<div class="row">
    <div class="large-12 columns">
        <label for="">
            Contact Person:
            <input type="text" name="send_quotation_contact_person" id="send_quotation_contact_person_{{ $quotation->id }}" value="{{ $quotation->contact_person }}">
        </label>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <label for="">
            Email:
            <input type="text" name="send_quotation_email" id="send_quotation_email_{{ $quotation->id }}" value="{{ $quotation->email }}">
        </label>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <label for="">
            Subject:
            <input type="text" name="send_quotation_subject" id="send_quotation_subject_{{ $quotation->id }}" value="Quotation - {{ $quotation->company_name }} - {{ $quotation->project_quote }}">
        </label>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <label for="">
            Message:
            <textarea name="send_quotation_note" id="send_quotation_note_{{ $quotation->id }}" cols="30" rows="10">Please check the attached quotation.</textarea>
        </label>
    </div>
</div>

<div class="row">
    <div class="large-8 columns">
        &nbsp;
    </div>
    <div class="large-2 columns">
        <input type="button" value="Cancel" class="button tiny alert" onclick="$('#quotation_send_for_approval_{{ $quotation->id }}').foundation('close');">
    </div>
    <div class="large-2 columns">
        <input type="button" value="Send" class="button tiny success" onclick="ajaxSendQuotation_{{ $quotation->id }} ()" id="send_quotation_form_save_button_{{ $quotation->id }}">
    </div>
</div>

<button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
</button>

<script>
    function ajaxSendQuotation_{{ $quotation->id }} () {
        var quotation_id = '{{ $quotation->id }}';
        var send_quotation_contact_person = $('#send_quotation_contact_person_{{ $quotation->id }}').val();
        var send_quotation_email = $('#send_quotation_email_{{ $quotation->id }}').val();
        var send_quotation_subject = $('#send_quotation_subject_{{ $quotation->id }}').val();
        var send_quotation_note = $('#send_quotation_note_{{ $quotation->id }}').val();

        $('#send_quotation_form_save_button_{{ $quotation->id }}').prop('disabled', true);


        $.post("/quotations/ajax/send-quotation-for-approval",
            {
                quotation_id: quotation_id,
                send_quotation_contact_person: send_quotation_contact_person,
                send_quotation_email: send_quotation_email,
                send_quotation_subject: send_quotation_subject,
                send_quotation_note: send_quotation_note
            },
            function (data, status) {

                $.notify(data, "success");

                $('#send_quotation_form_save_button_{{ $quotation->id }}').prop('disabled', false);


                $('#quotation_send_for_approval_{{ $quotation->id }}').foundation('close');


                if(typeof ajaxLoadQuotationsList == 'function'){
                    ajaxLoadQuotationsList(1);
                }

                if(typeof ajaxLoadCustomerQuotations == 'function'){
                    ajaxLoadCustomerQuotations(1);
                }

            });
    }
</script>