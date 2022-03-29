@extends('layouts.v1_layout')



    <?php
    $event360_tabs_hide_for_non_advertisers = false;
    if(Employee::projectChecker("event360")) {
        if(is_object($event360_vendor_profile)){
            if($event360_vendor_profile->advertiser == 1) {
                $event360_tabs_hide_for_non_advertisers = true;
            }
        }
    } else {
        $event360_tabs_hide_for_non_advertisers = true;
    }

    ?>


@section('content')



    <div class="row expanded">
        <div class="large-12 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h1>My Account</h1>
                </div>
                <div class="panel-content">
                    <ul class="tabs" data-tabs id="tabs_vendor_profile">
                        <li class="tabs-title is-active"><a href="#panel-overview">Overview</a></li>
                        @if(Employee::projectChecker("event360"))
                            <li class="tabs-title"><a href="#panel-vendor-profile">Vendor Profile</a></li>

                            @if($event360_tabs_hide_for_non_advertisers)
                            <li class="tabs-title"><a href="#panel-our-services">Our Services</a></li>
                            <li class="tabs-title"><a href="#panel-testimonials">Testimonials</a></li>
                            <li class="tabs-title"><a href="#panel-gallery">Gallery</a></li>
                            @endif
                        @endif
                        @if(Employee::projectChecker("event360"))
                            @if($event360_tabs_hide_for_non_advertisers)
                            <li class="tabs-title"><a href="#panel-ad-banners">Ad Banners</a></li>
                            @endif
                        @endif
                    </ul>

                    <div class="tabs-content" data-tabs-content="tabs_vendor_profile">
                        <div class="tabs-panel is-active" id="panel-overview">
                            @include('v1.my_account._partials.overview_form')
                        </div><!--end overview-->


                        @if(Employee::projectChecker("event360"))
                            <div class="tabs-panel" id="panel-vendor-profile">

                                @if(is_object($event360_vendor_profile))
                                    <div class="row expanded">
                                        <div class="large-6 columns">
                                            @include('v1.my_account._partials.vendor_profile_form_edit')
                                        </div>
                                        <div class="large-1 columns">
                                        </div>
                                        <div class="large-5 columns">
                                            <h4>Profile Images</h4>
                                            @include('v1.my_account._partials.vendor_profile_images_grid')
                                        </div>
                                    </div>
                                @else
                                    <h2>Vendor Profile</h2>
                                    @include('v1.my_account._partials.vendor_profile_form')
                                @endif

                            </div><!--end vendor-profile-->
                        @endif

                        @if(Employee::projectChecker("event360"))
                            <div class="tabs-panel" id="panel-our-services">
                                @if(is_object($event360_vendor_profile))
                                    @include('v1.my_account.services.index')
                                @else
                                    <div class="row expanded">
                                        <div class="large-12 columns">
                                            <div class="panel">
                                                <p>First create a Vendor Profile</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div><!--end our-services-->
                        @endif

                        @if(Employee::projectChecker("event360"))
                            <div class="tabs-panel" id="panel-testimonials">
                                @if(is_object($event360_vendor_profile))
                                    @include('v1.my_account.testimonials.index')
                                @else
                                    <div class="row expanded">
                                        <div class="large-12 columns">
                                            <div class="panel">
                                                <p>First create a Vendor Profile</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div><!--end testimonials-->
                        @endif

                        @if(Employee::projectChecker("event360"))
                            <div class="tabs-panel" id="panel-gallery">
                                @if(is_object($event360_vendor_profile))
                                    @include('v1.my_account.gallery.index')
                                @else
                                    <div class="row expanded">
                                        <div class="large-12 columns">
                                            <div class="panel">
                                                <p>First create a Vendor Profile</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div><!--end gallery-->
                        @endif


                        @if(Employee::projectChecker("event360"))
                            @if($event360_tabs_hide_for_non_advertisers)
                            <div class="tabs-panel" id="panel-ad-banners">
                                @if(is_object($event360_vendor_profile))
                                    @include('v1.my_account.event360_vendor_profile_ad_banners.index')
                                @else
                                    <div class="row expanded">
                                        <div class="large-12 columns">
                                            <div class="panel">
                                                <p>First create a Vendor Profile</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div><!--end ad-banners-->
                            @endif
                        @endif

                    </div>

                </div>
            </div>
        </div>

    </div>



@stop