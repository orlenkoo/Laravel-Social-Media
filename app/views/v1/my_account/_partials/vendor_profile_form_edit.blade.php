{{ Form::model($event360_vendor_profile, array('route' => array('event360.vendor_profile.profile.edit', $event360_vendor_profile->id), 'method' => 'put', 'class' => 'form-horizontal', 'files' => true )) }}

<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('about_us', 'About Us *', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::textarea('about_us', '', array('onchange' => '',  'data-validation'=>'required','id' => 'about_us_'.$event360_vendor_profile->id)) }}</div>
        {{ $errors->first('about_us', '<p class="alert-box alert radius">:message</p>') }}
    </div>

    <script>

        ClassicEditor.create( document.querySelector( '#about_us_{{$event360_vendor_profile->id}}' ) )
                .catch( error => {
            console.error( error );
        }).then( editor => {
        editor.setData('{{trim($event360_vendor_profile->about_us )}}');
        editor.on('change:prop', editor => {
            editor.updateElement();
        })
        });

    </script>
</div>
<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('facebook_url', 'Facebook URL', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('facebook_url', $event360_vendor_profile->facebook_url, array('data-validation'=>'')) }}</div>
        {{ $errors->first('facebook_url', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::label('map_url', 'Map URL', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::text('map_url', $event360_vendor_profile->map_url, array('data-validation'=>'')) }}</div>
        {{ $errors->first('map_url', '<p class="alert-box alert radius">:message</p>') }}
    </div>
</div>
<hr>
<div class="row expanded">
    <div class="large-6 columns">
        {{ Form::label('image_file', 'Logo Image File', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::file('image_file', array('data-validation'=>'required','class' => 'profile_image_file', 'onchange' => 'checkFileSize_profile_image_file()')) }}</div>
        {{ $errors->first('image_file', '<p class="alert-box alert radius">:message</p>') }}
    </div>
    <div class="large-6 columns">
                <a href="{{ $event360_vendor_profile->logo_image_url }}" target="_blank"><img src="{{ $event360_vendor_profile->logo_image_url }}" alt="" class="dashboard_image_thumbnail" width="100"></a>
    </div>
</div>
<hr>

{{--<div class="row save_bar">--}}
    {{--<div class="large-12 columns text-center">--}}
        {{--{{ Form::submit('Save About Us', array("class" => "button success tiny")) }}--}}

    {{--</div>--}}
{{--</div>--}}

<div class="row loading_animation" style="display: none;">
    <div class="large-12 columns text-center">
        {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
    </div>
</div>



{{ Form::close() }}

<script>

    function checkFileSize_profile_image_file(){
        var size =  $('.profile_image_file')[0].files[0].size;
        var fileName = $('.profile_image_file')[0].files[0].name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
        var fileExtension = fileExtension.trim();

        size = parseInt(parseInt(size) * 0.001);
        if(size > <?php echo Event360VendorProfile::$image_upload_file_size_limit['value'];?>){
            $('.profile_image_file').val("");
            $.notify("<?php echo Event360VendorProfile::$image_upload_file_size_limit['message'];?>");
            return false;
        }

        if(fileExtension.localeCompare('jpg') != 0 && fileExtension.localeCompare('jpeg') != 0 && fileExtension.localeCompare('png') != 0 ){
            $('.profile_image_file').val("");
            $.notify("Image must be of type jpg, jpeg, or png. Please check and upload.");
            return false;
        }
        return true;
    }



</script>