<table>
    <tr>
        <th>Campaign Name</th>
        <th>Campaign Content</th>
        <th>Call Tracking Number</th>
        <th>Cost</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Channels</th>
        <th>Status</th>
        <th>Point of Contact</th>
        <th>Campaign URLs</th>
    </tr>
    @foreach($campaigns as $campaign)
        <tr>
            <td><input id="campaign_name_{{ $campaign->id }}" name="campaign_name_{{ $campaign->id }}" type="text" value="{{ $campaign->campaign_name }}" onchange="ajaxUpdateIndividualFieldsOfModel('campaigns', '{{ $campaign->id }}', 'campaign_name', this.value, 'campaign_name_{{ $campaign->id }}', 'Campaign')"></td>
            <td><input id="campaign_content_{{ $campaign->id }}" name="campaign_content_{{ $campaign->id }}" type="text" value="{{ $campaign->campaign_content }}" onchange="ajaxUpdateIndividualFieldsOfModel('campaigns', '{{ $campaign->id }}', 'campaign_content', this.value, 'campaign_content_{{ $campaign->id }}', 'Campaign')"></td>
            <td><input id="campaign_content_{{ $campaign->id }}" name="call_tracking_number_{{ $campaign->id }}" type="text" value="{{ $campaign->call_tracking_number }}" onchange="ajaxUpdateIndividualFieldsOfModel('campaigns', '{{ $campaign->id }}', 'call_tracking_number', this.value, 'call_tracking_number_{{ $campaign->id }}', 'Campaign')"></td>
            <td><input id="campaign_cost_{{ $campaign->id }}" class="numbersonly" name="campaign_cost_{{ $campaign->id }}" type="text" value="{{ $campaign->cost }}" onchange="ajaxUpdateIndividualFieldsOfModel('campaigns', '{{ $campaign->id }}', 'cost', this.value, 'campaign_cost_{{ $campaign->id }}','Campaign')"></td>
            <td><input id="campaign_start_date_{{ $campaign->id }}" name="campaign_start_date_{{ $campaign->id }}" type="text" value="{{ $campaign->start_date }}" class="datepicker" onchange="ajaxUpdateIndividualFieldsOfModel('campaigns', '{{ $campaign->id }}', 'start_date', this.value, 'campaign_start_date_{{ $campaign->id }}', 'Campaign')"></td>
            <td><input id="campaign_end_date_{{ $campaign->id }}" name="campaign_end_date_{{ $campaign->id }}" type="text" value="{{ $campaign->end_date }}" class="datepicker" onchange="ajaxUpdateIndividualFieldsOfModel('campaigns', '{{ $campaign->id }}', 'end_date', this.value, 'campaign_end_date_{{ $campaign->id }}', 'Campaign')"></td>
            <td>
                <select name="campaign_media_channels_{{ $campaign->id }}" id="campaign_media_channels_{{ $campaign->id }}" multiple class="campaign-media-channels-selectize" onchange="ajaxUpdateCampaignTableMediaChannel('{{ $campaign->id }}')">
                    <?php
                        $media_channels = json_decode(MediaChannel::getMediaChannelsFilters());
                        $selected_media_channels = Campaign::getAllMediaChannelsForGivenCampaign($campaign->id);
                    ?>
                    <option value=''>Media Channels</option>
                    @foreach($media_channels as $media_channel)
                        @if(in_array($media_channel->id, $selected_media_channels, true))
                            <option value='{{ $media_channel->id }}' selected >{{ $media_channel->media_channel }}</option>
                        @else
                            <option value='{{ $media_channel->id }}'>{{ $media_channel->media_channel }}</option>
                        @endif
                    @endforeach
                </select>
            </td>
            <td>
                <select name="campaign_status_{{ $campaign->id }}" id="campaign_status_{{ $campaign->id }}" onchange="ajaxUpdateIndividualFieldsOfModel('campaigns', '{{ $campaign->id }}', 'status', this.value, 'campaign_status_{{ $campaign->id }}','Campaign')" >
                    <option value="0" {{ $campaign->status == 0? "selected": "" }}>Suspended</option>
                    <option value="1" {{ $campaign->status == 1? "selected": "" }}>Active</option>
                </select>
            </td>

            <td><input id="campaign_point_of_contact_{{ $campaign->id }}" name="campaign_point_of_contact_{{ $campaign->id }}" type="text" value="{{ $campaign->point_of_contact }}" onchange="ajaxUpdateIndividualFieldsOfModel('campaigns', '{{ $campaign->id }}', 'point_of_contact', this.value, 'campaign_point_of_contact_{{ $campaign->id }}', 'Campaign')"></td>
            <td>
                <a  data-open="popup_campaign_urls_{{ $campaign->id }}" class="button tiny" onclick="ajaxLoadCampaignsURLsList_{{ $campaign->id }}(1)">Create / View</a>
                <div class="reveal large" id="popup_campaign_urls_{{ $campaign->id }}" data-reveal>

                    <div class="row">
                        <div class="large-8 columns">
                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_campaign_urls_list_'.$campaign->id, 'class' => 'float-center', 'style' => 'display:none')) }}
                            <div id="response_campaign_urls_list_{{ $campaign->id }}">

                            </div>

                            <script>
                                /*==================== PAGINATION =========================*/

                                $(document).on('click', '#pagination_campaign_urls_list_{{ $campaign->id }} a', function (e) {
                                    e.preventDefault();
                                    var page = $(this).attr('href').split('page=')[1];
                                    //location.hash = page;
                                    ajaxLoadCampaignsURLsList_{{ $campaign->id }}(page);
                                });

                                function ajaxLoadCampaignsURLsList_{{ $campaign->id }}(page) {
                                    $('#loader_campaign_urls_list_{{ $campaign->id }}').show();
                                    $('#response_campaign_urls_list_{{ $campaign->id }}').hide();

                                    $.ajax({
                                        url: '/campaigns/ajax/load-campaign-urls-list?' +
                                        'page=' + page +
                                        '&campaign_id={{ $campaign->id }}'

                                    }).done(function (data) {
                                        $('#response_campaign_urls_list_{{ $campaign->id }}').html(data);
                                        $('#loader_campaign_urls_list_{{ $campaign->id }}').hide();
                                        $('#response_campaign_urls_list_{{ $campaign->id }}').show();
                                    });
                                }



                            </script>

                        </div>
                        <div class="large-4 columns">
                            <h4>Add New Campaign URL</h4>
                            @include('campaigns._partials.campaign_url_form')
                        </div>
                    </div>

                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach
</table>


