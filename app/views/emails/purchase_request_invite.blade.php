<p>Dear <strong>{{ $approver['name'] }},</strong></p>

<p>Purchase request has been made
    by {{ $purchase_request->requestedBy->given_name }} {{ $purchase_request->requestedBy->surname }}, Please check the
    details below and accept it.</p>

<h2>Purchase Request Details</h2>

<table>
    <tr>
        <th>Date</th>
        <td>{{ $purchase_request->datetime }}</td>
    </tr>
    <tr>
        <th>Department</th>
        <td>{{ $purchase_request->department }}</td>
    </tr>
    <tr>
        <th>Type</th>
        <td>{{ $purchase_request->type }}</td>
    </tr>
    <tr>
        <th>Purpose</th>
        <td>{{ $purchase_request->purpose }}</td>
    </tr>
    <tr>
        <th>Description</th>
        <td>{{ $purchase_request->description }}</td>
    </tr>
    <tr>
        <th>Cost</th>
        <td>{{ $purchase_request->cost }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{{ $purchase_request->status }}</td>
    </tr>
</table>

<hr>

<p>
    <?php

    $url_purchase_request_approval = route('purchase_requests.approval.update', array('purchase_request_id' => $purchase_request->id, 'approved_by' => $approver['id']));

    ?>
    <a href="{{ $url_purchase_request_approval }}"
       style="font-size: 14px; color: #fff; background: #117700; padding: 10px; margin: 10px;">Update</a>
</p>

<hr>

<p>Thank You.</p>