<p>Dear <strong>{{ $receiver }},</strong></p>
Purchase request made
    by {{ $purchase_request->requestedBy->given_name }} {{ $purchase_request->requestedBy->surname }}, has been updated.</p>

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
    <tr>
        <th>Finance Dept Comments</th>
        <td>{{ $purchase_request->finance_department_comments }}</td>
    </tr>
</table>

<hr>

<h2>Approval Status</h2>
<table>
    <thead>
    <tr>
        <th>By</th>
        <th>On</th>
        <th>Status</th>
        <th>Comments</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($purchase_request->purchaseRequestApprovals as $purchaseRequestApproval)
        <tr>
            <td>{{ $purchaseRequestApproval->approvedBy->given_name }} {{ $purchaseRequestApproval->approvedBy->surname }}</td>
            <td>{{ $purchaseRequestApproval->datetime }}</td>
            <td style="color: <?php echo $purchaseRequestApproval->status == 'Approved' ? 'green' : 'red' ?>">
                {{ $purchaseRequestApproval->status }}
            </td>
            <td>
                {{ $purchaseRequestApproval->approver_comments }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<hr>
<p>
    <?php

    $url_purchase_request_approval = route('purchase_requests.index');

    ?>
    <a href="{{ $url_purchase_request_approval }}"
       style="font-size: 14px; color: #fff; background: #117700; padding: 10px; margin: 10px;">Check on Webtics</a>
</p>

<hr>

<p>Thank You.</p>