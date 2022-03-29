@extends('layouts.clear')

@section('content')
    <div class="row" style="margin-top: 40px;">
        <div class="large-4 columns large-centered text-center">
            <h1>
                {{ HTML::image('assets/img/web360_logo2x.png', 'Web360', array('class' => 'logo-login', 'width' => '100')) }}
            </h1>
            <div class="panel">
                <div class="panel-heading">
                    <h5>Oops, something seems off..</h5>
                </div>
                <div class="panel-content">

                    <p>Please click below button to go to dashboard and try again.</p>

                    <p class="text-center"><a href="{{ url('/') }}" class="button tiny success">Dashboard</a></p>
                </div>
            </div>
        </div>
    </div><!--end login panel-->
@stop