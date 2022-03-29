<table>
    <tr>
        <th>Channel *</th>
        <th>Status</th>
    </tr>
    @foreach($media_channels as $media_channel)
        <tr>
            <td><input id="media_channel_{{ $media_channel->id }}" name="media_channel_{{ $media_channel->id }}" type="text" value="{{ $media_channel->media_channel }}" onchange="ajaxUpdateIndividualFieldsOfModel('media_channels', '{{ $media_channel->id }}', 'media_channel', this.value, 'media_channel_{{ $media_channel->id }}', 'MediaChannel', true)"></td>
            <td>
                <select name="media_channel_status_{{ $media_channel->id }}" id="media_channel_status_{{ $media_channel->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('media_channels', '{{ $media_channel->id }}', 'status', this.value, 'media_channel_status_{{ $media_channel->id }}', 'MediaChannel')">
                    <option value="0" {{ $media_channel->status == 0? "selected": "" }}>Disabled</option>
                    <option value="1" {{ $media_channel->status == 1? "selected": "" }}>Enabled</option>
                </select>
            </td>
        </tr>
    @endforeach
</table>

<div class="panel-list-pagination" id="pagination_media_channels_list">
    <?php echo $media_channels->links(); ?>
</div>