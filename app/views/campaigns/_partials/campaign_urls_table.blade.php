<table>
    <tr>
        <th>Website URL</th>
        <th>Campaign Source</th>
        <th>Campaign Medium</th>
        <th>Campaign Name</th>
        <th>Campaign Term</th>
        <th>Campaign Content</th>
        <th></th>
    </tr>
    @foreach($campaign_urls as $campaign_url)
        <tr>
            <td>{{ $campaign_url->website_url }}</td>
            <td>{{ $campaign_url->campaign_source }}</td>
            <td>{{ $campaign_url->campaign_medium }}</td>
            <td>{{ $campaign_url->campaign_name }}</td>
            <td>{{ $campaign_url->campaign_term }}</td>
            <td>{{ $campaign_url->campaign_content }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6">
                <strong>URL:</strong> <span id="url_copy_to_clipboard_{{ $campaign_url->id }}">{{ CampaignUrl::getUTMUrl($campaign_url) }}</span>
            </td>
            <td>
                <input type="button" class="button tiny" onclick="copyToClipBoard('url_copy_to_clipboard_{{ $campaign_url->id }}')" value="Copy URL">
            </td>
        </tr>
    @endforeach
</table>