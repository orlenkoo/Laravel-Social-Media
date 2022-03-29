{{ Form::open(array('route' => 'data_export.save.data_export_request', 'data-abide' => true , 'ajax' => 'true', 'id' => 'data_export_form','autocomplete' => 'off')) }}

<div class="row expanded">
    <div class="large-4 columns ">
        {{ Form::label('data_type', 'Data Type', array('class'=>'control-label')) }}
        <div class="controls">{{  Form::select('data_type',array('' => 'Select') + DataExportRequest::$type_of_data, null, array('id' => 'data_type', 'data-validation'=>'required','onchange' => 'showFilters(this)','required' => 'required' )) }}</div>
    </div>
    <div class="large-8 columns ">&nbsp;</div>
</div>


@include('data_exports._partials.customer_export_filter')


@include('data_exports._partials.lead_export_filter')


<br>

<div class="row expanded">
    <div class="large-12 columns">
        {{ Form::submit('Generate Export Request', array("class" => "button tiny")) }}
    </div>
</div>

{{ Form::close() }}


<script>


    function showFilters(obj) {
        // alert(obj.value);
        var toshow = obj.value;

        //Set all filters to none
        document.getElementById('customer_export_filter').style.display = 'none';
        document.getElementById('lead_export_filter').style.display = 'none';

        var toShowFilter = toshow + '_export_filter';

        document.getElementById('' + toShowFilter + '').style.display = 'block';

        //to clear the filter of the previous data
        clearFilters();

        if(toshow == 'customer'){
            loadDataExportCustomerFilters();
        }else if(toshow == 'lead'){
            loadDataExportLeadFilters();
        }

    }

    function clearFilters(){
        var data_type = $('#data_type').val();
        document.getElementById("data_export_form").reset();
        $('#data_type').val(data_type);
    }

    $("#data_export_form").submit(function (e) {

        e.preventDefault();
        $("input[type=submit]").attr("disabled", "disabled");

        var dashboard_filter_date_range = $('#dashboard_filter_date_range').find(":selected").text();
        var dashboard_filter_from_date = $('#dashboard_filter_from_date').val();
        var dashboard_filter_to_date = $('#dashboard_filter_to_date').val();

        var form = this;
        var form_data = new FormData(this);
        form_data.append('dashboard_filter_date_range',dashboard_filter_date_range);
        form_data.append('dashboard_filter_from_date', dashboard_filter_from_date);
        form_data.append('dashboard_filter_to_date', dashboard_filter_to_date);
        var form_url = $(form).attr("action");
        var form_method = $(form).attr("method").toUpperCase();

        $.ajax({
            url: form_url,
            type: form_method,
            data: form_data,
            contentType: false,
            processData: false,

            success:function(response){

                $("input[type=submit]").removeAttr("disabled");
                ajaxLoadDataExportList(1);
                $.notify("Data Exported Successfully.","success");

            },
            error: function (jqXHR, exception) {

                $("input[type=submit]").removeAttr("disabled");
                $.notify("Error Occurred when export data.");
            }
        });

    });


</script>