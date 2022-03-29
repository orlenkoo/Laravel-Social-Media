@extends('layouts.clear')

@section('content')
    <div class="row">
        <div class="large-12 columns content-area text-center login-content">
            <div class="panel large-3 large-centered columns login-panel">
                <h1><a href="#">{{ HTML::image('assets/img/web360_logo2x.png', 'Web360', array('class' => 'logo-login', 'width' => '100')) }}</a></h1>
                <h5>Not Authorized</h5>

                <p>Sorry you dont have permission to use this system. Please contact Webnatics.</p>

                <a href="{{ route('logout') }}" class="tiny success button">Try Again.</a>

            </div>

        </div>

    </div><!--end login panel-->

@stop