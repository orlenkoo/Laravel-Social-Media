<table>
    <tr>
        <th>User Name</th>
        <th>Status</th>
        <th>Access Level</th>
        <th>Total Times Access</th>
        <th>Last Access Date & Time</th>
    </tr>
    @foreach($audit_trail_employees as $audit_trail_employee)
            <tr>
                <td> {{ $audit_trail_employee->getEmployeeFullName() }} </td>
                <td> {{ $audit_trail_employee->status ? 'Enabled' : 'Disabled' }} </td>
                <td> {{ $audit_trail_employee->user_level }} </td>
                <td> {{ AuditTrail::getTotalTimeAccess($audit_trail_employee->id,$from_date,$to_date) }} </td>
                <td> {{ AuditTrail::getLastAccessDateTime($audit_trail_employee->id,$from_date,$to_date) }} </td>
                <td></td>
            </tr>
    @endforeach
</table>


