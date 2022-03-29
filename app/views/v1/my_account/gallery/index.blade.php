<div class="row expanded">
    <div class="large-12 columns">

        {{ Form::open(array('route' => 'event360.vendor_profile.image.upload', 'class'=>'dropzone', 'id' => 'gallery_image_upload_form', 'files' => true,'autocomplete' => 'off')) }}
        @include('v1.my_account._partials.image_upload_form', array('image_type' => 'Gallery'))
        {{ Form::close() }}

        <hr>

        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_gallery_images_list', 'style' => 'display:none')) }}
        <div id="gallery_images_list">

        </div>

    </div>
</div>

<script>
    function loadGalleryImagesList() {
        $('#gallery_images_list').html('');
        $('#loader_gallery_images_list').show();
        $('#gallery_images_list').hide();

        $.ajax({
            url: '/event360-vendor-profile/images/list?image_type=Gallery&allowed_number_of_images=25&event360_vendor_profile_id=<?php echo $event360_vendor_profile->id; ?>'
        }).done(function (data) {
            $('#gallery_images_list').html(data);
            $('#loader_gallery_images_list').hide();
            $('#gallery_images_list').show();
            $(document).foundation();
        });
    }
    loadGalleryImagesList();
</script>

<script>
    Dropzone.autoDiscover = false;
    // or disable for specific dropzone:
    //galleryImageUploadForm camelized version of the HTML element's id gallery_image_upload_form
    Dropzone.options.galleryImageUploadForm = {
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
        var myDropzone = new Dropzone("#gallery_image_upload_form");
        myDropzone.on("complete", function(file) {
            /* Maybe display some more file information on your page */
            loadGalleryImagesList();
        });
    })
</script>