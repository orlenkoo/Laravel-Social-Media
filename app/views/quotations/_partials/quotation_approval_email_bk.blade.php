<p>
    Dear {{ $send_quotation_contact_person }},
</p>

<p>Please check the following quotation for your enquiry. If your ok with this please click the Approve button at the end of this email. If not click the Negotiate Button.</p>

<p>If you approve the quotation I will be sending the printed version for your signature. If not I will be in touch you.</p>

<hr>

<p>
    <?php

        $url_accept = route('quotations.update_status_by_customer', array('quotation_id' => $quotation->id, 'status' => 'Closed' ));
        $url_reject = route('quotations.update_status_by_customer', array('quotation_id' => $quotation->id, 'status' => 'Negotiate' ));

    ?>
    <a href="{{ $url_accept }}" target="_blank" style="font-size: 14px; color: #fff; background: #117700; padding: 10px; margin: 10px;">Accept</a>
    <a href="{{ $url_reject }}" target="_blank" style="font-size: 14px; color: #fff; background: #aa1111; padding: 10px; margin: 10px;">Negotiate</a>
</p>

<p>
    Yours Faithfully,<br>
    @if(is_object($quotation->quotedBy))
        {{ $quotation->quotedBy->getEmployeeFullName() }}
    @endif
</p>