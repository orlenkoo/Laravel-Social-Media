<p>A new lead is generated on {{ $lead->datetime }}</p>
<p>Lead Source : {{ $lead->lead_source }}</p>
<p>Campaign : {{ $lead->auto_tagged_campaign }}</p> <br><br>

<p>Please log on to your WEB360 account to view this lead in WEB 360.</p> <br>

<br>
<hr>
<h2>Lead Details: </h2>
@include('leads._partials.lead_meta_data')
<hr>
<p>
    Yours Sincerely, <br>
    The Web360 Team
</p>
