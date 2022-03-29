@extends('layouts.default')

@section('content')

    <ul class="tabs" data-tabs id="tabs_marketing_dashboard">
        <li class="tabs-title is-active"><a href="#panel_overview" onclick="ajaxLoadOverviewModulesList()" aria-selected="true">Overview</a></li>
        <li class="tabs-title"><a href="#panel_modules" onclick="ajaxLoadModulesList()">Modules</a></li>
        <li class="tabs-title"><a href="#panel_payment_settings" onclick="ajaxLoadPaymentMethodsList(1);">Payment Settings</a></li>
        <li class="tabs-title"><a href="#panel_history" onclick="ajaxLoadPaymentHistoryList()">History</a></li>
    </ul>

    <div class="tabs-content" data-tabs-content="tabs_marketing_dashboard">
        <div class="tabs-panel is-active" id="panel_overview">
            <div class="row expanded">

                <div class="large-12 columns">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="row expanded">
                                <div class="large-12 columns">
                                    <h1>Overview</h1>
                                </div>
                            </div>
                        </div>
                        <div class="panel-content">
                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_overview_modules_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                            <div id="response_overview_modules_list">

                            </div><!--end response_overview_modules_list-->
                            <script>

                                function ajaxLoadOverviewModulesList() {
                                    $('#loader_overview_modules_list').show();
                                    $('#response_overview_modules_list').hide();

                                    $.ajax({
                                        url: '/payment-management/ajax/load-modules-list?type=overview'
                                    }).done(function (data) {
                                        $('#response_overview_modules_list').html(data);
                                        $('#loader_overview_modules_list').hide();
                                        $('#response_overview_modules_list').show();
                                        $(document).foundation();

                                    });
                                }

                                ajaxLoadOverviewModulesList();
                            </script>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="tabs-panel" id="panel_modules">
            <div class="row expanded">

                <div class="large-12 columns">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="row expanded">
                                <div class="large-12 columns">
                                    <h1>Modules</h1>
                                </div>
                            </div>
                        </div>
                        <div class="panel-content">
                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_modules_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                            <div id="response_modules_list">

                            </div><!--end response_modules_list-->
                            <script>

                                function ajaxLoadModulesList() {
                                    $('#loader_modules_list').show();
                                    $('#response_modules_list').hide();

                                    $.ajax({
                                        url: '/payment-management/ajax/load-modules-list?type=all'
                                    }).done(function (data) {
                                        $('#response_modules_list').html(data);
                                        $('#loader_modules_list').hide();
                                        $('#response_modules_list').show();
                                        $(document).foundation();
                                        $('.datetimepicker').datetimepicker({
                                            datepicker: true,
                                            format: 'Y-m-d H:i',
                                            step : 30,
                                            scrollInput: false
                                        });
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="tabs-panel" id="panel_payment_settings">
            <div class="row expanded">

                <div class="large-12 columns">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="row expanded">
                                <div class="large-6 columns">
                                    <h1>Payment Settings</h1>
                                </div>
                                <div class="large-6 columns">
                                    <button class="button tiny pull-right" type="button" data-open="popup_add_new_payment_method_form">Add Payment Method</button>
                                    <div class="reveal small panel" id="popup_add_new_payment_method_form" name="popup_add_new_payment_method_form" data-reveal>
                                        @include('payment_management._partials.payment_method_form')
                                        <button class="close-button" data-close aria-label="Close modal" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-content">
                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_payment_methods_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                            <div id="response_payment_methods_list">

                            </div><!--end response_payment_methods_list-->
                            <script>
                                $(document).on('click', '#pagination_payment_methods_list a', function (e) {
                                    e.preventDefault();
                                    var page = $(this).attr('href').split('page=')[1];
                                    ajaxLoadPaymentMethodsList(page);
                                });

                                function ajaxLoadPaymentMethodsList(page) {
                                    $('#loader_payment_methods_list').show();
                                    $('#response_payment_methods_list').hide();

                                    $.ajax({
                                        url: '/payment-management/ajax/load-payment-methods-list?' +
                                        'page=' + page
                                    }).done(function (data) {
                                        $('#response_payment_methods_list').html(data);
                                        $('#loader_payment_methods_list').hide();
                                        $('#response_payment_methods_list').show();
                                        $(document).foundation();
                                        $('.datetimepicker').datetimepicker({
                                            datepicker: true,
                                            format: 'Y-m-d H:i',
                                            step : 30,
                                            scrollInput: false
                                        });
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="tabs-panel" id="panel_history">
            <div class="row expanded">


                <div class="large-12 columns">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="row expanded">
                                <div class="large-12 columns">
                                    <h1>History</h1>
                                </div>
                            </div>
                        </div>
                        <div class="panel-content">
                            {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_payment_history_list', 'class' => 'float-center', 'style' => 'display:none')) }}
                            <div id="response_payment_history_list">

                            </div><!--end response_payment_history_list-->
                            <script>

                                function ajaxLoadPaymentHistoryList() {
                                    $('#loader_payment_history_list').show();
                                    $('#response_payment_history_list').hide();

                                    $.ajax({
                                        url: '/payment-management/ajax/load-payment-history-list'
                                    }).done(function (data) {
                                        $('#response_payment_history_list').html(data);
                                        $('#loader_payment_history_list').hide();
                                        $('#response_payment_history_list').show();
                                        $(document).foundation();

                                    });
                                }


                            </script>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>


@stop