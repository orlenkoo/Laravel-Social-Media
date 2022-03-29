@include('layouts._partials.header')

<div class="off-canvas-wrapper">
    <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
        <!-- Your menu or Off-canvas content goes here -->
        {{--@include('layouts._partials.v1_menu', array('menu_style' => 'vertical'))--}}
    </div>
    <div class="off-canvas-content" data-off-canvas-content>
        <div class="row expanded topbar">
            {{--<div class="large-1 columns dash-menu">--}}
                {{--<a class="" data-toggle="offCanvas" id="wh_tour_step2"><img src="{{ asset('assets/img/hamburger-menu.png') }}" alt="" width="35"></a>--}}
            {{--</div>--}}
            <div class="large-8 columns logo">
                <a href="{{ route('home') }}"><img src="{{ asset('assets/img/web360_logo2x.png') }}" alt="" width="80" id="wh_tour_step1"></a>
            </div>

            <div class="large-4 columns" id="wh_tour_step3">
                @include('layouts._partials.top_right_menu')
            </div>
        </div><!--end topbar-->

        <div class="row expanded menu-bar" id="wh_tour_step5">
            <div class="large-8 columns">
               @include('layouts._partials.main_menu')
            </div>
            <div class="large-4 columns" id="wh_tour_step4">
                @include('layouts._partials.dashboard_date_filter')
            </div>


        </div><!--end menu-bar-->

        <div class="content">
            @yield('content')
        </div><!--end content-->

    </div><!--end off-canvas-content-->
</div><!--end off-canvas-wrapper-->



@include('layouts._partials.footer')