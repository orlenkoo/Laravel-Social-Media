@if(is_object($event360_vendor_profile))
    {{ Form::hidden('event360_vendor_profile_id',$event360_vendor_profile->id) }}
@endif
<div class="row expanded">
    <div class="large-6 columns">
        {{ Form::label('title', 'Title (Company Name)', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('title', Input::old('title'), array('data-validation'=>'required')) }}</div>
        {{ $errors->first('title', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns">
        {{ Form::label('author', 'Author', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('author', Input::old('author')) }}</div>
        {{ $errors->first('author', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<div class="row expanded">
    <div class="large-6 columns">
        {{ Form::label('date', 'Date', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('date', '', array('class'=> 'datepicker')) }}</div>
        {{ $errors->first('date', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns">
            {{ Form::label('image_file', 'Image File', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::file('image_file', array('data-validation'=>'','class' => 'image_file','onchange' => 'checkFileSize()' )) }}</div>
            {{ $errors->first('image_file', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('testimonial', 'Testimonial *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::textarea('testimonial', Input::old('testimonial'), array('data-validation'=>'required')) }}</div>
        {{ $errors->first('testimonial', '<p class="alert-box alert radius">:message</p>') }}
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