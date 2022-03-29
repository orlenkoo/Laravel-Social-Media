<div class="row expanded">
    <div class="large-12 columns">
        <p><a href="#" data-open="newTestimonialForm" class="button tiny">Add New Testimonial</a></p>
        <div id="newTestimonialForm" class="reveal" data-reveal>
            <h2>Add New Testimonial</h2>
            {{ Form::open(array('route' => 'event360.vendor_profile.testimonial.add', 'files' => true ,'autocomplete' => 'off')) }}
            @include('v1.my_account._partials.testimonials_form')
            {{ Form::close() }}
            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">


            {{ Form::text('s', null, array('placeholder' => 'Search by Title', 'onchange' => 'getAjaxTestimonialsList()', 'id' => 'testimonial_search_query')) }}
        <hr>
    </div>
</div>

<div class="row expanded">
    <div class="large-12 columns">

            <h5>Testimonials</h5>
            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_testimonials_list', 'style' => 'display:none')) }}
            <div id="testimonials_list">

            </div>

    </div>
</div>

<script>


    /*==================== PAGINATION =========================*/

    $(document).on('click', '#pagination_event360_vendor_testimonial_list a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        //location.hash = page;
        getAjaxTestimonialsList(page);
    });


    function getAjaxTestimonialsList(page) {


        $('#loader_testimonials_list').show();
        $('#testimonials_list').hide();
        $('#testimonials_list').html('');

        var search_query = $('#testimonial_search_query').val();


        $.ajax({
            url: '/event360-vendor-profile/ajax/get/testimonials?page=' + page + '&search_query=' + search_query + '&event360_vendor_profile_id=<?php echo $event360_vendor_profile->id;?>'
        }).done(function (data) {
            $('#testimonials_list').html(data);
            $('#loader_testimonials_list').hide();
            $('#testimonials_list').show();
            $("#event360_vendor_testimonial_details").tablesorter();
            $('.datepicker').datetimepicker({
                format: 'Y-m-d',
                lang: 'en',
                scrollInput: false
            });

            $.validate({
                onSuccess: function () {
                    $('.save_bar').css('display', 'none');
                    $('.loading_animation').css('display', 'block');
                },
            });
            $(document).foundation();
        });
    }

    function checkFileSize(){
        var size =  $('.image_file')[0].files[0].size;
        size = parseInt(parseInt(size) * 0.001);
        var fileName = $('.image_file')[0].files[0].name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
        var fileExtension = fileExtension.trim();

        if(size > <?php echo Event360VendorProfile::$image_upload_file_size_limit['value'];?>){
            $('.image_file').val("");
            $.notify("<?php echo Event360VendorProfile::$image_upload_file_size_limit['message'];?>");
            return false;
        }

        if(fileExtension.localeCompare('jpg') != 0 && fileExtension.localeCompare('jpeg') != 0 && fileExtension.localeCompare('png') != 0 ){
            $('.image_file').val("");
            $.notify("Image must be of type jpg, jpeg, or png. Please check and upload.");
            return false;
        }

        return true;
    }

    getAjaxTestimonialsList(1);
</script>
