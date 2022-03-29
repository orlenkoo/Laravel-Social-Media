<table id="user_level_details" class="">
    <thead>
    <tr>
        <th>Employee</th>
        @foreach($screens as $screen)
            <th>{{ $screen->screen_name }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
    @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->given_name }} {{ $employee->surname }}</td>
            @foreach($screens as $screen)
                <td>
                    <input type="checkbox" name="screen_permission_{{ $screen->id }}"
                           id="screen_permission_{{ $screen->id }}">
                </td>
            @endforeach
        </tr>
    @endforeach

    </tbody>
</table>

