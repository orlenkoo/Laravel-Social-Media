@if(count($event360_call_leads) > 0 )
    <div style="overflow: scroll;">
        <table id="call_data_table">
            <tr>
                <th>Incoming Call Number</th>
                <th>Tracking Number</th>
                <th>Termination Number</th>
                {{--<th>Source</th>--}}
                <th>Date & Time</th>
                <th>Result</th>
                <th>Vendor Name / Source</th>
                <th>Call Duration</th>
                <th>Total Duration</th>
                {{--<th>Call Play Back</th>--}}
                <th>Recording URL</th>
                <th>Play Recording</th>
                <th>Lead Rating</th>
                <th>Share</th>
                <th>Notes</th>
            </tr>
            @foreach($event360_call_leads as $lead)
                <?php $event360_call = $lead->event360Call ?>

                    <tr>
                        <td>{{ $event360_call->incoming_call_number }}</td>
                        <td>{{ $event360_call->number1300 }}</td>
                        <td>{{ $event360_call->transferred_number }}</td>
                        {{--<td>{{ $event360_call->keyword }}</td>--}}
                        <td>{{ $event360_call->time }}</td>
                        <td>{{ $event360_call->result }}</td>
                        <td>{{ $event360_call->dealer_name }}</td>
                        <td>{{ $event360_call->duration }}</td>
                        <td>{{ $event360_call->durationof1300 }}</td>
                        {{--<td></td>--}}
                        <td>
                            @if($event360_call->recording_url != '')
                                <a href="https://pla.delaconcorp.com{{ $event360_call->recording_url }}" target="_blank"
                                   class="tiny button">Download</a>
                            @else
                                NA
                            @endif

                        </td>
                        <td>
                            @if($event360_call->recording_url != '')
                                <a href="#" data-open="lead_event360_calls_player_modal" class="button tiny" onclick="playButtonClick_<?php echo $lead->id; ?>();">Play ></a>
                                <script>
                                    function playButtonClick_<?php echo $lead->id; ?>(){
                                        loadttwMusicPlayer([{
                                            mp3:'https://pla.delaconcorp.com<?php echo $event360_call->recording_url; ?>'
                                        }]);

                                    }
                                </script>
                            @else
                                NA
                            @endif

                        </td>
                        <td>
                            <select name="" id="" onchange="updateAjaxLeadRating({{ $lead->id }}, this.value)">
                                @foreach(Lead::$lead_ratings as $lead_rating)
                                    <option value="{{ $lead_rating }}" <?php echo $lead->lead_rating == $lead_rating ? "selected" : ""; ?>>{{ $lead_rating }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>

                            <a href="#" data-open="forward_lead_event360_calls_{{ $lead->id }}" class="button tiny">Forward ></a>

                            <div id="forward_lead_event360_calls_{{ $lead->id }}" class="reveal panel" data-reveal>
                                <div class="panel-heading"><h4>Forward Lead Details</h4></div>
                                <div class="panel-content">@include('v1.web360.lead_management._partials.forward_lead')</div>
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                        </td>
                        <td>

                            <a href="#" data-open="lead_notes_event360_calls_{{ $lead->id }}" class="button tiny">Notes ></a>

                            <div id="lead_notes_event360_calls_{{ $lead->id }}" class="reveal panel" data-reveal>
                                <div class="panel-heading"><h4>Lead Notes</h4></div>
                                <div class="panel-content">
                                    @include('v1.web360.lead_management._partials.lead_notes')
                                </div>
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                        </td>
                    </tr>

            @endforeach
        </table>
    </div>

    <div id="lead_event360_calls_player_modal" class="reveal panel" data-reveal>
        <div class="panel-heading"><h4>Play Recording</h4></div>
        <div class="panel-content"><div id = "lead_event360_calls_player_div"></div></div>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <script type="text/javascript">

        function loadttwMusicPlayer(audio_recording){
            $('#lead_event360_calls_player_div').html("");
            $('#lead_event360_calls_player_div').ttwMusicPlayer(audio_recording, {
                autoPlay:false,
                jPlayer:{
                    swfPath:'../plugin/jquery-jplayer' //You need to override the default swf path any time the directory structure changes
                }
            });

        }

    </script>

@else
    <p class="alert-box info radius">No call records found for the given time period.</p>
@endif






