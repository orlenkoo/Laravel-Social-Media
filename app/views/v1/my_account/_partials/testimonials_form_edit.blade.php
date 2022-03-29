{{ Form::hidden('event360_vendor_profile_id',$testimonial->event360_vendor_profile_id) }}
<div class="row expanded">
    <div class="large-6 columns">
        {{ Form::label('title', 'Title (Company Name)', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('title', $testimonial->title, array('data-validation'=>'required')) }}</div>
        {{ $errors->first('title', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns">
        {{ Form::label('author', 'Author', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('author', $testimonial->author) }}</div>
        {{ $errors->first('author', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<div class="row expanded">
    <div class="large-4 columns">
        {{ Form::label('date', 'Date', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('date', $testimonial->date, array('class'=> 'datepicker')) }}</div>
        {{ $errors->first('date', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        {{ Form::label('image_file', 'Image File', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::file('image_file', array('data-validation'=>'','class' => 'image_file_'.$testimonial->id ,'onchange' => 'checkFileSize_'.$testimonial->id.'()')) }}</div>
        {{ $errors->first('image_file', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        <a href="{{ $testimonial->image_url }}" target="_blank"><img src="{{ $testimonial->image_url }}" alt="" class="dashboard_image_thumbnail"></a>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('testimonial', 'Testimonial *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::textarea('testimonial', $testimonial->testimonial, array('data-validation'=>'required')) }}</div>
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