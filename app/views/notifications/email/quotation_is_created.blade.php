<p>A Quotation was created by {{ $quotation->quotedBy->getEmployeeFullName() }} for {{ $quotation->customer->customer_name }} on {{ $quotation->quoted_datetime }}.</p>
<p>Project Quote: {{ $quotation->project_quote }}</p>
<p>Quotation Value: {{ $quotation->net_total }}</p>

<p>Please log into your WEB360 account to view this quotation in WEB360.</p> <br>

<p>
    Yours Sincerely, <br>
    The Web360 Team
</p>
