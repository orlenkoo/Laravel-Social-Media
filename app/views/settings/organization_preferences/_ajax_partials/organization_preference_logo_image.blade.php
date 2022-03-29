@if($organization_preference)
    @if($organization_preference->logo_image_url != 'null' && $organization_preference->logo_image_url != null)
        <a href="{{ $organization_preference->logo_image_url  }}" target="_blank">
            <img class="logo-image" id="logo-image-img" src="{{ $organization_preference->logo_image_url }}" height="350px" width="150px"/>
        </a>
    @else
        <img class="logo-image" id="logo-image-img" src=""/>
    @endif
@endif