<p>A Lead, {{ is_object($lead->customer)? $lead->customer->customer_name: "NA" }}, has been assigned to {{ is_object($lead_assignment->leadAssignedTo)? $lead_assignment->leadAssignedTo->getEmployeeFullName(): 'NA' }} on {{ $lead_assignment->assigned_datetime }}</p>

<p>Please log on to your WEB360 account to view this lead in WEB 360.</p> <br>


<br>
<hr>

@include('leads._partials.lead_meta_data')

<p>
    Yours Sincerely, <br>
    The Web360 Team
</p>
