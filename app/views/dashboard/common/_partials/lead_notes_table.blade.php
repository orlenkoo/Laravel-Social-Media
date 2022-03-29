<table>
    <tr>
        <th>
            Note
        </th>
        <th>
            Added By
        </th>
        <th>
            On
        </th>
    </tr>
    @foreach($lead_notes as $lead_note)
        <tr>
            <td>{{ $lead_note->note }}</td>
            <td>{{ is_object($lead_note->leadNoteCreatedBy)? $lead_note->leadNoteCreatedBy->getEmployeeFullName(): "No Author" }}</td>
            <td>{{ $lead_note->datetime }}</td>
        </tr>
    @endforeach
</table>