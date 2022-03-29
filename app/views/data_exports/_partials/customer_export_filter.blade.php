<div class="row expanded" id="customer_export_filter" style="display: none;">
    <div class="large-4 columns">
        {{ Form::label('customer_export_filter_export_parameter', 'Export Parameter', array('class'=>'control-label')) }}
        <div class="controls">{{ Form::select('customer_export_filter_export_parameter', DataExportRequest::$customer_export_parameters, null, array('id' => 'customer_export_filter_export_parameter','onchange' => 'clearCustomerExportFilters();showDataExportCustomerFilters();')) }}</div>
        {{ $errors->first('customer_export_filter_export_parameter', '<p class="callout alert radius">:message</p>') }}
    </div>

    <div class="large-4 columns">

        <div id="customer_export_filter_account_owner_div" style="display: none;">
            {{ Form::label('customer_export_filter_account_owner', 'Account Owner', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('customer_export_filter_account_owner[]', array(), null, array('id' => 'customer_export_filter_account_owner','multiple' => 'multiple')) }}</div>
            {{ $errors->first('customer_export_filter_account_owner', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="customer_export_filter_account_owner_name" name="customer_export_filter_account_owner_name">
            <script>
                $("#customer_export_filter_account_owner").on('change',function() {
                    var option_all = $("#customer_export_filter_account_owner option:selected").map(function () {
                        return $(this).text();
                    }).get().join(',');
                    console.log(option_all);

                    $("#customer_export_filter_account_owner_name").val(option_all);
                });
            </script>
        </div>

        <div id="customer_export_filter_industry_div" style="display: none;">
            {{ Form::label('customer_export_filter_industry', 'Industry', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('customer_export_filter_industry', array(), null, array('id' => 'customer_export_filter_industry')) }}</div>
            {{ $errors->first('customer_export_filter_industry', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="customer_export_filter_industry_name" name="customer_export_filter_industry_name">
            <script>
                $("#customer_export_filter_industry").on('change',function() {
                    $("#customer_export_filter_industry_name").val($(this).find("option:selected").text());
                });
            </script>
        </div>

        <div id="customer_export_filter_country_div" style="display: none;">
            {{ Form::label('customer_export_filter_country', 'Country', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('customer_export_filter_country', array(), null, array('id' => 'customer_export_filter_country')) }}</div>
            {{ $errors->first('customer_export_filter_country', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="customer_export_filter_country_name" name="customer_export_filter_country_name">
            <script>
                $("#customer_export_filter_country").on('change',function() {
                    $("#customer_export_filter_country_name").val($(this).find("option:selected").text());
                });
            </script>
        </div>

        <div id="customer_export_filter_customer_tags_div" style="display: none;">
            {{ Form::label('customer_export_filter_customer_tags', 'Customer Tags', array('class'=>'control-label')) }}
            <div class="controls">{{ Form::select('customer_export_filter_customer_tags[]', array(), null, array('id' => 'customer_export_filter_customer_tags','multiple' => 'multiple')) }}</div>
            {{ $errors->first('customer_export_filter_customer_tags', '<p class="callout alert radius">:message</p>') }}
            <input type="hidden" id="customer_export_filter_customer_tags_name" name="customer_export_filter_customer_tags_name">
            <script>
                $("#customer_export_filter_customer_tags").on('change',function() {
                    var option_all = $("#customer_export_filter_customer_tags option:selected").map(function () {
                        return $(this).text();
                    }).get().join(',');
                    console.log(option_all);

                    $("#customer_export_filter_customer_tags_name").val(option_all);
                });
            </script>
        </div>

    </div>

    <div class="large-4 columns">&nbsp;</div>
</div>

<script>

    function loadDataExportCustomerFilters(){
        getCustomerExportAssignedToEmployeesList();
        getCustomerExportIndustriesList();
        getCustomerExportCountriesList();
        getCustomerExportCustomerTagsList();
    }

    function showDataExportCustomerFilters(){

        var customer_export_filter_export_parameter = $("#customer_export_filter_export_parameter").val();

        $("#customer_export_filter_account_owner_div").hide();
        $("#customer_export_filter_industry_div").hide();
        $("#customer_export_filter_country_div").hide();
        $("#customer_export_filter_customer_tags_div").hide();


        if( customer_export_filter_export_parameter == 'Created At'){

        }else if(customer_export_filter_export_parameter == 'Account Owner'){

            $("#customer_export_filter_account_owner_div").show();

        }else if(customer_export_filter_export_parameter == 'Industry'){

            $("#customer_export_filter_industry_div").show();

        }else if(customer_export_filter_export_parameter == 'Country'){

            $("#customer_export_filter_country_div").show();

        }else if(customer_export_filter_export_parameter == 'Customer tags'){

            $("#customer_export_filter_customer_tags_div").show();

        }else if(customer_export_filter_export_parameter == 'All')
        {
            //display no filters
        }

    }

    function getCustomerExportAssignedToEmployeesList() {
        $.ajax({
            url: '/employees/ajax/get-employees-list'
        }).done(function (data) {
            $('#customer_export_filter_account_owner').empty();
            data = $.parseJSON(data);
            $('#customer_export_filter_account_owner').append("<option value=''>Select</option>");

            for (var i in data) {
                $('#customer_export_filter_account_owner').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
            }

            $('#customer_export_filter_account_owner').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function getCustomerExportIndustriesList() {
        $.ajax({
            url: '/industries/ajax/industries'
        }).done(function (data) {
            $('#customer_export_filter_industry').empty();
            data = $.parseJSON(data);
            $('#customer_export_filter_industry').append("<option value=''>Select</option>");

            for (var i in data) {
                $('#customer_export_filter_industry').append("<option value='" + data[i].id + "'>" + data[i].industry + "</option>");
            }

            $('#customer_export_filter_industry').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }


    function getCustomerExportCountriesList() {
        $.ajax({
            url: '/countries/ajax/countries'
        }).done(function (data) {
            $('#customer_export_filter_country').empty();
            data = $.parseJSON(data);
            $('#customer_export_filter_country').append("<option value=''>Select</option>");

            for (var i in data) {
                $('#customer_export_filter_country').append("<option value='" + data[i].id + "'>" + data[i].country + "</option>");
            }

            $('#customer_export_filter_country').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function getCustomerExportCustomerTagsList() {
        $.ajax({
            url: '/customers/ajax/get-customer-tags'
        }).done(function (data) {
            $('#customer_export_filter_customer_tags').empty();
            data = $.parseJSON(data);
            $('#customer_export_filter_customer_tags').append("<option value=''>Select</option>");

            for (var i in data) {
                $('#customer_export_filter_customer_tags').append("<option value='" + data[i].id + "'>" + data[i].tag + "</option>");
            }

            $('#customer_export_filter_customer_tags').selectize({
                create: false,
                sortField: 'text'
            });
        });
    }

    function clearCustomerExportFilters(){

        var customer_export_filter_account_owner = $('#customer_export_filter_account_owner').selectize();
        var control_customer_export_filter_account_owner = customer_export_filter_account_owner[0].selectize;
        control_customer_export_filter_account_owner.clear();

        var customer_export_filter_industry = $('#customer_export_filter_industry').selectize();
        var control_customer_export_filter_industry = customer_export_filter_industry[0].selectize;
        control_customer_export_filter_industry.clear();

        var customer_export_filter_country = $('#customer_export_filter_country').selectize();
        var control_customer_export_filter_country = customer_export_filter_country[0].selectize;
        control_customer_export_filter_country.clear();

        var customer_export_filter_customer_tags = $('#customer_export_filter_customer_tags').selectize();
        var control_customer_export_filter_customer_tags = customer_export_filter_customer_tags[0].selectize;
        control_customer_export_filter_customer_tags.clear();

        $("#customer_export_filter_account_owner_name").val('');
        $("#customer_export_filter_industry_name").val('');
        $("#customer_export_filter_country_name").val('');
        $("#customer_export_filter_customer_tags_name").val('');
    }


</script>