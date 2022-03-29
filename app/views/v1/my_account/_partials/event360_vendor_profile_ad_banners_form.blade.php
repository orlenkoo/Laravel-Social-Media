@if(is_object($event360_vendor_profile))
    {{ Form::hidden('event360_vendor_profile_id',$event360_vendor_profile->id) }}
@endif
<div class="row expanded">
    <div class="large-4 columns">
        {{ Form::label('ad_title', 'Ad Title *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('ad_title', Input::old('ad_title'), array('data-validation'=>'required')) }}</div>
        {{ $errors->first('ad_title', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        {{ Form::label('from_datetime', 'From date', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('from_datetime',  '', array('class'=> 'datepicker-webclickz-timepicker')) }}</div>
        {{ $errors->first('from_datetime', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        {{ Form::label('to_datetime', 'To date', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('to_datetime', '', array('class'=> 'datepicker-webclickz-timepicker')) }}</div>
        {{ $errors->first('to_datetime', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<div class="row expanded">
    <div class="large-6 columns">
        {{ Form::label('ad_type', 'Ad Type', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::select('ad_type', Event360VendorProfileAdBanner::$ad_type, 'Image', array('onchange' => 'changeAdType(this.value)')) }}</div>
        {{ $errors->first('ad_type', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns" id="ad_type_image">
        {{ Form::label('image_file', 'Image File (380 x 375 px)', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::file('image_file', array('data-validation'=>'','class' => 'image_file','onchange' => 'checkFileSize()')) }}</div>
        {{ $errors->first('image_file', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns" id="ad_type_video" style="display: none;">
        {{ Form::label('video_url', 'Video URL (YouTube URL)', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('video_url', Input::old('video_url'), array('data-validation'=>'')) }}</div>
        {{ $errors->first('video_url', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>

<script>
    function changeAdType(ad_type) {
        if(ad_type == 'Image') {
            $('#ad_type_image').show();
            $('#ad_type_video').hide();
        } else if (ad_type == 'Video') {
            $('#ad_type_image').hide();
            $('#ad_type_video').show();
        }
    }
</script>
<div class="row expanded">
    <div class="large-6 columns">
        {{ Form::label('ad_url_type', 'Ad Url Type', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::select('ad_url_type', Event360VendorProfileAdBanner::$ad_url_type, 'Event360 Profile', array('onchange' => 'changeAdUrlType(this.value)')) }}</div>
        {{ $errors->first('ad_url_type', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns" id="show_ad_url" style="display: none">
        {{ Form::label('ad_url', 'Ad URL', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('ad_url', Input::old('ad_url'), array('data-validation'=>'')) }}</div>
        {{ $errors->first('ad_url', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<script>
    function changeAdUrlType(ad_url_type) {
        if(ad_url_type == 'Other') {
            $('#show_ad_url').show();
        } else if (ad_url_type == 'Event360 Profile') {
            $('#show_ad_url').hide();
        }
    }
</script>


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