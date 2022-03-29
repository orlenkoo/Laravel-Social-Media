<div class="off-canvas-wrapper">
    <div class="off-canvas-absolute position-left" id="leadFilterOffCanvas" data-off-canvas>
        <div class="panel">
            <div class="panel-content">
                <div class="row">
                    <div class="large-12 columns">
                        <label for="leads_list_filter_lead_source">
                            Source:
                            <select name="leads_list_filter_lead_source" id="leads_list_filter_lead_source">
                                <option value="">All</option>
                                @foreach(Lead::$lead_sources as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 columns">
                        <label for="leads_list_filter_lead_rating">
                            Rating:
                            <select name="leads_list_filter_lead_rating" id="leads_list_filter_lead_rating">
                                <option value="">All</option>
                                @foreach(Lead::$lead_ratings as $lead_rating)
                                    <option value="{{ $lead_rating }}">{{ $lead_rating }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 columns">
                        <label for="leads_list_filter_assigned_to">
                            Assigned To:
                            <select name="leads_list_filter_assigned_to" id="leads_list_filter_assigned_to" multiple>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 columns">
                        <label for="leads_list_filter_campaign">
                            Campaign:
                            <select name="leads_list_filter_campaign" id="leads_list_filter_campaign" multiple>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-6 columns text-right">
                        <input type="button" value="Clear Filters" class="alert button tiny" style="width: 100%;" onclick="clearDashboardLeadsFilter()">
                    </div>
                    <div class="large-6 columns text-right">
                        <input type="button" class="button tiny success" value="Search" style="margin-top: 0px; width: 100%;" onclick="ajaxLoadDashboardLeadsList()">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="off-canvas-content" style="min-height: 300px;" data-off-canvas-content>
        <div class="leads-list-filters">
            <div class="row">
                <div class="large-2 columns">
                    <input type="button" class="tiny button" value="Filter Leads" data-toggle="leadFilterOffCanvas">
                </div>
                <div class="large-10 columns">
                    <input type="text" id="leads_list_search_text" class="search-box" value="" placeholder="Search By Customer Name [Press Enter to Search]" onchange="ajaxLoadDashboardLeadsList(1)">
                </div>
            </div>
        </div>

        <hr>

        {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_dashboard_leads_list', 'class' => 'float-center', 'style' => 'display:none')) }}
        <div id="response_dashboard_leads_list">

        </div><!--end response_dashboard_leads_list-->
    </div>
</div>



<script>
    /*==================== PAGINATION =========================*/

    $(document).on('click', '#pagination_dashboard_leads_list a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        //location.hash = page;
        ajaxLoadDashboardLeadsList(page);
    });

    function clearDashboardLeadsFilter() {
        $('#leads_list_filter_lead_source').val('');
        $('#leads_list_filter_lead_rating').val('');
        $('#leads_list_search_text').val('');
        var leads_list_filter_assigned_to = $('#leads_list_filter_assigned_to').selectize();
        var control_leads_list_filter_assigned_to = leads_list_filter_assigned_to[0].selectize;
        control_leads_list_filter_assigned_to.clear();
        var leads_list_filter_campaign = $('#leads_list_filter_campaign').selectize();
        var control_leads_list_filter_campaign = leads_list_filter_campaign[0].selectize;
        control_leads_list_filter_campaign.clear();
        ajaxLoadDashboardLeadsList(1);
    }

    function getAssignedToEmployeesList() {
        $.ajax({
            url: '/employees/ajax/get-employees-list'
        }).done(function (data) {
            $('#leads_list_filter_assigned_to').empty();
            $('#my_leads_widget_assigned_to').empty();
            $('#my_sales_widget_assigned_to').empty();
            data = $.parseJSON(data);

            console.log('data -- ' + data);

            $('#leads_list_filter_assigned_to').append("<option value=''>Assigned to</option>");
            $('#my_leads_widget_assigned_to').append("<option value=''>Assigned to</option>");
            $('#my_sales_widget_assigned_to').append("<option value=''>Assigned to</option>");

            for (var i in data) {
                $('#leads_list_filter_assigned_to').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");

                if(data[i].id == '{{ Session::get('user-id') }}') {
                    $('#my_leads_widget_assigned_to').append("<option value='" + data[i].id + "' selected>" + data[i].full_name + "</option>");
                } else {
                    $('#my_leads_widget_assigned_to').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                }

                if(data[i].id == '{{ Session::get('user-id') }}') {
                    $('#my_sales_widget_assigned_to').append("<option value='" + data[i].id + "' selected>" + data[i].full_name + "</option>");
                } else {
                    $('#my_sales_widget_assigned_to').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                }

            }

            $('#leads_list_filter_assigned_to').selectize({
                create: false,
                sortField: 'text'
            });

            $('#my_leads_widget_assigned_to').selectize({
                create: false,
                sortField: 'text'
            });

            $('#my_sales_widget_assigned_to').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function getCampaignsList() {
        $.ajax({
            url: '/campaigns/ajax/get-campaigns-list'
        }).done(function (data) {
            $('#leads_list_filter_campaign').empty();
            data = $.parseJSON(data);
            $('#leads_list_filter_campaign').append("<option value=''>Campaign</option>");

            for (var i in data) {
                $('#leads_list_filter_campaign').append("<option value='" + data[i].auto_tagged_campaign + "'>" + data[i].auto_tagged_campaign + "</option>");
            }

            $('#leads_list_filter_campaign').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    getCampaignsList();
    getAssignedToEmployeesList();
</script>