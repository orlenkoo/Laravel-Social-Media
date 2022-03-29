@include('layouts._partials.header')

<div class="off-canvas-wrapper">
    <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
        <!-- Your menu or Off-canvas content goes here -->
        @include('layouts._partials.v1_menu', array('menu_style' => 'vertical'))
    </div>
    <div class="off-canvas-content" data-off-canvas-content>
        <div class="row expanded topbar">
            <div class="large-1 columns dash-menu">
                <a class="" data-toggle="offCanvas"><img src="{{ asset('assets/img/hamburger-menu.png') }}" alt="" width="35"></a>
            </div>
            <div class="large-8 columns logo">
                <a href="{{ route('home') }}"><img src="{{ asset('assets/img/web360_logo2x.png') }}" alt="" width="80"></a>
            </div>

            <div class="large-3 columns">
                @include('layouts._partials.top_right_menu')
            </div>
        </div><!--end topbar-->

        <div class="row expanded menu-bar">
            <div class="large-12 columns">
                @include('layouts._partials.v1_menu', array('menu_style' => 'dropdown'))
            </div>
        </div><!--end menu-bar-->

        <div class="content">
            @include('layouts._partials.layout_selection_option')

            @yield('content')
        </div><!--end content-->

    </div><!--end off-canvas-content-->
</div><!--end off-canvas-wrapper-->



@include('layouts._partials.footer')