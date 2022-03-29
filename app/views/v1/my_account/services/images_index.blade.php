{{ Form::open(array('route' => 'event360.vendor_profile.image.upload', 'class'=>'dropzone', 'id' => 'service_image_upload_form_'.$service->id, 'files' => true,'autocomplete' => 'off')) }}
    @include('v1.my_account._partials.image_upload_form', array('image_type' => 'Service'))
{{ Form::close() }}

<hr>

{{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_service_images_list_'.$service->id, 'style' => 'display:none')) }}
<div id="service_images_list_<?php echo $service->id; ?>">

</div>

<script>
    function loadServiceImagesList_<?php echo $service->id; ?>() {
        $('#service_images_list_<?php echo $service->id; ?>').html('');
        $('#loader_service_images_list_<?php echo $service->id; ?>').show();
        $('#service_images_list_<?php echo $service->id; ?>').hide();

        $.ajax({
            url: '/event360-vendor-profile/images/list?image_type=Service&allowed_number_of_images=25&event360_vendor_profile_id=<?php echo $service->event360VendorProfile->id; ?>&event360_vendor_service_id=<?php echo $service->id; ?>'
        }).done(function (data) {
            $('#service_images_list_<?php echo $service->id; ?>').html(data);
            $('#loader_service_images_list_<?php echo $service->id; ?>').hide();
            $('#service_images_list_<?php echo $service->id; ?>').show();
        });
    }
    loadServiceImagesList_<?php echo $service->id; ?>();
</script>

<script>
    Dropzone.autoDiscover = false;
    // or disable for specific dropzone:
    //serviceImageUploadForm camelized version of the HTML element's id service_image_upload_form_
    Dropzone.options.serviceImageUploadForm<?php echo $service->id; ?> = {
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
        var myDropzone = new Dropzone("#service_image_upload_form_<?php echo $service->id; ?>");
        myDropzone.on("complete", function(file) {
            /* Maybe display some more file information on your page */
            loadServiceImagesList_<?php echo $service->id; ?>();
        });
    })
</script>