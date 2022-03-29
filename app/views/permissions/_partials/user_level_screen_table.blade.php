<table id="user_level_details" class="">
    <thead>
    <tr>
        <th>User Level</th>
        @foreach($screens as $screen)
            <th>{{ $screen->screen_name }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
    @foreach($user_levels as $user_level)
        <tr>
            <td>{{ $user_level->user_level }}</td>
            @foreach($screens as $screen)
                <td>
                    {{ Form::checkbox('screen_permission', '1', true , array('id' => 'screen_permission_'.$user_level->id.'_'.$screen->id)); }}
                </td>
            @endforeach
        </tr>
    @endforeach

    </tbody>
</table>

<script>
    // function to update resigned
    function updateUserLevelScreenPermission(user_level_id, screen_id, obj) {
        if (obj.checked) {

        } else {

        }


    }

</script>