{{ Form::hidden('organization_id', Session::get('user-organization-id')) }}
<div class="row">
    <div class="large-6 columns">
        {{ Form::label('user_level', 'User Level *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('user_level', Input::old('user_level'), array('data-validation'=>'required')) }}</div>
        {{ $errors->first('user_level', '<p class="alert-box alert radius">:message</p>') }}
    </div>

</div>

<hr>

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