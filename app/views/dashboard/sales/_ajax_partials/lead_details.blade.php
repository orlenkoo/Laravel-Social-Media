<input type="hidden" id="dashboard_selected_lead_id" name="dashboard_selected_lead_id"
       value="{{ $lead->id }}">

<div class="row">
    <div class="large-6 columns">
        <h3>
            @if(is_object($lead->customer))
                <a href="{{ route('customers.view', array('customer_id' => $lead->customer->id)) }}" target="_blank">{{ $lead->customer->customer_name }}</a>
            @else
                New Lead - Update Customer Name
            @endif
        </h3>
        @include('dashboard.common._partials.primary_contact', array('customer' => $lead->customer))
    </div>

    <div class="large-6 columns">
        @if(Employee::checkAuthorizedAccess(array('sales')))
            Lead Rating: <span id="sales_lead_detail_lead_rating">{{ $lead->lead_rating }}</span> <br>
            <label for="">
                Mark as Junk: <input type="checkbox" value="Junk" id="checkbox_mark_as_junk_{{ $lead->id }}" onchange="updateLeadRatingToJunk_{{ $lead->id }}('{{ $lead->id }}')" {{ $lead->lead_rating == "Junk"? 'checked': '' }}>
            </label>
            <hr>
            <script>
                function updateLeadRatingToJunk_{{ $lead->id }}(lead_id) {
                    var rating = "Lead";
                    if ($('#checkbox_mark_as_junk_'+lead_id).is(":checked"))
                    {
                        rating = "Junk";
                    }
                    ajaxUpdateIndividualFieldsOfModel('leads', lead_id, 'lead_rating', rating, 'lead_rating');

                    $('#sales_lead_detail_lead_rating').html(rating);

                }
            </script>
        @else
            <select name="lead_rating" id="lead_rating"
                    onchange="ajaxUpdateIndividualFieldsOfModel('leads', '{{ $lead->id }}', 'lead_rating', this.value, 'lead_rating')">
                @foreach(Lead::$lead_ratings as $key_lead_rating => $value_lead_rating )
                    <option value="{{ $key_lead_rating }}" {{ $lead->lead_rating == $key_lead_rating? "selected": ""  }}>{{ $value_lead_rating }}</option>
                @endforeach
            </select>
        @endif

        {{--<label for="">--}}
            {{--Assigned To:--}}
        {{--</label>--}}
        {{--{{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_assigned_to', 'class' => 'float-center', 'style' => 'display:none')) }}--}}
        {{--<select name="assigned_to" id="assigned_to" onchange="ajaxUpdateLeadAssignedTo()">--}}
        {{--</select>--}}
    </div>
    <script>

        // getAssignedToEmployeesList();
        function getAssignedToEmployeesList() {
            $.ajax({
                url: '/employees/ajax/get-employees-list'
            }).done(function (data) {
                $('#assigned_to').empty();
                data = $.parseJSON(data);
                $('#assigned_to').append("<option value=''>Select one</option>");

                for (var i in data) {
                    if (data[i].id == '{{ $lead->assigned_to }}') {
                        $('#assigned_to').append("<option value='" + data[i].id + "' selected>" + data[i].full_name + "</option>");
                    } else {
                        $('#assigned_to').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                    }

                }

                $('#assigned_to').selectize({
                    create: false,
                    sortField: 'text'
                });
            });
        }

        function ajaxUpdateLeadAssignedTo() {
            var assigned_to = $('#assigned_to').val();

            $.post("/leads/ajax/update-lead-assigned-to",
                {
                    lead_id: '{{ $lead->id }}',
                    assigned_to: assigned_to
                },
                function (data, status) {

                    $.notify(data, 'success');
                });
        }

    </script>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="large-12/columns">
        <ul class="tabs" data-tabs id="tabs_lead_details">
            <li class="tabs-title is-active"><a href="#panel_lead_details" aria-selected="true">Lead Details</a></li>
            <li class="tabs-title"><a href="#panel_lead_time_line" onclick="ajaxLoadDashboardLeadTimeLine(1)">Lead Time Line</a></li>
            <li class="tabs-title"><a href="#panel_lead_lead_notes" onclick="ajaxLoadDashboardLeadNotesList(1)">Lead Notes</a></li>
            @if(is_object($lead->customer))
                    <li class="tabs-title"><a href="#panel_lead_contacts" onclick="ajaxLoadContactsList(1)">Contacts</a>
                    </li>
            @endif
            @if(in_array($lead->lead_source, array('web360_enquiries', 'event360_calls', 'novocall_leads')))
                <li class="tabs-title"><a href="#panel_lead_meta_data_{{ $lead->id }}" aria-selected="true">@if($lead->lead_source == 'web360_enquiries')  {{ 'Website Form Data' }} @elseif($lead->lead_source == 'event360_calls') {{ 'Calls Submission Data' }} @elseif($lead->lead_source == 'novocall_leads') {{ 'Novocall Lead Data' }} @endif</a></li>
            @endif
        </ul><!--end tabs-->
        <div class="tabs-content" data-tabs-content="tabs_lead_details">
            <div class="tabs-panel is-active" id="panel_lead_details">
                @include('dashboard.common._partials.lead_customer_details_panel')
            </div><!--end panel_lead_details-->
            <div class="tabs-panel" id="panel_lead_time_line">


                <div class="row">
                    <div class="large-12 columns">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_dashboard_lead_time_line', 'class' => 'float-center', 'style' => 'display:none')) }}
                        <div id="response_dashboard_lead_time_line">

                        </div><!--end response_dashboard_lead_time_line-->
                        <hr>
                        <p><em>Lead Captured On: {{ $lead->created_at }}</em></p>
                        <script>


                            /*==================== PAGINATION =========================*/

                            $(document).on('click', '#pagination_lead_time_line a', function (e) {
                                e.preventDefault();
                                var page = $(this).attr('href').split('page=')[1];
                                //location.hash = page;
                                ajaxLoadDashboardLeadTimeLine(page);
                            });

                            function ajaxLoadDashboardLeadTimeLine(page) {
                                $('#loader_dashboard_lead_time_line').show();
                                $('#response_dashboard_lead_time_line').hide();
                                $('#response_dashboard_lead_time_line').html('');
                                $("[name='reveal_edit_activity_lead']").remove();

                                var customer_id = '{{ is_object($lead->customer)? $lead->customer->id: '' }}';

                                $.ajax({
                                    url: '/leads/ajax/load-dashboard-lead-time-line?' +
                                    '&customer_id=' + customer_id + '&page=' + page

                                }).done(function (data) {
                                    $('#response_dashboard_lead_time_line').html(data);
                                    $('#loader_dashboard_lead_time_line').hide();
                                    $('#response_dashboard_lead_time_line').show();
                                    $('.datepicker').datetimepicker({
                                        timepicker: false,
                                        format: 'Y-m-d',
                                        lang: 'en',
                                        scrollInput: false
                                    });
                                    $('.timepicker').datetimepicker({
                                        datepicker: false,
                                        format: 'g:i A',
                                        formatTime: 'g:i A',
                                        lang: 'en',
                                        scrollInput: false
                                    });
                                    $(document).foundation();
                                });
                            }
                            ajaxLoadDashboardLeadTimeLine(1);
                        </script>
                    </div>
                </div>


            </div><!--end panel_lead_timeline-->
            <div class="tabs-panel" id="panel_lead_lead_notes">

                <div class="large-12 columns">
                    @include('dashboard.common._partials.add_new_lead_note_form')
                </div>

                <div class="row">
                    <div class="large-12 columns">
                        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_dashboard_lead_notes_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                        <div id="response_dashboard_lead_notes_list">

                        </div><!--end response_dashboard_leads_list-->
                        <script>
                            /*==================== PAGINATION =========================*/

                            $(document).on('click', '#pagination_dashboard_lead_notes_list a', function (e) {
                                e.preventDefault();
                                var page = $(this).attr('href').split('page=')[1];
                                //location.hash = page;
                                ajaxLoadDashboardLeadNotesList(page);
                            });

                            function ajaxLoadDashboardLeadNotesList(page) {
                                $('#loader_dashboard_lead_notes_list').show();
                                $('#response_dashboard_lead_notes_list').hide();

                                $.ajax({
                                    url: '/leads/ajax/load-dashboard-lead-notes-list?' +
                                    'page=' + page +
                                    '&lead_id={{ $lead->id }}'

                                }).done(function (data) {
                                    $('#response_dashboard_lead_notes_list').html(data);
                                    $('#loader_dashboard_lead_notes_list').hide();
                                    $('#response_dashboard_lead_notes_list').show();
                                    $(document).foundation();
                                });
                            }

                        </script>

                    </div>

                </div>

            </div><!--end panel_lead_lead_notes-->
            @if(is_object($lead->customer))
                <div class="tabs-panel" id="panel_lead_contacts">
                    @include('contacts.index', array('customer' => $lead->customer))
                </div><!--end panel_lead_contacts-->
            @endif
            @if(in_array($lead->lead_source, array('web360_enquiries', 'event360_calls', 'novocall_leads')))
                <div class="tabs-panel" id="panel_lead_meta_data_{{ $lead->id }}">
                    @include('leads._partials.lead_meta_data')
                </div>
            @endif
        </div><!--end tabs-content-->
    </div>
</div>

@include('dashboard.common._partials.lead_meta_details_dropdown_loader')