@if(is_object($event360_vendor_profile))
    {{ Form::hidden('event360_vendor_profile_id',$event360_vendor_profile->id) }}
@endif
<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('service', 'Service *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('service', Input::old('service'), array('data-validation'=>'required')) }}</div>
        {{ $errors->first('service', '<p class="alert-box alert radius">:message</p>') }}
    </div>

</div>
<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('description', 'Description', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::textarea('description', Input::old('description')) }}</div>
        {{ $errors->first('description', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('image_file', 'Image File', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::file('image_file', array('data-validation'=>'')) }}</div>
        {{ $errors->first('image_file', '<p class="callout">:message</p>') }}
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