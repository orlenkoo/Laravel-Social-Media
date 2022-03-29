{{ Form::hidden('event360_vendor_profile_id',$event360_vendor_profile_ad_banner->event360_vendor_profile_id) }}
<div class="row expanded">
    <div class="large-4 columns">
        {{ Form::label('ad_title', 'Ad Title *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('ad_title', Input::old('ad_title'), array('data-validation'=>'required')) }}</div>
        {{ $errors->first('ad_title', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        {{ Form::label('from_datetime', 'From date', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('from_datetime', $event360_vendor_profile_ad_banner->from_datetime, array('class'=> 'datepicker-webclickz-timepicker')) }}</div>
        {{ $errors->first('from_datetime', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        {{ Form::label('to_datetime', 'To date', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('to_datetime', $event360_vendor_profile_ad_banner->to_datetime, array('class'=> 'datepicker-webclickz-timepicker')) }}</div>
        {{ $errors->first('to_datetime', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('ad_type', 'Ad Type', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::select('ad_type', Event360VendorProfileAdBanner::$ad_type, $event360_vendor_profile_ad_banner->ad_type, array('onchange' => 'changeAdType_'. $event360_vendor_profile_ad_banner->id .'(this.value)')) }}</div>
        {{ $errors->first('ad_type', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<div class="row expanded" id="ad_type_image_{{ $event360_vendor_profile_ad_banner->id }}" style="display: {{ $event360_vendor_profile_ad_banner->ad_type == 'Image'? 'block': 'none' }};">
    <div class="large-6 columns">
        {{ Form::label('image_file', 'Image File (380 x 375 px)', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::file('image_file', array('data-validation'=>'','class' => 'image_file_'.$event360_vendor_profile_ad_banner->id,'onchange' => 'checkFileSize_'.$event360_vendor_profile_ad_banner->id.'()')) }}</div>
        {{ $errors->first('image_file', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns">
        <a href="{{ $event360_vendor_profile_ad_banner->image_url }}" target="_blank"><img src="{{ $event360_vendor_profile_ad_banner->image_url }}" alt="" class="dashboard_image_thumbnail"></a>
    </div>
</div>
<div class="row expanded" id="ad_type_video_{{ $event360_vendor_profile_ad_banner->id }}" style="display: {{ $event360_vendor_profile_ad_banner->ad_type == 'Video'? 'block': 'none' }};">
    <div class="large-6 columns">
        {{ Form::label('video_url', 'Video URL (YouTube URL)', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('video_url', $event360_vendor_profile_ad_banner->video_url, array('data-validation'=>'')) }}</div>
        {{ $errors->first('video_url', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns">
        <iframe width="100%" height="315" src="{{ $event360_vendor_profile_ad_banner->video_url }}" frameborder="0" allowfullscreen></iframe>
    </div>
</div>
<script>
    function changeAdType_{{ $event360_vendor_profile_ad_banner->id }}(ad_type) {
        if(ad_type == 'Image') {
            $('#ad_type_image_{{ $event360_vendor_profile_ad_banner->id }}').show();
            $('#ad_type_video_{{ $event360_vendor_profile_ad_banner->id }}').hide();
        } else if (ad_type == 'Video') {
            $('#ad_type_image_{{ $event360_vendor_profile_ad_banner->id }}').hide();
            $('#ad_type_video_{{ $event360_vendor_profile_ad_banner->id }}').show();
        }
    }
</script>
<div class="row expanded">
    <div class="large-6 columns">
        {{ Form::label('ad_url_type', 'Ad Url Type', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::select('ad_url_type', Event360VendorProfileAdBanner::$ad_url_type, $event360_vendor_profile_ad_banner->ad_url_type, array('onchange' => 'changeAdUrlType_'.$event360_vendor_profile_ad_banner->id.'(this.value)')) }}</div>
        {{ $errors->first('ad_url_type', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns" id="show_ad_url_{{ $event360_vendor_profile_ad_banner->id }}" style="display: {{ $event360_vendor_profile_ad_banner->ad_url_type == 'Other'? 'block': 'none' }};">
        {{ Form::label('ad_url', 'Ad URL', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('ad_url', $event360_vendor_profile_ad_banner->ad_url, array('data-validation'=>'')) }}</div>
        {{ $errors->first('ad_url', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<script>
    function changeAdUrlType_{{ $event360_vendor_profile_ad_banner->id }}(ad_url_type) {
        if(ad_url_type == 'Other') {
            $('#show_ad_url_{{ $event360_vendor_profile_ad_banner->id }}').show();
        } else if (ad_url_type == 'Event360 Profile') {
            $('#show_ad_url_{{ $event360_vendor_profile_ad_banner->id }}').hide();
        }
    }
</script>
<div class="row expanded">
    <div class="large-12 columns">
        <p class="info alert-box radius">Show this ad banner when following are selected</p>
        <hr>
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

<script>

    function checkFileSize_<?php echo $event360_vendor_profile_ad_banner->id; ?>(){
        var size =  $('.image_file_<?php echo $event360_vendor_profile_ad_banner->id; ?>')[0].files[0].size;
        size = parseInt(parseInt(size) * 0.001);
        var fileName = $('.image_file_<?php echo $event360_vendor_profile_ad_banner->id; ?>')[0].files[0].name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
        var fileExtension = fileExtension.trim();

        if(size > <?php echo Event360VendorProfile::$image_upload_file_size_limit['value'];?>){
            $('.image_file_<?php echo $event360_vendor_profile_ad_banner->id; ?>').val("");
            $.notify("<?php echo Event360VendorProfile::$image_upload_file_size_limit['message'];?>");
            return false;
        }

        if(fileExtension.localeCompare('jpg') != 0 && fileExtension.localeCompare('jpeg') != 0 && fileExtension.localeCompare('png') != 0 ){
            $('.image_file_<?php echo $event360_vendor_profile_ad_banner->id; ?>').val("");
            $.notify("Image must be of type jpg, jpeg, or png. Please check and upload.");
            return false;
        }

        return true;
    }




    $(document).foundation();
    
</script>