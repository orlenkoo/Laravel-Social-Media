{{ Form::hidden('organization_id', Session::get('user-organization-id')) }}
<div class="row">
    <div class="large-12 columns">
        {{ Form::label('team_name', 'Team Name *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('team_name', Input::old('team_name'), array('data-validation'=>'required')) }}</div>
        {{ $errors->first('team_name', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        {{ Form::label('team_lead_id', 'Team Lead *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::select('team_lead_id', $employees) }}</div>
        {{ $errors->first('team_lead_id', '<p class="alert-box alert radius">:message</p>') }}
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