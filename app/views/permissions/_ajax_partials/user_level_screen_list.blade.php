<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <h5>User Level Permissions</h5>

            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader', 'style' => 'display:none')) }}
            {{ Form::open(array('route' => 'screen_permissions.add','autocomplete' => 'off')) }}
            @include('screen_permissions._partials.user_level_screen_table')
            <div class="row save_bar">
                <div class="large-12 columns text-center">
                    {{ Form::submit('Save', array("class" => "button success tiny")) }}
                </div>
            </div>
            <div class="row loading_animation" style="display: none;">
                <div class="large-12 columns text-center">
                    {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
                </div>
            </div>
            {{ Form::close() }}


        </div>
    </div>
</div>

