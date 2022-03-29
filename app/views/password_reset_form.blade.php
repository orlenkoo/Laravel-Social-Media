@extends('layouts.clear')

@section('content')
    <div class="row">
        <div class="large-12 columns content-area text-center login-content">
            <div class="row">
                <div class="large-4 columns large-centered">
                    <div class="panel login-panel">
                        <h1>
                            <a href="#">{{ HTML::image('assets/img/web360_logo2x.png', 'Web360', array('class' => 'logo', 'width' => '100')) }}</a>
                        </h1>
                        <h5>Password Reset Form</h5>
                        {{ Form::open(array('route' => 'employees.password.reset')) }}
                        {{ Form::hidden('_token', $_token) }}
                        {{ Form::hidden('user_id', $user_id) }}
                        <div class="row">
                            <div class="large-12 columns">
                                {{ Form::label('new_password', 'New Password', array('class'=>'control-label')) }}
                                <div class="controls">{{ Form::password('new_password', array('data-validation'=>'required', 'onchange'=>'validatePassword()','id' => 'new_password')) }}</div>
                                {{ $errors->first('new_password', '<p class="alert-box alert radius">:message</p>') }}
                            </div>
                        </div>
                        <div class="row save_bar">
                            <div class="large-12 columns text-center">
                                {{ Form::submit('Reset', array("class" => "button success tiny")) }}
                            </div>
                        </div>
                        <div class="row loading_animation" style="display: none;">
                            <div class="large-12 columns text-center">
                                {{ HTML::image('assets/img/loading.gif', 'Loading', array('class' => '')) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

        </div>
    </div><!--end login panel-->

@stop