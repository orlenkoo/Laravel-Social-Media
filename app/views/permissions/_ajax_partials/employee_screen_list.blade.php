<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <h5>Employee Permissions</h5>

            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader', 'style' => 'display:none')) }}

            @include('screen_permissions._partials.employee_screen_table')

        </div>
    </div>
</div>

