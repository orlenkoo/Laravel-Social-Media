<p>Dear {{ $quoted_by->given_name }} {{ $quoted_by->surname }},</p>

<p>Don’t forget to follow up on your quotation {{ $quotation->project_quote }} for <a target="_blank" href="https://web360.asia/customers/{{ $quotation->customer->id }}">{{ $quotation->customer->customer_name }}</a>!.</p>

<p>The WEB360 Team</p>
