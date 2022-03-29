{{ Form::open(array('route' => 'event360.vendor_profile.image.upload', 'class'=>'dropzone', 'id' => 'profile_image_upload_form', 'files' => true,'autocomplete' => 'off')) }}
@include('v1.my_account._partials.image_upload_form', array('image_type' => 'Profile'))
{{ Form::close() }}

<hr>

{{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_profile_images_list', 'style' => 'display:none')) }}
<div id="profile_images_list">

</div>

<script>
    function loadProfileImagesList() {
        $('#profile_images_list').html('');
        $('#loader_profile_images_list').show();
        $('#profile_images_list').hide();

        $.ajax({
            url: '/event360-vendor-profile/images/list?image_type=Profile&allowed_number_of_images=3&event360_vendor_profile_id=<?php echo $event360_vendor_profile->id; ?>'
        }).done(function (data) {
            $('#profile_images_list').html(data);
            $('#loader_profile_images_list').hide();
            $('#profile_images_list').show();
        });
    }
    loadProfileImagesList();
</script>

<script>
    Dropzone.autoDiscover = false;
    // or disable for specific dropzone:
    //galleryImageUploadForm camelized version of the HTML element's id gallery_image_upload_form
     Dropzone.options.profileImageUploadForm = {
         acceptedFiles: '.jpg, .jpeg, .png',
         maxFilesize: <?php echo (Event360VendorProfile::$image_upload_file_size_limit['value']/1000);?>,
         init: function() {
             this.on("error", function(file, message) {
                 $.notify("<?php echo Event360VendorProfile::$image_upload_file_size_limit['message'];?>");
                 this.removeFile(file);
             });
         }

     };

    $(function() {
        // Now that the DOM is fully loaded, create the dropzone, and setup the
        // event listeners
        var myDropzone = new Dropzone("#profile_image_upload_form");
        myDropzone.on("complete", function(file) {
            /* Maybe display some more file information on your page */
            loadProfileImagesList();
        });
    })
</script>