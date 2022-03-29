<table>
    <tr>
        <th>Page Name</th>
        <th>Url</th>
        <th>Campaign</th>
        <th>Status</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($landing_pages as $landing_page)
        <tr>
            <td>{{ $landing_page->page_name }}</td>
            <td>{{ $landing_page->url }}</td>
            <td>
                @if(is_object($landing_page->campaign))
                    {{ $landing_page->campaign->campaign_name }}
                @else
                    NA
                @endif
            </td>
            <td>{{ $landing_page->status }}</td>
            <td>
                <button class="button tiny float-right" type="button" data-open="reveal_publish_export_landing_page_{{ $landing_page->id }}">Publish / Export</button>
                <div class="panel reveal" id="reveal_publish_export_landing_page_{{ $landing_page->id }}" name="reveal_publish_export_landing_page" data-reveal>
                    @include('landing_pages._partials.landing_page_publish_export_form')
                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </td>
            <td>
                <a class="button tiny float-right"  href="{{ route('landing_pages.ajax.show_landing_page_builder',  array('landing_page_id' => $landing_page->id)) }}" target="_blank">Edit</a>
            </td>
        </tr>
    @endforeach
</table>

<script>
    function ajaxUpdatelanding_pageStatus(landing_page_id, status) {
        $.post("/landing_pages/ajax/update-landing_page-status",
            {
                landing_page_id: landing_page_id,
                status: status
            },
            function (data, status) {

                $.notify(data, "success");

            });
    }
</script>


