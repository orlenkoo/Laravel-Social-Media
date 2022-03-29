{{-- show images grid --}}
<em>Add Gallery Image - You can add {{ 25 - count($images) }} more.</em>
<ul class="row small-up-2 medium-up-3 large-up-4">
    @foreach($images as $image)
        <li class="column column-block">

            {{ Form::button('Delete Image', array("class" => "button tiny delete-button right", "onclick" => "deleteImageByID(".$image->id.")")) }}

            <a href="{{ $image->image_url }}" target="_blank"><img src="{{ $image->image_url }}" alt="" class=""></a>

        </li>
    @endforeach
</ul>

<script>
    function deleteImageByID(image_id) {
        var r = confirm("WARNING!!! Are sure you want to delete this image? Deletion is permanent, please double check.");
        if (r == true) {
            $.post("event360-vendor-profile/image/delete", {id: image_id}, function (data) {
                $.notify('Deleted Successfully.');
                //alert(data);
                loadGalleryImagesList();;

            });
        } else {
            return false;
        }
    }
</script>