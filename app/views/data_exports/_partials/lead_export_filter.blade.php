<div class="row expanded" id="lead_export_filter" style="display: none;">
    <div class="large-4 columns">
        {{ Form::label('lead_export_filter_export_parameter', 'Export Parameter', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::select('lead_export_filter_export_parameter', DataExportRequest::$lead_export_parameters, null, array('id' => 'lead_export_filter_export_parameter','onchange' => 'clearLeadExportFilters();showDataExportLeadFilters();')) }}</div>
        {{ $errors->first('lead_export_filter_export_parameter', '<p class="callout alert radius">:message</p>') }}
    </div>
    <div class="large-4 columns">
        <div id="lead_export_filter_customer_div" style="">
            {{ Form::label('lead_export_filter_customer', 'Customer', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('lead_export_filter_customer', array(), null, array('id' => 'lead_export_filter_customer')) }}</div>
            {{ $errors->first('lead_export_filter_customer', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="lead_export_filter_customer_name" name="lead_export_filter_customer_name">
            <script>
                $("#lead_export_filter_customer").on('change',function() {
                    $("#lead_export_filter_customer_name").val($(this).find("option:selected").text());
                });
            </script>
        </div>
        <div id="lead_export_filter_campaign_div" style="display: none;">
            {{ Form::label('lead_export_filter_campaign', 'Campaigns', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('lead_export_filter_campaign', array(), null, array('id' => 'lead_export_filter_campaign')) }}</div>
            {{ $errors->first('lead_export_filter_campaign', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="lead_export_filter_campaign_name" name="lead_export_filter_campaign_name">
            <script>
                $("#lead_export_filter_campaign").on('change',function() {
                    $("#lead_export_filter_campaign_name").val($(this).find("option:selected").text());
                });
            </script>
        </div>
        <div id="lead_export_filter_assigned_to_div" style="display: none;">
            {{ Form::label('lead_export_filter_assigned_to', 'Assigned To', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('lead_export_filter_assigned_to', array(), null, array('id' => 'lead_export_filter_assigned_to')) }}</div>
            {{ $errors->first('lead_export_filter_assigned_to', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="lead_export_filter_assigned_to_name" name="lead_export_filter_assigned_to_name">
            <script>
                $("#lead_export_filter_assigned_to").on('change',function() {
                    $("#lead_export_filter_assigned_to_name").val($(this).find("option:selected").text());
                });
            </script>
        </div>
        <div id="lead_export_filter_status_updated_by_div" style="display: none;">
            {{ Form::label('lead_export_filter_status_updated_by', 'Status Updated By', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('lead_export_filter_status_updated_by', array(), null, array('id' => 'lead_export_filter_status_updated_by')) }}</div>
            {{ $errors->first('lead_export_filter_status_updated_by', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="lead_export_filter_status_updated_by_name" name="lead_export_filter_status_updated_by_name">
            <script>
                $("#lead_export_filter_status_updated_by").on('change',function() {
                    $("#lead_export_filter_status_updated_by_name").val($(this).find("option:selected").text());
                });
            </script>
        </div>
        <div id="lead_export_filter_lead_rating_last_updated_by_div" style="display: none;">
            {{ Form::label('lead_export_filter_lead_rating_last_updated_by', 'Lead Rating Last Updated By', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('lead_export_filter_lead_rating_last_updated_by', array(), null, array('id' => 'lead_export_filter_lead_rating_last_updated_by')) }}</div>
            {{ $errors->first('lead_export_filter_lead_rating_last_updated_by', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="lead_export_filter_lead_rating_last_updated_by_name" name="lead_export_filter_lead_rating_last_updated_by_name">
            <script>
                $("#lead_export_filter_lead_rating_last_updated_by").on('change',function() {
                    $("#lead_export_filter_lead_rating_last_updated_by_name").val($(this).find("option:selected").text());
                });
            </script>
        </div>
        <div id="lead_export_filter_lead_rating_div" style="display: none;">
            {{ Form::label('lead_export_filter_lead_rating', 'Lead Rating', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('lead_export_filter_lead_rating', ['' => 'Select'] + Lead::$lead_ratings, null, array('id' => 'lead_export_filter_lead_rating')) }}</div>
            {{ $errors->first('lead_export_filter_lead_rating', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="lead_export_filter_lead_rating_name" name="lead_export_filter_lead_rating_name">
            <script>
                $("#lead_export_filter_lead_rating").on('change',function() {
                    $("#lead_export_filter_lead_rating_name").val($(this).find("option:selected").text());
                });
            </script>
        </div>
    </div>
    <div class="large-4 columns">&nbsp;</div>
</div>


<script>

    function loadDataExportLeadFilters(){
        getLeadExportCustomerList();
        getLeadExportCampaignList();
        getLeadExportAssignedToEmployeesList();
        getLeadExportStatusUpdatedByEmployeesList();
        getLeadExportLeadRatingLastUpdatedByEmployeesList();

    }

    function showDataExportLeadFilters() {

        var lead_export_filter_export_parameter = $("#lead_export_filter_export_parameter").val();

        $("#lead_export_filter_customer_div").hide();
        $("#lead_export_filter_campaign_div").hide();
        $("#lead_export_filter_assigned_to_div").hide();
        $("#lead_export_filter_status_updated_by_div").hide();
        $("#lead_export_filter_lead_rating_last_updated_by_div").hide();
        $("#lead_export_filter_lead_rating_div").hide();

        if (lead_export_filter_export_parameter == 'Customer') {

            $("#lead_export_filter_customer_div").show();

        } else if (lead_export_filter_export_parameter == 'Campaign') {

            $("#lead_export_filter_campaign_div").show();

        } else if (lead_export_filter_export_parameter == 'Assigned To') {

            $("#lead_export_filter_assigned_to_div").show();

        } else if (lead_export_filter_export_parameter == 'Status Updated By') {

            $("#lead_export_filter_status_updated_by_div").show();

        } else if (lead_export_filter_export_parameter == 'Lead Rating Last Updated By') {

            $("#lead_export_filter_lead_rating_last_updated_by_div").show();

        } else if (lead_export_filter_export_parameter == 'Lead Rating') {

            $("#lead_export_filter_lead_rating_div").show();

        }
    }

    function getLeadExportCustomerList() {
        $.ajax({
            url: '/customers/ajax/load-customer-list'
        }).done(function (data) {
            $('#lead_export_filter_customer').empty();
            data = $.parseJSON(data);
            $('#lead_export_filter_customer').append("<option value=''>Select</option>");

            for (var i in data) {
                $('#lead_export_filter_customer').append("<option value='" + data[i].id + "'>" + data[i].customer_name + "</option>");
            }

            $('#lead_export_filter_customer').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function getLeadExportCampaignList() {
        $.ajax({
            url: '/campaigns/ajax/get-campaigns-list'
        }).done(function (data) {
            $('#lead_export_filter_campaign').empty();
            data = $.parseJSON(data);
            $('#lead_export_filter_campaign').append("<option value=''>Select</option>");

            for (var i in data) {
                $('#lead_export_filter_campaign').append("<option value='" + data[i].id + "'>" + data[i].campaign_name + "</option>");
            }

            $('#lead_export_filter_campaign').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }


    function getLeadExportAssignedToEmployeesList() {
        $.ajax({
            url: '/employees/ajax/get-employees-list'
        }).done(function (data) {
            $('#lead_export_filter_assigned_to').empty();
            data = $.parseJSON(data);
            $('#lead_export_filter_assigned_to').append("<option value=''>Select</option>");

            for (var i in data) {
                $('#lead_export_filter_assigned_to').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
            }

            $('#lead_export_filter_assigned_to').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function getLeadExportStatusUpdatedByEmployeesList() {
        $.ajax({
            url: '/employees/ajax/get-employees-list'
        }).done(function (data) {
            $('#lead_export_filter_status_updated_by').empty();
            data = $.parseJSON(data);
            $('#lead_export_filter_status_updated_by').append("<option value=''>Select</option>");

            for (var i in data) {
                $('#lead_export_filter_status_updated_by').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
            }

            $('#lead_export_filter_status_updated_by').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function getLeadExportLeadRatingLastUpdatedByEmployeesList() {
        $.ajax({
            url: '/employees/ajax/get-employees-list'
        }).done(function (data) {
            $('#lead_export_filter_lead_rating_last_updated_by').empty();
            data = $.parseJSON(data);
            $('#lead_export_filter_lead_rating_last_updated_by').append("<option value=''>Select</option>");

            for (var i in data) {
                $('#lead_export_filter_lead_rating_last_updated_by').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
            }

            $('#lead_export_filter_lead_rating_last_updated_by').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }


    $( document ).ready(function() {
        $('#lead_export_filter_lead_rating').selectize({
            create: false,
            sortField: 'text'
        });
    });
    
    function clearLeadExportFilters(){

        var lead_export_filter_customer = $('#lead_export_filter_customer').selectize();
        var control_lead_export_filter_customer = lead_export_filter_customer[0].selectize;
        control_lead_export_filter_customer.clear();
        
        var lead_export_filter_campaign = $('#lead_export_filter_campaign').selectize();
        var control_lead_export_filter_campaign = lead_export_filter_campaign[0].selectize;
        control_lead_export_filter_campaign.clear();
        
        var lead_export_filter_assigned_to = $('#lead_export_filter_assigned_to').selectize();
        var control_lead_export_filter_assigned_to = lead_export_filter_assigned_to[0].selectize;
        control_lead_export_filter_assigned_to.clear();
        
        var lead_export_filter_status_updated_by = $('#lead_export_filter_status_updated_by').selectize();
        var control_lead_export_filter_status_updated_by = lead_export_filter_status_updated_by[0].selectize;
        control_lead_export_filter_status_updated_by.clear();
        
        var lead_export_filter_lead_rating_last_updated_by = $('#lead_export_filter_lead_rating_last_updated_by').selectize();
        var control_lead_export_filter_lead_rating_last_updated_by = lead_export_filter_lead_rating_last_updated_by[0].selectize;
        control_lead_export_filter_lead_rating_last_updated_by.clear();
        
        var lead_export_filter_lead_rating = $('#lead_export_filter_lead_rating').selectize();
        var control_lead_export_filter_lead_rating = lead_export_filter_lead_rating[0].selectize;
        control_lead_export_filter_lead_rating.clear();

        $("#lead_export_filter_customer_name").val('');
        $("#lead_export_filter_campaign_name").val('');
        $("#lead_export_filter_assigned_to_name").val('');
        $("#lead_export_filter_status_updated_by_name").val('');
        $("#lead_export_filter_lead_rating_last_updated_by_name").val('');
        $("#lead_export_filter_lead_rating_name").val('');
    }


</script>