{{ Form::hidden('organization_id', $attachmenttype->organization_id) }}
<div class="row">
    <div class="large-12 columns">
        {{ Form::label('type', 'Attachement Type *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('type', Input::old('type'), array('data-validation'=>'required')) }}</div>
        {{ $errors->first('type', '<p class="alert-box alert radius">:message</p>') }}
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
