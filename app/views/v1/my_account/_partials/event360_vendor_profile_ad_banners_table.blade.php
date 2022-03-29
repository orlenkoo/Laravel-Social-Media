<div class="table-scroll">

    <table id="event360_vendor_profile_ad_banners_details" class="sort-enable ">
        <thead>
        <tr>
            <th>Title</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Ad Type</th>
            <th>Image/Video</th>
            <th>Status</th>
            <th></th>
            <th>ID</th>

        </tr>
        </thead>
        <tbody>
        @foreach($event360_vendor_profile_ad_banners as $event360_vendor_profile_ad_banner)
            <tr>
                <td>
                    {{ $event360_vendor_profile_ad_banner->ad_title }}
                </td>
                <td>
                    {{ $event360_vendor_profile_ad_banner->from_datetime }}
                </td>
                <td>
                    {{ $event360_vendor_profile_ad_banner->to_datetime }}
                </td>
                <td>{{ $event360_vendor_profile_ad_banner->ad_type }}</td>
                <td>
                    @if($event360_vendor_profile_ad_banner->ad_type == 'Image')
                    <a href="#" data-open="event360_vendor_profile_ad_banner_image_{{ $event360_vendor_profile_ad_banner->id }}" class="">
                        <img src="{{ $event360_vendor_profile_ad_banner->image_url }}" alt="" class="dashboard_image_thumbnail" style="width: 200px;height: 100px;">
                    </a>

                    <div class="reveal" id="event360_vendor_profile_ad_banner_image_{{ $lead->id }}" data-reveal>

                        <img src="{{ $event360_vendor_profile_ad_banner->image_url }}" alt="" class="dashboard_image_thumbnail">
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @else
                        <iframe width="100%" height="315" src="{{ $event360_vendor_profile_ad_banner->video_url }}" frameborder="0" allowfullscreen></iframe>
                    @endif
                </td>
                <td>
                    @if($event360_vendor_profile_ad_banner->status == 1)
                        Enabled {{ link_to_route('event360_vendor_profile_ad_banners.disable', 'Disable', array($event360_vendor_profile_ad_banner->id), array("class" => 'button alert tiny')) }}
                    @else
                        Disabled {{ link_to_route('event360_vendor_profile_ad_banners.enable', 'Enable', array($event360_vendor_profile_ad_banner->id), array("class" => 'button success tiny')) }}
                    @endif
                </td>

                <td>

                    <a href="#" data-open="event360_vendor_profile_ad_banners_add_banner_form_{{ $event360_vendor_profile_ad_banner->id }}" class="button tiny">Edit
                        ></a>

                    <div class="reveal" id="event360_vendor_profile_ad_banners_add_banner_form_{{ $lead->id }}" data-reveal>
                        <h2>Edit Ad Banner</h2>
                        {{ Form::model($event360_vendor_profile_ad_banner, array('route' => array('event360.vendor_profile.ad_banner.update', $event360_vendor_profile_ad_banner->id), 'method' => 'post','files' => true, 'class' => 'form-horizontal')) }}
                        @include('v1.my_account._partials.event360_vendor_profile_ad_banners_form_edit')
                        {{ Form::close() }}
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </td>
                <td>{{ $event360_vendor_profile_ad_banner->id }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


