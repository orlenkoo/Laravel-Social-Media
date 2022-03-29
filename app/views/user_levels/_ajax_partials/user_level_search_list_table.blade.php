<table id="user_level_details" class="">
    <thead>
    <tr>
        <th>User Level</th>
        <th>Status</th>
        <th></th>
        <th>ID</th>
    </tr>
    </thead>
    <tbody>
    @foreach($user_levels as $user_level)
        <tr>
            <td>{{ $user_level->user_level }}</td>
            <td>
                @if($user_level->user_level !='super_user')
                    @if($user_level->status == 1)
                        Enabled {{ link_to_route('user_levels.disable', 'Disable', array($user_level->id), array("class" => 'button alert tiny')) }}
                    @else
                        Disabled {{ link_to_route('user_levels.enable', 'Enable', array($user_level->id), array("class" => 'button success tiny')) }}
                    @endif
                @endif
            </td>
            <td>
                @if($user_level->user_level !='super_user')
                    <a href="#" data-reveal-id="user_level_edit_form_{{ $user_level->id }}" class="button tiny">Edit
                        ></a>

                    <div id="user_level_edit_form_{{ $user_level->id }}" class="reveal-modal" data-reveal
                         aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
                        <h2>Edit User Level</h2>
                        {{ Form::model($user_level, array('route' => array('user_levels.update', $user_level->id), 'method' => 'put', 'class' => 'form-horizontal')) }}
                        @include('user_levels._partials.form_edit')
                        {{ Form::close() }}
                        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                    </div>
                @endif

            </td>
            <td>{{ $user_level->id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

