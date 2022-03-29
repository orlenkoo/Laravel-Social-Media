<table>
    <tr>
        <th>Export Type</th>
        <th>Generated Date</th>
        <th>Status</th>
        <th>Export Parameters</th>
        <th>Requested By</th>
        <th>No of Records</th>
        <th>Download</th>
    </tr>
    @foreach($data_export_requests as $data_export_request)
        <tr>
            <td>{{DataExportRequest::$type_of_data[$data_export_request->export_type]}}</td>
            <td>{{$data_export_request->generated_date}}</td>
            <td>{{DataExportRequest::$status[$data_export_request->status]}}</td>
            <td>
                <button class="button tiny" type="button" data-open="reveal_data_export_request_{{ $data_export_request->id }}">View</button>
                <div class="panel reveal" id="reveal_data_export_request_{{ $data_export_request->id }}" name="reveal_data_export_request" data-reveal>
                    <div class="panel-heading">
                        <div class="row">
                            <div class="large-12 columns">
                                <h4>Export Parameters</h4>
                            </div>
                        </div>
                    </div>
                    <div class="panel-content">
                        <div class="row">
                            <table>
                                @foreach(json_decode($data_export_request->export_parameters,true) as $parameter_key => $parameter_value)
                                    <tr>
                                        <th>{{ ucwords(str_replace("_"," ",$parameter_key)) }}</th>
                                        <td>{{$parameter_value}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </td>
            <td>{{$data_export_request->requestedBy->getEmployeeFullName()}}</td>
            <td>{{$data_export_request->no_of_records}}</td>
            <td>
                <a class="button tiny" target="_blank" href="{{$data_export_request->download_link}}">Download</a>
            </td>
        </tr>
    @endforeach
</table>