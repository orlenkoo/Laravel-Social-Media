<div class="table-scroll">

    <table id="event360_vendor_testimonial_details" class="">
        <thead>
        <tr>
            <th>Title</th>
            <th>Testimonial</th>
            <th>Author</th>
            <th>Date</th>
            <th>Image</th>
            <th></th>
            <th>ID</th>
        </tr>
        </thead>
        <tbody>
        @foreach($testimonials as $testimonial)
            <tr>
                <td>{{ $testimonial->title }}</td>
                <td>{{ $testimonial->testimonial }}</td>
                <td>{{ $testimonial->author }}</td>
                <td>{{ $testimonial->date }}</td>
                <td>
                    <a href="{{ $testimonial->image_url }}" target="_blank"><img src="{{ $testimonial->image_url }}" alt="" class="dashboard_image_thumbnail"></a>
                </td>
                <td>
                    <a href="#" data-open="editTestimonialForm_{{ $testimonial->id }}"
                       class="button tiny">Edit</a>
                    <div id="editTestimonialForm_{{ $testimonial->id }}" class="reveal" data-reveal>

                        <h2>Edit Testimonial</h2>
                        {{ Form::model($testimonial, array('route' => array('event360.vendor_profile.testimonial.update', $testimonial->id), 'method' => 'put', 'class' => 'form-horizontal', 'files' => true )) }}
                        @include('v1.my_account._partials.testimonials_form_edit')
                        {{ Form::close() }}
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <script>
                            function checkFileSize_{{ $testimonial->id }}(){
                                var file_input = 'image_file_'+ {{ $testimonial->id }} + '';
                                var size =  $('.'+file_input)[0].files[0].size;
                                size = parseInt(parseInt(size) * 0.001);
                                var fileName = $('.'+file_input)[0].files[0].name;
                                var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
                                var fileExtension = fileExtension.trim();

                                if(size > <?php echo Event360VendorProfile::$image_upload_file_size_limit['value'];?>){
                                    $('.'+file_input).val("");
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
                        </script>
                    </div>
                </td>
                <td>{{ $testimonial->id }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

