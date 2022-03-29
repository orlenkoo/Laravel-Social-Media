<ul class="top-icon-menu">
    <li class="<?php StringUtilities::setSelectedMenuClass("selected", "home") ?>"><a href="{{ route('home') }}"><img src="{{ asset('assets/img/icons/icon-dashboard.png') }}" alt="">
            <p>Dashboard</p></a></li>
    <li class="web360-tooltip <?php StringUtilities::setSelectedMenuClass("selected", "leads.index") ?>" title="Manage all your Leads."><a href="{{ route('leads.index') }}"><img src="{{ asset('assets/img/icons/icon-leads-list.png') }}" alt="">
            <p>Leads</p></a></li>
    <li class="web360-tooltip <?php StringUtilities::setSelectedMenuClass("selected", "customers.index") ?>" title="View & Update Customers."><a href="{{ route('customers.index') }}"><img src="{{ asset('assets/img/icons/icon-customers-3.png') }}" alt="">
            <p>Customers</p></a></li>
    <li class="web360-tooltip <?php StringUtilities::setSelectedMenuClass("selected", "my_activities.index") ?>" title="Manage Calls/Emails/Meetings."><a href="{{ route('my_activities.index', array()) }}"><img src="{{ asset('assets/img/icons/icon-activities.png') }}" alt="">
            <p>My Activities</p></a></li>
    <li class="web360-tooltip <?php StringUtilities::setSelectedMenuClass("selected", "tasks.index") ?>" title="Manage Tasks and Reminders."><a href="{{ route('tasks.index') }}"><img src="{{ asset('assets/img/icons/icon-tasks.png') }}" alt="">
            <p>Tasks</p></a></li>
    @if(Employee::checkAuthorizedAccess(array('marketing', 'management')))
        <?php
       /*
        <li class="web360-tooltip <?php StringUtilities::setSelectedMenuClass("selected", "campaigns.index") ?>" title="Manage marketing campaigns."><a href="{{ route('campaigns.index') }}"><img src="{{ asset('assets/img/icons/icon-campaigns.png') }}" alt="">
                <p>Campaigns</p></a></li>

        */

        ?>
    @endif

    @if(OrganizationConfiguration::checkIfConfigurationDisabled(Config::get('project_vars.organization_configurations')['disable_quotations_feature_id']))
        <li class="web360-tooltip <?php StringUtilities::setSelectedMenuClass("selected", "quotations.index") ?>" title="Manage all your Quotations."><a href="{{ route('quotations.index') }}"><img src="{{ asset('assets/img/icons/icon-quotations.png') }}" alt="">
                <p>Quotations</p></a></li>
    @endif
    @if(Employee::checkAuthorizedAccess(array('management', 'marketing')))
        <li class="web360-tooltip <?php StringUtilities::setSelectedMenuClass("selected", "reports.index") ?>" title="View reports."><a href="{{ route('reports.index') }}"><img src="{{ asset('assets/img/icons/icon-reports.png') }}" alt="">
                <p>Reports</p></a></li>
    @endif
    <?php

    /*





    @if(in_array(Session::get('user-email'), Employee::$only_allow_for_test_users))
    <li class="web360-tooltip <?php StringUtilities::setSelectedMenuClass("selected", "landing_pages.index") ?>" title="Create & update landing pages."><a href="{{ route('landing_pages.index') }}"><img src="{{ asset('assets/img/icons/icon-landing-pages.png') }}" alt="">
            <p>Landing Pages</p></a></li>
    <li class="web360-tooltip <?php StringUtilities::setSelectedMenuClass("selected", "email_module.index") ?>" title="Create & update email campaigns."><a href="{{ route('email_module.index') }}"><img src="{{ asset('assets/img/icons/icon_email_campaigns.png') }}" alt="">
            <p>Email Campaigns</p></a></li>
    @endif
    */
    ?>

</ul><!--end top-icon-menu-->