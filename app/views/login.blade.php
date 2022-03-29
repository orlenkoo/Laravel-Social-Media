@extends('layouts.clear')

@section('content')

    <div class="row" style="margin-top: 40px;">
        <div class="large-4 columns large-centered text-center">
            <h1>
                {{ HTML::image('assets/img/web360_logo2x.png', 'Web360', array('class' => 'logo-login', 'width' => '100')) }}
            </h1>
            <div class="panel">
                <div class="panel-heading">
                    <h5>Login</h5>
                </div>
                <div class="panel-content">
                    {{ Form::open(array('route' => 'login.submit')) }}
                    {{ Form::hidden('referring_url', $referring_url) }}

                    <div class="row">
                        <div class="large-12 columns">
                            {{ Form::text('email', '', array('data-validation'=>'required', 'placeholder' => 'Email')) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            {{ Form::password('password', array('data-validation'=>'required', 'placeholder' => 'Password')) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            {{ Form::submit('Login', array("class" => "button tiny")) }}
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="large-12 columns">
                            <a href="#" data-open="popup_reset_password" class="tiny login-forgot-password">Forgot password?</a>
                        </div>
                    </div> --}}

                    
                </div>
            </div>

        </div>
    </div><!--end login panel-->


@stop