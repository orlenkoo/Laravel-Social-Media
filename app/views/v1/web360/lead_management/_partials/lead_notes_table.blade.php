<div class="table-scroll">

    <table>
        <tr>
            <th>On</th>
            <th>By</th>
            <th>note</th>

        </tr>
        @foreach($lead_notes as $lead_note)
            <tr>
                <td>{{ $lead_note->datetime }}</td>
                <td>
                    @if(is_object($lead_note->leadNoteCreatedBy))
                        {{ $lead_note->leadNoteCreatedBy->getEmployeeFullName() }}
                    @else
                        NA
                    @endif
                </td>
                <td>{{ $lead_note->note }}</td>
            </tr>
    @endforeach
    </table>
</div>
