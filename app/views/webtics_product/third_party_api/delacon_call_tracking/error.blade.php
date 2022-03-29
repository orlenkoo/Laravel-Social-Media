@extends('layouts.default')

@section('content')
    <div class="row page-title-bar">
        <div class="large-12 columns">
            <h1>Call Tracking Report</h1>
        </div>
    </div>





    <div class="row">
        <div class="large-12 columns">
            <div class="panel" style="overflow: scroll;">
                <p class="alert-box info radius">{{ $error_msg }}</p>
            </div>
        </div>
    </div>



@stop