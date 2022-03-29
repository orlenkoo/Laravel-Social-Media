@if(in_array($image_type,array('Gallery','Profile')))
    {{ Form::hidden('event360_vendor_profile_id', $event360_vendor_profile->id) }}
@else
    {{ Form::hidden('event360_vendor_profile_id', $event360_vendor_profile_id) }}
    {{ Form::hidden('event360_vendor_service_id', $service->id) }}
@endif
{{ Form::hidden('image_type', $image_type) }}
<div class="fallback">
    {{ Form::file('file') }}
</div>

