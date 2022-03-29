@extends('layouts.default')

@section('content')

    @if(is_object($customer))

        <div class="row expanded">
            <div class="large-4 columns">
                <div class="panel">
                    <div class="panel-heading">
                        <h1>Customer: <span id="title_customer_name">{{ $customer->customer_name }}</span></h1>
                        <script>
                            $(document).ready(function () {
                                document.title = 'Web360 | {{ $customer->customer_name }}';
                            });
                        </script>
                    </div>

                    <div class="panel-content">
                        <div class="row">
                            <div class="large-12 columns">
                                <label for="customer_name">
                                    Customer Name *
                                    <input type="text" id="customer_name" name="customer_name"
                                           value="{{ $customer->customer_name }}"
                                           onchange="updateTitleCustomerName(this.value);">
                                    <script>
                                        function updateTitleCustomerName(customer_name) {
                                            ajaxUpdateIndividualFieldsOfModel('customers', '{{ $customer->id }}', 'customer_name', customer_name, 'customer_name', 'Customer', true);

                                            $('#title_customer_name').html(customer_name);
                                            $(document).prop('title', 'Web360 | ' + customer_name);
                                        }
                                    </script>
                                </label>
                            </div>
                        </div>
                        @include('customers._partials.customer_details_form')

                    </div>


                </div>

            </div>
            <div class="columns large-8">
                <div class="panel">
                    <div class="panel-content">
                        <ul class="tabs" data-tabs id="customer_tabs">
                            <li class="tabs-title is-active"><a href="#panel_contacts" aria-selected="true">Contacts</a>
                            </li>
                            <li class="tabs-title"><a href="#panel_leads" aria-selected="true"
                                                      onclick="ajaxLoadCustomerLeads(1)">Leads</a></li>
                            <li class="tabs-title"><a href="#panel_calls" aria-selected="true"
                                                      onclick="ajaxLoadCustomerCalls(1)">Calls</a></li>
                            <li class="tabs-title"><a href="#panel_meetings" aria-selected="true"
                                                      onclick="ajaxLoadCustomerMeetings(1)">Meetings</a></li>
                            <li class="tabs-title"><a href="#panel_emails" aria-selected="true"
                                                      onclick="ajaxLoadCustomerEmails(1)">Emails</a></li>
                            <li class="tabs-title"><a href="#panel_quotations" aria-selected="true"
                                                      onclick="ajaxLoadCustomerQuotations(1)">Quotations</a></li>
                        </ul>
                        <div class="tabs-content" data-tabs-content="customer_tabs">
                            <div class="tabs-panel is-active" id="panel_contacts">
                                @include('contacts.index', array('customer' => $customer))
                            </div>
                            <div class="tabs-panel" id="panel_leads">
                                <div class="row expanded">
                                    @if(!Employee::checkAuthorizedAccess(array('sales')))
                                        <div class="large-2 columns">
                                            <select name="leads_assigned_to_filter" id="leads_assigned_to_filter"
                                                    onchange="ajaxLoadCustomerLeads()"
                                                    style="display: <?php Employee::checkAuthorizedAccess(array('sales')) ? 'none': 'block'; ?>">
                                            </select>
                                        </div>
                                    @endif

                                </div>

                                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_customer_leads', 'class' => 'float-center', 'style' => 'display:none')) }}
                                <div id="response_customer_leads">

                                </div>
                                <script>
                                    /*==================== PAGINATION =========================*/

                                    $(document).on('click', '#pagination_customer_leads_list a', function (e) {
                                        e.preventDefault();
                                        var page = $(this).attr('href').split('page=')[1];
                                        //location.hash = page;
                                        ajaxLoadCustomerLeads(page);
                                    });

                                    function ajaxLoadCustomerLeads(page) {

                                        $('#loader_customer_leads ').show();
                                        $('#response_customer_leads').hide();

                                        var leads_assigned_to_filter = $("#leads_assigned_to_filter").val();

                                        $.ajax({
                                            url: '/customers/ajax/load-customer-leads?' +
                                            'page=' + page +
                                            '&customer_id=' + '{{ $customer->id }}' +
                                            '&leads_assigned_to_filter=' + leads_assigned_to_filter
                                        }).done(function (data) {

                                            $('#response_customer_leads').html(data);
                                            $('#loader_customer_leads').hide();
                                            $('#response_customer_leads').show();
                                            $(document).foundation();

                                        });
                                    }
                                </script>
                            </div>
                            <div class="tabs-panel" id="panel_calls">
                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        {{ Form::text('search_by_call_or_agenda', null, array('placeholder' => 'Agenda or Summary', 'id' => 'search_by_call_or_agenda', 'onchange' => 'ajaxLoadCustomerCalls()','class' => 'large-text-input-field')) }}
                                    </div>
                                    <div class="large-4 columns">
                                        <select name="created_by_call_filter" id="created_by_call_filter"
                                                onchange="ajaxLoadCustomerCalls()">
                                        </select>
                                    </div>
                                    <div class="large-4 columns">
                                       &nbsp;
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <input type="button" data-open="popup_add_new_call" onclick="ajaxGetAddNewCallForm({{ $customer->id }})" class="button tiny float-right" value="Add New Call"/>
                                    </div>
                                </div>

                                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_customer_calls', 'class' => 'float-center', 'style' => 'display:none')) }}
                                <div id="response_customer_calls">

                                </div>
                                <script>
                                    /*==================== PAGINATION =========================*/

                                    $(document).on('click', '#pagination_calls_list a', function (e) {
                                        e.preventDefault();
                                        var page = $(this).attr('href').split('page=')[1];
                                        //location.hash = page;
                                        ajaxLoadCustomerCalls(page);
                                    });

                                    function ajaxLoadCustomerCalls(page) {

                                        $('#loader_customer_calls ').show();
                                        $('#response_customer_calls').hide();

                                        var created_by_call_filter = $("#created_by_call_filter").val();
                                        created_by_call_filter = created_by_call_filter ? created_by_call_filter : '';

                                        var search_by_call_or_agenda = $("#search_by_call_or_agenda").val();

                                        $.ajax({
                                            url: '/customers/ajax/load-customer-activities?' +
                                            'page=' + page +
                                            '&activity_type=calls' +
                                            '&customer_id=' + '{{ $customer->id }}' +
                                            '&created_by_filter=' + created_by_call_filter +
                                            '&search_by_call_or_agenda=' + search_by_call_or_agenda

                                        }).done(function (data) {

                                            $('#response_customer_calls').html(data);
                                            $('#loader_customer_calls').hide();
                                            $('#response_customer_calls').show();
                                            $(document).foundation();

                                        });
                                    }

                                </script>
                            </div>
                            <div class="tabs-panel" id="panel_meetings">
                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        {{ Form::text('search_by_meeting', null, array('placeholder' => 'Search By Agenda/Venue/Summary', 'id' => 'search_by_meeting', 'onchange' => 'ajaxLoadCustomerMeetings()','class' => 'large-text-input-field')) }}
                                    </div>
                                    <div class="large-4 columns">
                                        <select name="created_by_meeting_filter" id="created_by_meeting_filter"
                                                onchange="ajaxLoadCustomerMeetings()">
                                        </select>
                                    </div>
                                    <div class="large-4 columns">
                                        &nbsp;
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <input type="button" data-open="popup_add_new_meeting" onclick="ajaxGetAddNewMeetingForm({{ $customer->id }})" value="Add New Meeting" class="button tiny float-right"/>
                                    </div>
                                </div>

                                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_customer_meetings', 'class' => 'float-center', 'style' => 'display:none')) }}
                                <div id="response_customer_meetings">

                                </div>
                                <script>
                                    /*==================== PAGINATION =========================*/

                                    $(document).on('click', '#pagination_meetings_list a', function (e) {
                                        e.preventDefault();
                                        var page = $(this).attr('href').split('page=')[1];
                                        //location.hash = page;
                                        ajaxLoadCustomerMeetings(page);
                                    });

                                    function ajaxLoadCustomerMeetings(page) {

                                        $('#loader_customer_meetings ').show();
                                        $('#response_customer_meetings').hide();

                                        var search_by_meeting = $("#search_by_meeting").val();

                                        var created_by_meeting_filter = $("#created_by_meeting_filter").val();
                                        created_by_meeting_filter = created_by_meeting_filter ? created_by_meeting_filter : '';


                                        $.ajax({
                                            url: '/customers/ajax/load-customer-activities?' +
                                            'page=' + page +
                                            '&activity_type=meetings' +
                                            '&customer_id=' + '{{ $customer->id }}' +
                                            '&search_by_meeting=' + search_by_meeting +
                                            '&created_by_filter=' + created_by_meeting_filter

                                        }).done(function (data) {

                                            $('#response_customer_meetings').html(data);
                                            $('#loader_customer_meetings').hide();
                                            $('#response_customer_meetings').show();
                                            $(document).foundation();

                                        });
                                    }

                                </script>
                            </div>
                            <div class="tabs-panel" id="panel_emails">
                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        {{ Form::text('search_by_email', null, array('placeholder' => 'Subject or Body', 'id' => 'search_by_email', 'onchange' => 'ajaxLoadCustomerEmails()','class' => 'large-text-input-field')) }}
                                    </div>
                                    <div class="large-4 columns">
                                        <select name="created_by_email_filter" id="created_by_email_filter"
                                                onchange="ajaxLoadCustomerEmails()">
                                        </select>
                                    </div>
                                    <div class="large-4 columns">
                                        &nbsp;
                                    </div>
                                </div>

                                <div class="row expanded">
                                    <div class="large-12 columns">
                                        <input type="button" data-open="popup_add_new_email" onclick="ajaxGetAddNewEmailForm({{ $customer->id }})" value="Add New Email" class="button tiny float-right"/>
                                    </div>
                                </div>

                                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_customer_emails', 'class' => 'float-center', 'style' => 'display:none')) }}
                                <div id="response_customer_emails">

                                </div>
                                <script>
                                    /*==================== PAGINATION =========================*/

                                    $(document).on('click', '#pagination_emails_list a', function (e) {
                                        e.preventDefault();
                                        var page = $(this).attr('href').split('page=')[1];
                                        //location.hash = page;
                                        ajaxLoadCustomerEmails(page);
                                    });

                                    function ajaxLoadCustomerEmails(page) {

                                        $('#loader_customer_emails ').show();
                                        $('#response_customer_emails').hide();

                                        var search_by_email = $("#search_by_email").val();
                                        var created_by_email_filter = $("#created_by_email_filter").val();
                                        created_by_email_filter = created_by_email_filter ? created_by_email_filter : '';

                                        $.ajax({
                                            url: '/customers/ajax/load-customer-activities?' +
                                            'page=' + page +
                                            '&activity_type=emails' +
                                            '&customer_id=' + '{{ $customer->id }}' +
                                            '&search_by_email=' + search_by_email +
                                            '&created_by_filter=' + created_by_email_filter

                                        }).done(function (data) {

                                            $('#response_customer_emails').html(data);
                                            $('#loader_customer_emails').hide();
                                            $('#response_customer_emails').show();
                                            $(document).foundation();

                                        });
                                    }

                                </script>
                            </div>
                            <div class="tabs-panel" id="panel_quotations">
                                <div class="row expanded">
                                    <div class="large-4 columns">
                                        {{ Form::text('search_by_project_quote', null, array('placeholder' => 'Project Quote', 'id' => 'search_by_project_quote', 'onchange' => 'ajaxLoadCustomerQuotations()','class' => 'large-text-input-field')) }}
                                    </div>
                                    <div class="large-4 columns">
                                        <select name="generated_by_quotation_filter" id="generated_by_quotation_filter"
                                                onchange="ajaxLoadCustomerQuotations()">
                                        </select>
                                    </div>
                                    <div class="large-4 columns">
                                        <select name="status_quotation_filter" id="status_quotation_filter"
                                                onchange="ajaxLoadCustomerQuotations()">
                                                    <option value="">Quotation Status</option>
                                                @foreach( Quotation::$quotation_status as $status_key => $status_value )
                                                    <option value="{{ $status_key }}">{{ $status_value }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{ HTML::image('assets/img/loading.gif', 'Loading', array('id' => 'loader_customer_quotations', 'class' => 'float-center', 'style' => 'display:none')) }}
                                <div id="response_customer_quotations">

                                </div>
                                <script>
                                    /*==================== PAGINATION =========================*/

                                    $(document).on('click', '#pagination_quotations_list a', function (e) {
                                        e.preventDefault();
                                        var page = $(this).attr('href').split('page=')[1];
                                        //location.hash = page;
                                        ajaxLoadCustomerQuotations(page);
                                    });

                                    function ajaxLoadCustomerQuotations(page) {

                                        $('#loader_customer_quotations ').show();
                                        $('#response_customer_quotations').hide();

                                        var search_by_project_quote = $("#search_by_project_quote").val();

                                        var generated_by_quotation_filter = $("#generated_by_quotation_filter").val();
                                        generated_by_quotation_filter = generated_by_quotation_filter ? generated_by_quotation_filter : '';

                                        var status_quotation_filter = $("#status_quotation_filter").val();

                                        $.ajax({
                                            url: '/customers/ajax/load-customer-quotations?' +
                                            'page=' + page +
                                            '&customer_id=' + '{{ $customer->id }}' +
                                            '&search_by_project_quote=' + search_by_project_quote +
                                            '&generated_by_filter=' + generated_by_quotation_filter +
                                            '&status_quotation_filter=' + status_quotation_filter

                                        }).done(function (data) {

                                            $('#response_customer_quotations').html(data);
                                            $('#loader_customer_quotations').hide();
                                            $('#response_customer_quotations').show();
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
        </div>

        <script>
            function getEmployeesList() {
                $.ajax({
                    url: '/employees/ajax/get-employees-list'
                }).done(function (data) {
                    $('#created_by_call_filter').empty();
                    $('#created_by_meeting_filter').empty();
                    $('#created_by_email_filter').empty();
                    $('#generated_by_quotation_filter').empty();
                    $('#leads_assigned_to_filter').empty();

                    data = $.parseJSON(data);

                    $('#created_by_call_filter').append("<option value=''>Created By</option>");
                    $('#created_by_meeting_filter').append("<option value=''>Created By</option>");
                    $('#created_by_email_filter').append("<option value=''>Created By</option>");
                    $('#generated_by_quotation_filter').append("<option value=''>Generated By</option>");
                    $('#leads_assigned_to_filter').append("<option value=''>Assigned To</option>");

                    for (var i in data) {
                        $('#created_by_call_filter').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                        $('#created_by_meeting_filter').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                        $('#created_by_email_filter').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                        $('#generated_by_quotation_filter').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                        $('#leads_assigned_to_filter').append("<option value='" + data[i].id + "'>" + data[i].full_name + "</option>");
                    }
                    $('#created_by_call_filter').selectize({
                        create: false,
                        sortField: 'text'
                    });
                    $('#created_by_meeting_filter').selectize({
                        create: false,
                        sortField: 'text'
                    });
                    $('#created_by_email_filter').selectize({
                        create: false,
                        sortField: 'text'
                    });

                    $('#generated_by_quotation_filter').selectize({
                        create: false,
                        sortField: 'text'
                    });

                    $('#leads_assigned_to_filter').selectize({
                        create: false,
                        sortField: 'text'
                    });

                    $('#status_quotation_filter').selectize({
                        create: false,
                        sortField: 'text'
                    });
                });
            }

            getEmployeesList();



        </script>

    @else
        <div class="row expanded">
            <div class="large-12 columns">
                <p>Customer not found.</p>
            </div>
        </div>
    @endif

    @include('my_activities._partials.add_new_activity_popup_forms', ['post_data_to_load' => 'reloadActivityLists'])

    <script>
        function reloadActivityLists() {
            ajaxLoadCustomerCalls(1);
            ajaxLoadCustomerMeetings(1);
            ajaxLoadCustomerEmails(1);
        }

        function loadDashboardData(){
            ajaxLoadCustomerLeads(1);
            ajaxLoadCustomerCalls(1);
            ajaxLoadCustomerMeetings(1);
            ajaxLoadCustomerEmails(1);
            ajaxLoadCustomerQuotations(1);

        }
    </script>

    @include('my_activities._partials.edit_activity_popup_forms', ['post_data_to_load' => 'customer-activity-list'])
@stop