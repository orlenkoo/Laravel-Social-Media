@extends('layouts.default')

@section('content')

    <div class="row page-title-bar">
        <div class="large-12 columns">
            <h1>Leads Summary</h1>
        </div>
    </div>

    <div class="row" id="leads_sumamry_screen" style="display: block;">
        <div class="large-12 columns">
            <ul class="tabs" data-tab>
                <li class="tab-title active"><a href="#panel-event360-website-form">Event360 Website Form</a></li>
                <li class="tab-title"><a href="#panel-event360-calls">Event360 Calls</a></li>
                <li class="tab-title"><a href="#panel-event360-messages">Event360 Message</a></li>
                <li class="tab-title"><a href="#panel-vendor-website-form">Vendor Website Form</a></li>
                <li class="tab-title"><a href="#panel-vendor-website-email">Vendor Website Email</a></li>
            </ul>

            <div class="tabs-content">
                <div class="content active" id="panel-event360-website-form">
                    <h2>Event360 Website Form</h2>
                    @include('webtics_product.web360.leads_detail.event360_website_form.index')

                </div><!--end overview-->

                <div class="content" id="panel-event360-calls">
                    <h2>Event360 Calls</h2>

                </div><!--end vendor-profile-->

                <div class="content" id="panel-event360-messages">
                    <h2>Event360 Messages</h2>

                </div><!--end our-services-->

                <div class="content" id="panel-vendor-website-form">
                    <h2>Vendor Website Form</h2>

                </div><!--end testimonials-->

                <div class="content" id="panel-gallery">
                    <h2>Vendor Website Email</h2>


                </div><!--end gallery-->
            </div>

        </div>
    </div>



@stop