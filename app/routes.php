<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('not-authorized', array("as" => "not_authorized", "uses" => 'HomeController@showNotAuthorized'));
Route::get('logout', array("as" => "logout", "uses" => 'HomeController@logout'));
Route::get('login', array("as" => "login", "uses" => 'HomeController@login'));

Route::post('login/submit', array("as" => "login.submit", "uses" => 'SignUpProcessController@submitLogin'));

//password reset
// Route::get('employees/ajax/check-email-exists', array("as" => "employees.ajax.check.email.exists", "uses" => 'EmployeesController@ajaxCheckEmailExists'));
Route::get('employees/show-password-reset-form', array("as" => "employees.show.password.reset.form", "uses" => 'EmployeesController@showPasswordResetForm'));
Route::post('employees/password-reset', array("as" => "employees.password.reset", "uses" => 'EmployeesController@passwordReset'));

// cron jobs
Route::get('third-party-api/delacon/save-to-the-database', array("as" => "third_party_api.delacon.save_to_the_database", "uses" => 'DelaconCallTrackingReportController@saveToTheDatabase'));
Route::get('quotations/send-follow-up-reminder-email', array("as" => "quotations.send_follow_up_reminder_email", "uses" => 'QuotationsController@sendQuotationFollowUpReminderEmail'));

// delacon push api link
Route::post('services/calldata/callenddata', array("as" => "third_party_api.delacon.push_api_save_call_data", "uses" => 'DelaconCallTrackingReportController@delaconPushAPISaveCallRecord'));

// novocall push api links
Route::post('services/novocall/save-call-leads', array("as" => "third_party_api.novocall.push_api_save_call_lead", "uses" => 'NovocallAPIIntegrationsController@saveCallLead'));
Route::post('services/novocall/save-message-leads', array("as" => "third_party_api.novocall.push_api_save_message_lead", "uses" => 'NovocallAPIIntegrationsController@saveMessageLead'));
Route::post('services/novocall/save-schedule-leads', array("as" => "third_party_api.novocall.push_api_save_schedule_lead", "uses" => 'NovocallAPIIntegrationsController@saveScheduleLead'));

Route::group(array('before' => 'mtauth'), function () {

    Route::get('/', array("as" => "home", "uses" => 'HomeController@index'));

    // Common Utility Functions
    Route::post('common-utility/ajax/update-individual-fields-of-model', array("as" => "common_utility.ajax.update_individual_fields_of_model", "uses" => "CommonUtilityController@ajaxUpdateIndividualFieldsOfModel"));
    Route::post('common-utility/ajax/post-sessions-date-range', array("as" => "common_utility.ajax.post_sessions_date_range", "uses" => "CommonUtilityController@ajaxPostDateRangeSessions"));


    // quotations
    Route::get('quotations', array("as" => "quotations.index", "uses" => "QuotationsController@index"));
    Route::get('quotations/ajax/load-quotations-list', array("as" => "quotations.ajax.load_quotations_list", "uses" => "QuotationsController@ajaxLoadQuotationsList"));
    Route::post('quotations/ajax/save-new-quotation', array("as" => "quotations.ajax.save_new_quotation", "uses" => "QuotationsController@ajaxSaveNewQuotation"));
    Route::post('quotations/ajax/update-quotation', array("as" => "quotations.ajax.update_quotation", "uses" => "QuotationsController@ajaxUpdateQuotation"));
    Route::post('quotations/ajax/send-quotation-for-approval', array("as" => "quotations.ajax.send_quotation_for_approval", "uses" => "QuotationsController@ajaxSendQuotationForApproval"));
    Route::get('quotations/generate-pdf', array("as" => "quotations.generate_pdf", "uses" => "QuotationsController@generatePDF"));
    Route::get('quotations/ajax/load-quotation-attachments-table', array("as" => "quotations.ajax.load_quotation_attachments_table", "uses" => "QuotationsController@ajaxLoadQuotationAttachmentsTable"));
    Route::post('quotations/ajax/save-quotation-attachment', array("as" => "quotations.ajax.save_quotation_attachment", "uses" => "QuotationsController@ajaxSaveQuotationAttachment"));
    Route::post('quotations/ajax/update-quotation-status', array("as" => "quotations.ajax.update_quotation_status", "uses" => "QuotationsController@ajaxUpdateQuotationStatus"));
    Route::get('quotations/ajax-load-dashboard-contracted-sales-widget', array("as" => "quotations.ajax.load_dashboard_contracted_sales_widget", "uses" => "QuotationsController@ajaxLoadDashboardContractedSalesWidget"));
    Route::get('quotations/ajax/load-my-sales-dashboard-sales-volume-by-campaign-chart', array("as" => "quotations.ajax.load_my_sales_dashboard_sales_volume_by_campaign", "uses" => 'QuotationsController@ajaxLoadMySalesDashboardSalesVolumeByCampaignChart'));
    Route::get('quotations/ajax/load-my-sales-dashboard-sales-value-by-campaign-chart', array("as" => "quotations.ajax.load_my_sales_dashboard_sales_value_by_campaign", "uses" => 'QuotationsController@ajaxLoadMySalesDashboardSalesValueByCampaignChart'));
    Route::get('quotations/ajax/load-my-sales-dashboard-sales-volume-by-sales-person-chart', array("as" => "quotations.ajax.load_my_sales_dashboard_sales_volume_by_sales_person", "uses" => 'QuotationsController@ajaxLoadMySalesDashboardSalesVolumeBySalesPersonChart'));
    Route::get('quotations/ajax/load-my-sales-dashboard-sales-value-by-sales-person-chart', array("as" => "quotations.ajax.load_my_sales_dashboard_sales_value_by_sales_person", "uses" => 'QuotationsController@ajaxLoadMySalesDashboardSalesValueBySalesPersonChart'));

    // contacts
    Route::get('contacts/ajax/load-contacts-list', array("as" => "contacts.ajax.load_contacts_list", "uses" => 'ContactsController@ajaxLoadContactsList'));
    Route::post('contacts/ajax/save-new-customer-contact', array("as" => "contacts.ajax.save_new_customer_contact", "uses" => 'ContactsController@ajaxSaveNewCustomerContact'));
    Route::post('contacts/ajax/update-customer-primary-contact', array("as" => "contacts.ajax.update_customer_primary_contact", "uses" => 'ContactsController@ajaxUpdateCustomerPrimaryContact'));
    Route::get('contacts/ajax/get-customer-primary-contact-details', array("as" => "contacts.ajax.get_customer_primary_contact_details", "uses" => 'ContactsController@ajaxGetCustomerPrimaryContactDetails'));
    Route::get('contacts/ajax/load-contacts-list-by-customer-id', array("as" => "contacts.ajax.load_contacts_list_by_customer_id", "uses" => 'ContactsController@ajaxLoadContactsListByCustomer'));

    // customers
    Route::get('customers', array("as" => "customers.index", "uses" => 'CustomersController@index'));
    Route::get('customers/{customer_id}', array("as" => "customers.view", "uses" => 'CustomersController@viewCustomer'));
    Route::get('customers/ajax/load-customers', array("as" => "customers.ajax.load_customers", "uses" => 'CustomersController@ajaxLoadCustomers'));
    Route::get('customers/ajax/load-customer-leads', array("as" => "customers.ajax.load_customer_leads", "uses" => 'CustomersController@ajaxLoadCustomerLeads'));
    Route::get('customers/ajax/load-customer-activities', array("as" => "customers.ajax.load_customer_activities", "uses" => 'CustomersController@ajaxLoadCustomerActivities'));
    Route::get('customers/ajax/load-customer-quotations', array("as" => "customers.ajax.load_customer_quotations", "uses" => 'CustomersController@ajaxLoadCustomerQuotations'));
    Route::get('customers/ajax/load-customer-list', array("as" => "customers.ajax.load_customer_list", "uses" => 'CustomersController@ajaxLoadCustomerList'));
    Route::get('customers/ajax/load-dashboard-check-customer-list', array("as" => "customers.ajax.load_dashboard_check_customer_list", "uses" => 'CustomersController@ajaxLoadDashboardCheckCustomerList'));
    Route::get('customers/ajax/get-customers-list-by-name', array("as" => "customers.ajax.get_customers_list_by_name", "uses" => 'CustomersController@ajaxGetCustomersListByName'));
    Route::post('customers/ajax/assign-customer-tags', array("as" => "customers.ajax.assign_customer_tags", "uses" => 'CustomersController@assignCustomerTags'));
    Route::get('customers/ajax/get-customer-tags', array("as" => "customers.ajax.get_customer_tags", "uses" => 'CustomersController@ajaxLoadCustomerTags'));

    // leads
    Route::get('leads', array("as" => "leads.index", "uses" => 'LeadsController@index'));
    Route::get('leads/ajax/load-leads-list', array("as" => "leads.ajax.load_leads_list", "uses" => 'LeadsController@ajaxLoadLeadsList'));
    Route::post('leads/ajax/save-new-lead', array("as" => "leads.ajax.save_new_lead", "uses" => 'LeadsController@ajaxSaveNewLead'));
    Route::get('leads/ajax/load-dashboard-leads', array("as" => "leads.ajax.load_dashboard_leads", "uses" => 'LeadsController@ajaxLoadDashboardLeadsList'));
    Route::get('leads/ajax/load-dashboard-lead-details', array("as" => "leads.ajax.load_dashboard_lead_details", "uses" => 'LeadsController@ajaxLoadDashboardLeadDetails'));
    Route::get('leads/ajax/load-dashboard-lead-notes-list', array("as" => "leads.ajax.load_dashboard_lead_notes_list", "uses" => 'LeadsController@ajaxLoadDashboardLeadNotesList'));
    Route::post('leads/ajax/save-lead-note', array("as" => "leads.ajax.save_lead_note", "uses" => 'LeadsController@ajaxSaveLeadNote'));
    Route::post('leads/ajax/update-lead-assigned-to', array("as" => "leads.ajax.update_lead_assigned_to", "uses" => 'LeadsController@ajaxUpdateLeadAssignedTo'));
    Route::post('leads/ajax/update-campaign', array("as" => "leads.ajax.update_campaign", "uses" => 'LeadsController@ajaxUpdateLeadCampaign'));
    Route::get('leads/ajax/load-sales-dashboard-sales-pipeline-chart', array("as" => "leads.ajax.load_sales_dashboard_sales_pipeline_chart", "uses" => 'LeadsController@ajaxLoadSalesDashboardSalesPipelineChart'));
    Route::post('leads/ajax/create-new-customer-for-lead', array("as" => "leads.ajax.create_new_customer_for_lead", "uses" => 'LeadsController@ajaxCreateNewCustomerForLead'));
    Route::post('leads/ajax/assign-customer-for-lead', array("as" => "leads.ajax.assign_customer_for_lead", "uses" => 'LeadsController@ajaxAssignCustomerForLead'));
    Route::get('leads/ajax/load-dashboard-lead-time-line', array("as" => "leads.ajax.load_dashboard_lead_time_line", "uses" => 'LeadsController@ajaxLoadDashboardLeadTimeLine'));
    Route::get('leads/ajax/load-dashboard-lead-statistics', array("as" => "leads.ajax.load_dashboard_lead_statistics", "uses" => 'LeadsController@ajaxLoadDashboardLeadStatistics'));
    Route::get('leads/ajax/load-dashboard-campaign_performance', array("as" => "leads.ajax.load_dashboard_campaign_performance", "uses" => 'LeadsController@ajaxLoadDashboardCampaignPerformance'));
    Route::post('leads/ajax/assign-lead-tags', array("as" => "leads.ajax.assign_lead_tags", "uses" => 'LeadsController@assignLeadTags'));
    Route::get('leads/ajax/get-lead-meta-details-for-drop-down', array("as" => "leads.ajax.get_lead_meta_details_for_drop_down", "uses" => 'LeadsController@ajaxGetLeadMetaDetailsForDropDown'));
    Route::get('leads/ajax/load-my-leads-dashboard-lead-overview-volume-chart', array("as" => "leads.ajax.load_my_leads_dashboard_lead_overview_volume_chart", "uses" => 'LeadsController@ajaxLoadMyLeadsDashboardLeadOverviewVolumeChart'));
    Route::get('leads/ajax/load-my-leads-dashboard-lead-overview-value-chart', array("as" => "leads.ajax.load_my_leads_dashboard_lead_overview_value_chart", "uses" => 'LeadsController@ajaxLoadMyLeadsDashboardLeadOverviewValueChart'));
    Route::get('leads/ajax/load-my-leads-dashboard-lead-volume-by-campaign-chart', array("as" => "leads.ajax.load_my_leads_dashboard_lead_volume_by_campaign", "uses" => 'LeadsController@ajaxLoadMyLeadsDashboardLeadVolumeByCampaignChart'));
    Route::get('leads/ajax/load-my-leads-dashboard-lead-value-by-campaign-chart', array("as" => "leads.ajax.load_my_leads_dashboard_lead_value_by_campaign", "uses" => 'LeadsController@ajaxLoadMyLeadsDashboardLeadValueByCampaignChart'));
    Route::get('leads/ajax/get-lead-tags', array("as" => "leads.ajax.get_lead_tags", "uses" => 'LeadsController@ajaxLoadLeadTags'));


    // campaigns
    Route::get('campaigns', array("as" => "campaigns.index", "uses" => "CampaignsController@index"));
    Route::get('campaigns/ajax/load-campaigns-list', array("as" => "campaigns.ajax.load_campaigns_list", "uses" => "CampaignsController@ajaxLoadCampaignsList"));
    Route::post('campaigns/ajax/save-campaign', array("as" => "campaigns.ajax.campaign", "uses" => "CampaignsController@ajaxSaveCampaign"));
    Route::post('campaigns/ajax/update-campaign-table-media-channel', array("as" => "campaigns.ajax.update.campaign.table.media.channel", "uses" => "CampaignsController@ajaxUpdateCampaignTableMediaChannel"));
    Route::get('campaigns/ajax/get-campaigns-list', array("as" => "campaigns.ajax.get.campaigns.list", "uses" => 'CampaignsController@ajaxGetCampaignsList'));
    Route::post('campaigns/ajax/save-campaign-url', array("as" => "campaigns.ajax.campaign_url", "uses" => "CampaignsController@ajaxSaveCampaignURL"));
    Route::get('campaigns/ajax/load-campaign-urls-list', array("as" => "campaigns.ajax.load_campaign_urls_list", "uses" => "CampaignsController@ajaxLoadCampaignUrlsList"));

    // settings
    Route::get('settings', array("as" => "settings.index", "uses" => "SettingsController@index"));
    Route::get('settings/ajax/load-media-channels-list', array("as" => "settings.ajax.load_media_channels_list", "uses" => "SettingsController@ajaxLoadMediaChannelsList"));
    Route::post('settings/ajax/save-media-channel', array("as" => "settings.ajax.save_media_channel", "uses" => "SettingsController@ajaxSaveMediaChannel"));
    Route::post('settings/ajax/save-preference-changes', array("as" => "settings.ajax.save_preference_changes", "uses" => "SettingsController@ajaxSavePreferenceChanges"));
    Route::get('settings/ajax/load-organization-preference-logo-image', array("as" => "settings.ajax.load.organization.preference.logo.image", "uses" => "SettingsController@ajaxLoadOrganizationPreferenceLogoImage"));

    // email_templates
    Route::get('email-templates/ajax/load-user-defined-tags-list', array("as" => "email_templates.ajax.load_user_defined_tags_list", "uses" => "EmailTemplatesController@ajaxLoadEmailTemplateUserDefinedTagsList"));
    Route::post('email-templates/ajax/save-user-defined-tag', array("as" => "email_templates.ajax.save_user_defined_tag", "uses" => "EmailTemplatesController@ajaxSaveUserDefinedTag"));
    Route::get('email-templates/ajax/load-user-defined-action-buttons-list', array("as" => "email_templates.ajax.load_user_defined_action_buttons_list", "uses" => "EmailTemplatesController@ajaxLoadEmailTemplateUserDefinedActionButtonsList"));
    Route::post('email-templates/ajax/save-user-defined-action_button', array("as" => "email_templates.ajax.save_user_defined_action_button", "uses" => "EmailTemplatesController@ajaxSaveUserDefinedActionButton"));
    Route::get('email-templates/ajax/load-email_templates-list', array("as" => "email_templates.ajax.load_email_templates_list", "uses" => "EmailTemplatesController@ajaxLoadEmailTemplatesList"));
    Route::post('email-templates/ajax/save-email-template', array("as" => "email_templates.ajax.save_email_template", "uses" => "EmailTemplatesController@ajaxSaveEmailTemplate"));
    Route::post('email-templates/ajax/update-email-template', array("as" => "email_templates.ajax.update_email_template", "uses" => "EmailTemplatesController@ajaxUpdateEmailTemplate"));
    Route::get('email-templates/ajax/check-user-defined-action-button-exists', array("as" => "email_templates.ajax.check_user_defined_action_button_exists", "uses" => "EmailTemplatesController@checkUserDefinedActionButtonExists"));
    Route::get('email-templates/ajax/check-user-defined-tag-exists', array("as" => "email_templates.ajax.check_user_defined_tag_exists", "uses" => "EmailTemplatesController@checkUserDefinedTagExists"));
    Route::post('email-templates/ajax/update-user-defined-action-button-name', array("as" => "email_templates.ajax.update_user_defined_action_button_name", "uses" => "EmailTemplatesController@ajaxUpdateUserDefinedActionButtonName"));
    Route::post('email-templates/ajax/update-user-defined-tag-name', array("as" => "email_templates.ajax.update_user_defined_tag_name", "uses" => "EmailTemplatesController@ajaxUpdateUserDefinedTagName"));
    Route::get('email-templates/ajax/get-templates-list', array("as" => "email_templates.ajax.get.templates.list", "uses" => 'EmailTemplatesController@ajaxGetTemplatesList'));
    Route::get('email-templates/ajax/get-template-details', array("as" => "email_templates.ajax.get.template.details", "uses" => 'EmailTemplatesController@ajaxGetTemplateDetails'));

    // reports
    Route::get('reports', array("as" => "reports.index", "uses" => "ReportsController@index"));
    Route::get('reports/ajax/load-report-charts', array("as" => "reports.ajax.load_report_charts", "uses" => "ReportsController@ajaxLoadReportCharts"));
    Route::get('reports/ajax/load-report-activity-efficiency-table', array("as" => "reports.ajax.load_report_activity_efficiency_table", "uses" => "ReportsController@ajaxLoadReportsActivityEfficiencyTable"));
    Route::get('reports/ajax/load-report-sales-efficiency-table', array("as" => "reports.ajax.load_report_sales_efficiency_table", "uses" => "ReportsController@ajaxLoadReportsSalesEfficiencyTable"));
    Route::post('reports/generate-report', array("as" => "reports.generate_report", "uses" => "ReportsController@generateReport"));

    // event360 leads
    Route::post('event360-leads', array("as" => "event360_leads.store", "uses" => 'Event360LeadsController@store'));
    Route::post('event360-leads/ajax/update-status/', array("as" => "event360_leads.ajax.update.status", "uses" => 'Event360LeadsController@updateAjaxLeadStatus'));
    Route::post('event360-leads/ajax/update-rating/', array("as" => "event360_leads.ajax.update.rating", "uses" => 'Event360LeadsController@updateAjaxLeadRating'));
    Route::post('event360-leads/ajax/forward/', array("as" => "event360_leads.ajax.forward", "uses" => 'Event360LeadsController@forwardAjaxLead'));
    Route::post('event360-leads/ajax/save-note/', array("as" => "event360_leads.ajax.save.note", "uses" => 'Event360LeadsController@saveAjaxLeadNotes'));
    Route::post('event360-leads/ajax/save-message/', array("as" => "event360_leads.ajax.save.message", "uses" => 'Event360LeadsController@saveAjaxMessage'));
    Route::put('event360-leads/{leads}', array("as" => "event360_leads.update", "uses" => 'Event360LeadsController@update'));
    Route::get('event360-leads/disable/{id}', array("as" => "event360_leads.disable", "uses" => 'Event360LeadsController@disable'));
    Route::get('event360-leads/enable/{id}', array("as" => "event360_leads.enable", "uses" => 'Event360LeadsController@enable'));
    Route::post('event360-leads/export-to-excel', array("as" => "event360_leads.export_to_excel", "uses" => 'Event360LeadsController@exportToExcel'));

    // designations
    Route::get('designations/disable/{id}', array("as" => "designations.disable", "uses" => 'DesignationsController@disable'));
    Route::get('designations/enable/{id}', array("as" => "designations.enable", "uses" => 'DesignationsController@enable'));
    Route::get('designations', array("as" => "designations.index", "uses" => 'DesignationsController@index'));
    Route::get('designations/create', array("as" => "designations.create", "uses" => 'DesignationsController@create'));
    Route::post('designations', array("as" => "designations.store", "uses" => 'DesignationsController@store'));
    Route::get('designations/{designations}', array("as" => "designations.show", "uses" => 'DesignationsController@show'));
    Route::get('designations/{designations}/edit', array("as" => "designations.edit", "uses" => 'DesignationsController@edit'));
    Route::put('designations/{designations}', array("as" => "designations.update", "uses" => 'DesignationsController@update'));
    Route::patch('designations/{designations}', array("uses" => 'DesignationsController@update'));
    Route::delete('designations/{designations}', array("as" => "designations.destroy", "uses" => 'DesignationsController@update'));

    // employees
    Route::get('employees/ajax/load-employees-list', array("as" => "employees.ajax.load_employees_list", "uses" => 'EmployeesController@ajaxLoadEmployeesList'));
    Route::post('employees/ajax/save-employee', array("as" => "employees.ajax.save_employee", "uses" => "EmployeesController@ajaxSaveEmployee"));
    Route::get('employees/ajax/get-employees-list', array("as" => "employees.ajax.get.employees.list", "uses" => 'EmployeesController@ajaxGetEmployeesList'));
    //Route::post('employees/ajax/update-password', array("as" => "employees.ajax.update_password", "uses" => "EmployeesController@ajaxUpdatePassword"));
    Route::get('employees/my-profile', array("as" => "employees.my_profile", "uses" => 'EmployeesController@myProfile'));
    Route::post('employees/ajax/save-profile-changes', array("as" => "employees.ajax.save_profile_changes", "uses" => "EmployeesController@ajaxSaveProfileChanges"));
    //Route::post('employees/ajax/profile-image-change', array("as" => "employees.ajax.profile_image.change", "uses" => 'EmployeesController@ajaxProfileImageChange'));
    Route::post('employees/ajax/update-email', array("as" => "employees.ajax.update_email", "uses" => "EmployeesController@ajaxUpdateEmail"));
    //Route::post('employees/ajax/delete-profile-image', array("as" => "employees.ajax.delete.profile_image", "uses" => 'EmployeesController@ajaxDeleteProfileImage'));
    Route::post('employees/ajax/email/signature-change', array("as" => "employees.ajax.email.signature.change", "uses" => 'EmployeesController@ajaxSignatureChange'));
    //Route::post('employees/ajax/email/signature-image-change', array("as" => "employees.ajax.email.signature.image.change", "uses" => 'EmployeesController@ajaxSignatureImageChange'));
    //Route::post('employees/ajax/email/delete-signature-image', array("as" => "employees.ajax.email.delete.signature_image", "uses" => 'EmployeesController@ajaxDeleteSignatureImage'));
    Route::post('employees/ajax/update-employee-notifications', array("as" => "employees.ajax_update_employee_notifications", "uses" => 'EmployeesController@ajaxUpdateEmployeeNotifications'));
    Route::get('employees/ajax-update-layout-preference', array("as" => "employees.ajax_update_layout_preference", "uses" => 'EmployeesController@ajaxUpdateLayoutPreference'));
    Route::get('employees/ajax-update-guided-tour-status', array("as" => "employees.ajax_update_guided_tour_status", "uses" => 'EmployeesController@ajaxUpdateGuidedTourStatus'));

    // employee assignment
    Route::post('teams/employee-assignment', array("as" => "team.employee.assignment", "uses" => 'TeamsController@teamEmployeeAssignments'));
    Route::get('teams/disable/{id}', array("as" => "teams.disable", "uses" => 'TeamsController@disable'));
    Route::get('teams/enable/{id}', array("as" => "teams.enable", "uses" => 'TeamsController@enable'));
    Route::get('teams', array("as" => "teams.index", "uses" => 'TeamsController@index'));
    Route::get('teams/create', array("as" => "teams.create", "uses" => 'TeamsController@create'));
    Route::post('teams', array("as" => "teams.store", "uses" => 'TeamsController@store'));
    Route::get('teams/{teams}', array("as" => "teams.show", "uses" => 'TeamsController@show'));
    Route::get('teams/{teams}/edit', array("as" => "teams.edit", "uses" => 'TeamsController@edit'));
    Route::put('teams/{teams}', array("as" => "teams.update", "uses" => 'TeamsController@update'));
    Route::patch('teams/{teams}', array("uses" => 'TeamsController@update'));
    Route::delete('teams/{teams}', array("as" => "teams.destroy", "uses" => 'TeamsController@update'));
    Route::get('teams/ajax/teams', array("as" => "teams.ajax.teams", "uses" => 'TeamsController@ajaxReturnTeamsList'));
    Route::get('teams/team_list/team_overview', array("as" => "teams.team_overview", "uses" => 'TeamsController@getTeamOverview'));

    // team assignments
    Route::get('team-assignments', array("as" => "team_assignments.index", "uses" => 'TeamAssignmentsController@index'));
    Route::get('team-assignments/create', array("as" => "team_assignments.create", "uses" => 'TeamAssignmentsController@create'));
    Route::post('team-assignments', array("as" => "team_assignments.store", "uses" => 'TeamAssignmentsController@store'));
    Route::get('team-assignments/{team_assignments}', array("as" => "team_assignments.show", "uses" => 'TeamAssignmentsController@show'));
    Route::get('team-assignments/{team_assignments}/edit', array("as" => "team_assignments.edit", "uses" => 'TeamAssignmentsController@edit'));
    Route::put('team-assignments/{team_assignments}', array("as" => "team_assignments.update", "uses" => 'TeamAssignmentsController@update'));
    Route::patch('team-assignments/{team_assignments}', array("uses" => 'TeamAssignmentsController@update'));
    Route::delete('team-assignments/{team_assignments}', array("as" => "team_assignments.destroy", "uses" => 'TeamAssignmentsController@update'));

    // industries
    Route::get('industries/disable/{id}', array("as" => "industries.disable", "uses" => 'IndustriesController@disable'));
    Route::get('industries/enable/{id}', array("as" => "industries.enable", "uses" => 'IndustriesController@enable'));
    Route::get('industries', array("as" => "industries.index", "uses" => 'IndustriesController@index'));
    Route::get('industries/create', array("as" => "industries.create", "uses" => 'IndustriesController@create'));
    Route::post('industries', array("as" => "industries.store", "uses" => 'IndustriesController@store'));
    Route::get('industries/{industries}', array("as" => "industries.show", "uses" => 'IndustriesController@show'));
    Route::get('industries/{industries}/edit', array("as" => "industries.edit", "uses" => 'IndustriesController@edit'));
    Route::put('industries/{industries}', array("as" => "industries.update", "uses" => 'IndustriesController@update'));
    Route::patch('industries/{industries}', array("uses" => 'IndustriesController@update'));
    Route::delete('industries/{industries}', array("as" => "industries.destroy", "uses" => 'IndustriesController@update'));
    Route::get('industries/ajax/industries', array("as" => "industries.ajax.industries", "uses" => 'IndustriesController@ajaxLoadIndustries'));

    // screen permissions
    Route::get('permissions-screen', array("as" => "permissions.index", "uses" => 'PermissionsController@index'));
    Route::post('permissions/ajax/update-employee-screen-permissions', array("as" => "permissions.ajax.update_employee_screen_permissions", "uses" => 'PermissionsController@ajaxUpdateEmployeeScreenPermissions'));
    Route::post('permissions/ajax/update-user-level-screen-permissions', array("as" => "permissions.ajax.update_user_level_screen_permissions", "uses" => 'PermissionsController@ajaxUpdateUserLevelScreenPermissions'));

    /*
     * Third Party API Integration
     * */
    Route::get('third-party-api/delacon/report', array("as" => "third_party_api.delacon.report", "uses" => 'DelaconCallTrackingReportController@viewReport'));


    // http://webtics-product-staging.appspot.com/third-party-api/delacon/save-to-the-database/

    /*
     * Event 360 Specific Routes
     */
    // vendor profile

    Route::put('event360-vendor-profile/overview/{id}', array("as" => "event360.vendor_profile.overview.edit", "uses" => 'Event360VendorProfileController@editOverview'));
    Route::post('event360-vendor-profile/profile/add', array("as" => "event360.vendor_profile.profile.add", "uses" => 'Event360VendorProfileController@addProfile'));
    Route::put('event360-vendor-profile/profile/{id}', array("as" => "event360.vendor_profile.profile.edit", "uses" => 'Event360VendorProfileController@editProfile'));

    // images
    Route::post('event360-vendor-profile/image/upload', array("as" => "event360.vendor_profile.image.upload", "uses" => 'Event360VendorProfileController@uploadImage'));
    Route::post('event360-vendor-profile/image/delete', array("as" => "event360.vendor_profile.image.delete", "uses" => 'Event360VendorProfileController@deleteImage'));

    // testimonials
    Route::post('event360-vendor-profile/testimonial/add', array("as" => "event360.vendor_profile.testimonial.add", "uses" => 'Event360VendorProfileController@addTestimonial'));
    Route::put('event360-vendor-profile/testimonials/{id}', array("as" => "event360.vendor_profile.testimonial.update", "uses" => 'Event360VendorProfileController@updateTestimonial'));

    // services
    Route::post('event360-vendor-profile/services/add', array("as" => "event360.vendor_profile.services.add", "uses" => 'Event360VendorProfileController@addService'));
    Route::put('event360-vendor-profile/services/{id}', array("as" => "event360.vendor_profile.services.update", "uses" => 'Event360VendorProfileController@updateService'));

    // ad banners
    Route::post('event360-vendor-profile/ad-banner/add', array("as" => "event360.vendor_profile.ad_banner.add", "uses" => 'Event360VendorProfileController@addAdBanner'));
    Route::post('event360-vendor-profile/ad-banner/{id}', array("as" => "event360.vendor_profile.ad_banner.update", "uses" => 'Event360VendorProfileController@updateAdBanner'));
    Route::get('event360-vendor-profile-ad-banners/disable/{id}', array("as" => "event360_vendor_profile_ad_banners.disable", "uses" => 'Event360VendorProfileController@disableAdBanner'));
    Route::get('event360-vendor-profile-ad-banners/enable/{id}', array("as" => "event360_vendor_profile_ad_banners.enable", "uses" => 'Event360VendorProfileController@enableAdBanner'));

    // event360_enquiry_lead_quotes
    Route::post('event360-enquiry-lead-quote/ajax/save-quotation', array("as" => "event360_enquiry_lead_quote.ajax.save_quotation", "uses" => 'Event360EnquiryLeadQuoteController@saveQuotation'));
    Route::get('event360-enquiry-lead-quote/ajax/load-previous-quotations', array("as" => "event360_enquiry_lead_quote.ajax.get.load_previous_quotations", "uses" => 'Event360EnquiryLeadQuoteController@loadPreviousQuotations'));


    /*
     * Web360 Routes
     */

    //Web360 Leads Summary
    Route::get('leads-detail', array("as" => "web360_leads_detail.index", "uses" => 'Web360LeadsDetailController@index'));
    Route::get('leads-detail/ajax/get/event360-website-form-submissions', array("as" => "web360_leads_detail.ajax.get.event360_website_form_submissions", "uses" => 'Web360LeadsDetailController@getAjaxEvent360WebsiteFormSubmission'));


    //my activities
    Route::get('my-activities', array("as" => "my_activities.index", "uses" => "MyActivitiesController@index"));
    Route::get('my-activities/ajax/get-add-new-call-form', array("as" => "my_activities.ajax.get_add_new_call_form", "uses" => "MyActivitiesController@ajaxGetAddNewCallForm"));
    Route::get('my-activities/ajax/get-edit-call-form', array("as" => "my_activities.ajax.get_edit_call_form", "uses" => "MyActivitiesController@ajaxGetEditCallForm"));
    Route::get('my-activities/ajax/load-calls-list', array("as" => "my_activities.ajax.load_calls_list", "uses" => "MyActivitiesController@ajaxLoadCallsList"));
    Route::post('my-activities/ajax/save-new-call', array("as" => "my_activities.ajax.save_new_call", "uses" => "MyActivitiesController@ajaxSaveNewCall"));
    Route::post('my-activities/ajax/update-call', array("as" => "my_activities.ajax.update_call", "uses" => "MyActivitiesController@ajaxUpdateCall"));
    Route::get('my-activities/ajax/get-add-new-meeting-form', array("as" => "my_activities.ajax.get_add_new_meeting_form", "uses" => "MyActivitiesController@ajaxGetAddNewMeetingForm"));
    Route::get('my-activities/ajax/get-edit-meeting-form', array("as" => "my_activities.ajax.get_edit_meeting_form", "uses" => "MyActivitiesController@ajaxGetEditMeetingForm"));
    Route::get('my-activities/ajax/load-meetings-list', array("as" => "my_activities.ajax.load_meetings_list", "uses" => "MyActivitiesController@ajaxLoadMeetingsList"));
    Route::post('my-activities/ajax/save-new-meeting', array("as" => "my_activities.ajax.save_new_meeting", "uses" => "MyActivitiesController@ajaxSaveNewMeeting"));
    Route::post('my-activities/ajax/update-meeting', array("as" => "my_activities.ajax.update_meeting", "uses" => "MyActivitiesController@ajaxUpdateMeeting"));
    Route::get('my-activities/ajax/get-add-new-email-form', array("as" => "my_activities.ajax.get_add_new_email_form", "uses" => "MyActivitiesController@ajaxGetAddNewEmailForm"));
    Route::get('my-activities/ajax/get-edit-email-form', array("as" => "my_activities.ajax.get_edit_email_form", "uses" => "MyActivitiesController@ajaxGetEditEmailForm"));
    Route::get('my-activities/ajax/load-emails-list', array("as" => "my_activities.ajax.load_emails_list", "uses" => "MyActivitiesController@ajaxLoadEmailsList"));
    Route::post('my-activities/ajax/save-new-email', array("as" => "my_activities.ajax.save_new_email", "uses" => "MyActivitiesController@ajaxSaveNewEmail"));
    Route::post('my-activities/ajax/update-email', array("as" => "my_activities.ajax.update_email", "uses" => "MyActivitiesController@ajaxUpdateEmail"));
    Route::post('my-activities/add-activity-to-my-calender', array("as" => "my_activities.add_activity_to_my_calender", "uses" => "MyActivitiesController@addActivityToMyCalender"));
    Route::post('my-activities/ajax/remove-email-attachment', array("as" => "my_activities.remove_email_attachment", "uses" => "MyActivitiesController@removeEmailAttachment"));


    //Data Export
    Route::post('data-export', array("as" => "data_export.ajax.export", "uses" => 'DataExportRequestController@export'));
    Route::post('data-export/save/data-export-request', array("as" => "data_export.save.data_export_request", "uses" => 'DataExportRequestController@saveDataExportRequest'));
    Route::get('data-export/ajax/load-data-export-list', array("as" => "data_export.tasks.ajax.load_data_export_list", "uses" => 'DataExportRequestController@ajaxLoadDataExportList'));

    //Tasks
    Route::get('tasks', array("as" => "tasks.index", "uses" => "TasksController@index"));
    Route::get('tasks/ajax/load-tasks-list', array("as" => "tasks.ajax.load_tasks_list", "uses" => "TasksController@ajaxLoadTaskList"));
    Route::post('tasks/ajax/add-task', array("as" => "tasks.ajax.add_task", "uses" => "TasksController@ajaxAddTask"));
    Route::post('tasks/ajax/update-task', array("as" => "tasks.ajax.update_task", "uses" => "TasksController@ajaxUpdateTask"));
    Route::post('tasks/ajax/update-mark-as-done-status', array("as" => "tasks.ajax.delete_task", "uses" => "TasksController@ajaxUpdateMarkAsDoneStatus"));
    Route::get('tasks/ajax/get-all-tasks-for-calender', array("as" => "tasks.ajax.get_all_tasks_for_calander", "uses" => "TasksController@ajaxGetAllTasksForCalender"));
    Route::post('tasks/ajax/update-task-date-time', array("as" => "tasks.ajax.update_task_date_time", "uses" => "TasksController@ajaxUpdateTaskDateTime"));
    Route::get('tasks/ajax/load-update-task-form', array("as" => "tasks.ajax.load_update_task_form", "uses" => "TasksController@ajaxLoadUpdateTaskForm"));

    //Landing Pages
    Route::get('landing-pages', array("as" => "landing_pages.index", "uses" => "LandingPagesController@index"));
    Route::get('landing-pages/ajax/load-landing-pages-list', array("as" => "landing_pages.ajax.load_landing_pages_list", "uses" => "LandingPagesController@ajaxLoadLandingPagesList"));
    Route::post('landing-pages/ajax/add-landing-page', array("as" => "landing_pages.ajax.add_landing_page", "uses" => "LandingPagesController@ajaxAddLandingPage"));
    Route::post('landing-pages/ajax/update-landing-page-details', array("as" => "landing_pages.ajax.update_landing_page_details", "uses" => "LandingPagesController@ajaxUpdateLandingPageDetails"));
    Route::get('landing-pages/show-landing-page-builder', array("as" => "landing_pages.ajax.show_landing_page_builder", "uses" => "LandingPagesController@ajaxShowLandingPageBuilder"));
    Route::get('landing-pages/ajax/get-landing-page-html/{landing_page_id}', array("as" => "landing_pages.ajax.get_landing_page_html", "uses" => "LandingPagesController@ajaxGetLandingPageHTML"));
    Route::post('landing-pages/ajax/save-landing-page-html/{landing_page_id}', array("as" => "landing_pages.ajax.save_landing_page_html", "uses" => "LandingPagesController@ajaxSaveLandingPageHTML"));

    // Email Module
    Route::get('email-module', array("as" => "email_module.index", "uses" => "EmailModuleController@index"));
    Route::get('email-module/ajax/load-email-campaigns-list', array("as" => "email_module.ajax.load_email_campaigns_list", "uses" => "EmailModuleController@ajaxLoadEmailCampaignsList"));
    Route::get('email-module/email-campaign-management-screen', array("as" => "email_module.email_campaign_management_screen", "uses" => "EmailModuleController@emailCampaignManagementScreen"));
    Route::get('email-module/ajax/search-and-load-contacts-list', array("as" => "email_module.ajax.search_and_load_contacts_list", "uses" => "EmailModuleController@ajaxSearchAndLoadContactsList"));
    Route::get('email-module/ajax/load-selected-contacts-list', array("as" => "email_module.ajax.load_selected_contacts_list", "uses" => "EmailModuleController@ajaxLoadSelectedContactsList"));
    Route::get('email-module/ajax/load-email-lists', array("as" => "email_module.ajax.load_email_lists", "uses" => "EmailModuleController@ajaxLoadEmailLists"));
    Route::post('email-module/ajax/save-new-email-campaign', array("as" => "email_module.ajax.save_new_email_campaign", "uses" => "EmailModuleController@ajaxSaveNewEmailCampaign"));
    Route::post('email-module/ajax/save-new-email-list', array("as" => "email_module.ajax.save_new_email_list", "uses" => "EmailModuleController@ajaxSaveNewEmailList"));
    Route::post('email-module/ajax/edit-email-list', array("as" => "email_module.ajax.edit_email_list", "uses" => "EmailModuleController@ajaxEditEmailList"));
    Route::get('email-module/ajax/load-email-content_preview', array("as" => "email_module.ajax.load_email_content_preview", "uses" => "EmailModuleController@ajaxLoadEmailContentPreview"));
    Route::post('email-module/ajax/edit-email-content', array("as" => "email_module.ajax.edit_email_content", "uses" => "EmailModuleController@ajaxEditEmailContent"));

    //Payment
    Route::get('payment-management', array("as" => "payment_management.index", "uses" => "PaymentManagementController@index"));
    Route::get('payment-management/ajax/load-payment-methods-list', array("as" => "payment_management.ajax.load_payment_methods_list", "uses" => "PaymentManagementController@ajaxLoadPaymentMethodsList"));
    Route::post('payment-management/ajax/save-new-payment-method', array("as" => "payment_management.ajax.save_new_payment_method", "uses" => "PaymentManagementController@ajaxSaveNewPaymentMethod"));
    Route::post('payment-management/ajax/update-payment-method', array("as" => "payment_management.ajax.update_payment_method", "uses" => "PaymentManagementController@ajaxUpdatePaymentMethod"));
    Route::get('payment-management/ajax/load-modules-list', array("as" => "payment_management.ajax.load_modules_list", "uses" => "PaymentManagementController@ajaxLoadModulesList"));
    Route::get('payment-management/ajax/update-module-organization-assignment', array("as" => "payment_management.ajax.update_module_organization_assignment", "uses" => "PaymentManagementController@ajaxUpdateModuleOrganizationAssignment"));
    Route::get('payment-management/ajax/load-module-employees-list', array("as" => "payment_management.ajax.load_module_employees_list", "uses" => "PaymentManagementController@ajaxLoadModuleEmployeesList"));
    Route::get('payment-management/ajax/assign-employee-to-module', array("as" => "payment_management.ajax.assign_employee_to_module", "uses" => "PaymentManagementController@ajaxAssignEmployeeToModule"));
    Route::get('payment-management/ajax/load-payment-history-list', array("as" => "payment_management.ajax.load_payment_history_list", "uses" => "PaymentManagementController@ajaxLoadPaymentHistoryList"));

    //Countries
    Route::get('countries/ajax/countries', array("as" => "countries.ajax.countries", "uses" => 'CountriesController@ajaxLoadCountries'));


    //Test Google Natural Language Content Classification
    Route::get('test/content/classification', array("as" => "test.content.classification", "uses" => 'TestController@getContentClassification'));

    //V1 links
    Route::get('home-quick-access', array("as" => "home_quick_access", "uses" => 'V1Controller@homeQuickAccess'));
    Route::get('event360-vendor-profile', array("as" => "event360.vendor_profile.index", "uses" => 'V1Controller@index'));
    Route::get('event360-vendor-profile/images/list', array("as" => "event360.vendor_profile.image.list", "uses" => 'V1Controller@retrieveImages'));
    Route::get('event360-vendor-profile/ajax/get/testimonials', array("as" => "event360_vendor_profile.ajax.get.testimonials", "uses" => 'V1Controller@getAjaxTestimonials'));
    Route::get('event360-vendor-profile/ajax/get/services', array("as" => "event360_vendor_profile.ajax.get.services", "uses" => 'V1Controller@getAjaxServices'));
    Route::get('event360-vendor-profile/ajax/get/ad-banners', array("as" => "event360_vendor_profile.ajax.get.ad_banners", "uses" => 'V1Controller@getAjaxAdBanners'));
    Route::get('web360-pixel-report', array("as" => "web360_pixel_report.index", "uses" => 'V1Controller@web360PixelReport'));
    Route::get('event360-leads', array("as" => "event360_leads.index", "uses" => 'V1Controller@leadsManagement'));
    Route::get('event360-leads/ajax/get/leads', array("as" => "event360_leads.ajax.get.leads", "uses" => 'V1Controller@getAjaxLeadsList'));
    Route::get('event360-leads/ajax/forwards/table', array("as" => "event360_leads.ajax.forward.table", "uses" => 'V1Controller@loadAjaxLeadForwardTable'));
    Route::get('event360-leads/ajax/notes/table', array("as" => "event360_leads.ajax.notes.table", "uses" => 'V1Controller@loadAjaxNotesTable'));
    Route::get('event360-leads/ajax/get/event360_messenger_thread_messages', array("as" => "event360_leads.ajax.get.event360_messenger_thread_messages", "uses" => 'V1Controller@loadEvent360MessengerThreadMessages'));
    Route::get('event360-leads/ajax/messages/table', array("as" => "event360_leads.ajax.messages.table", "uses" => 'V1Controller@loadMessagesTable'));
    Route::get('event360-leads/ajax/get/non-advertising-event360-leads-widget', array("as" => "event360_leads.ajax.get.non.advertising.event360.leads.widget", "uses" => 'V1Controller@getAjaxNonAdvertisingEvent360LeadsWidget'));
    Route::get('event360-leads/get-event360-enquiry-lead-management-screen/{id}', array("as" => "event360_leads.get_event360_enquiry_lead_management_screen", "uses" => 'V1Controller@getEvent360EnquiryLeadManagementScreen'));
    Route::get('event360-leads/get-event360-messages-lead-management-screen/{id}', array("as" => "event360_leads.get_event360_messages_screen", "uses" => 'V1Controller@getEvent360MessagesLeadManagementScreen'));
    Route::get('event360-pixel-report', array("as" => "event360_pixel_report.index", "uses" => 'V1Controller@event360Report'));

    //  audit trails
    Route::get('audit-trails', array("as" => "audit_trails.index", "uses" => 'AuditTrailsController@index'));
    Route::get('audit-trails/ajax/load-audit-trails-list', array("as" => "audit_trails.ajax.load_audit_trails_list", "uses" => "AuditTrailsController@ajaxLoadAuditTrailsList"));

    // removed from login, needed by event360 control panel

    Route::get('web360-pixel-report/get-roi-metric-report', array("as" => "web360_pixel_report.get_roi_metric_report", "uses" => 'V1Controller@getROIMetricReport'));
    Route::get('web360-pixel-report/get-engagement-metric-report', array("as" => "web360_pixel_report.get_engagement_metric_report", "uses" => 'V1Controller@getEngagementMetricReport'));
    Route::get('event360-pixel-report/get-engagement-metric-report', array("as" => "event360_pixel_report.get_engagement_metric_report", "uses" => 'V1Controller@getEvent360EngagementMetricReport'));
    Route::get('event360-pixel-report/get-roi-metric-report', array("as" => "event360_pixel_report.get_roi_metric_report", "uses" => 'V1Controller@getEvent360ROIMetricReport'));

    // web360 pixel report
    Route::get('web360-pixel-report/get-visits-breakdown-by-medium', array("as" => "web360_pixel_report.get_visits_breakdown_by_medium", "uses" => 'Web360PixelReportController@getVisitsBreakDownByMedium'));
    Route::get('web360-pixel-report/get-leads-breakdown-by-medium', array("as" => "web360_pixel_report.get_leads_breakdown_by_medium", "uses" => 'Web360PixelReportController@getLeadsBreakdownByMedium'));
    Route::get('web360-pixel-report/get-lead-conversion-rate-by-medium', array("as" => "web360_pixel_report.get_lead_conversion_rate_by_medium", "uses" => 'Web360PixelReportController@getLeadConversionRateByMedium'));
    Route::get('web360-pixel-report/get-web360-latency-by-medium', array("as" => "web360_pixel_report.get_web360_latency_by_medium", "uses" => 'Web360PixelReportController@getWeb360LatencyByMedium'));


    // event360 pixel report
    Route::get('event360-pixel-report/get-leads-conversion-rate-comparison', array("as" => "event360_pixel_report.get_leads_conversion_rate_comparison", "uses" => 'Event360PixelReportController@getLeadsConversionRateComparison'));
    Route::get('event360-pixel-report/get-lead-volume-comparison', array("as" => "event360_pixel_report.get_lead_volume_comparison", "uses" => 'Event360PixelReportController@getLeadVolumeComparison'));
    Route::get('event360-pixel-report/get-lead-breakdown-by-type', array("as" => "event360_pixel_report.get_lead_breakdown_by_type", "uses" => 'Event360PixelReportController@getLeadBreakdownByType'));
    Route::get('event360-pixel-report/get-leads-breakdown-by-lead-rating', array("as" => "event360_pixel_report.get_leads_breakdown_by_lead_rating", "uses" => 'Event360PixelReportController@getLeadsBreakdownByLeadRating'));



    //Dashboard Widgets
    Route::get('event360-leads/ajax/homepage-widget-leads-weekly', array("as" => "event360_leads.ajax.homepage_widget_website_weekly", "uses" => 'Event360LeadsController@homePageWidgetLeadsByWeekChart'));

    // quotations routes for customer
    Route::get('quotations/update-status-by-customer', array("as" => "quotations.update_status_by_customer", "uses" => "QuotationsController@updateQuotationStatusByCustomer"));

    // queued tasks
    Route::post('tasks/send-task-reminder-from-task-queue', array("as" => "tasks.send_task_reminder_from_task_queue", "uses" => "TasksController@sendTaskReminderFromTaskQueue"));
});



/*
 * ------------------
 * Rest API functions
 * ------------------
 */

// web360 rest api to save web360enquiries
Route::post('web360-rest-api/save-web360-enquiries/email-replace-form', array("as" => "web360_rest_api.save_web360_enquiries.email_replace_form", "uses" => 'RestApiController@saveWeb360EnquiriesEmailReplaceForm'));
Route::post('web360-rest-api/save-web360-enquiries/website-form-submission', array("as" => "web360_rest_api.save_web360_enquiries.website_form_submission", "uses" => 'RestApiController@saveWeb360EnquiriesWebsiteFormSubmission'));


/**
 * Mobile API Functions
 */

/*
 * ------------------------------
 * Event 360 Mobile API functions
 * ------------------------------
 */

// // login
// Route::post('event360-mobile-api/login', array("as" => "event360_mobile_api.login", "uses" => 'MobileRestApiController@login'));

// // get website leads list
// Route::post('event360-mobile-api/get-website-leads-list', array("as" => "event360_mobile_api.website_leads_list", "uses" => 'MobileRestApiController@getWebSiteLeadsList'));

// // get Lead Rating options
// Route::post('event360-mobile-api/get-lead-rating-options', array("as" => "event360_mobile_api.get_lead_rating_options", "uses" => 'MobileRestApiController@getLeadRatingOptions'));

// // update lead rating
// Route::post('event360-mobile-api/update-lead-rating', array("as" => "event360_mobile_api.update_lead_rating", "uses" => 'MobileRestApiController@updateLeadRating'));

// // add lead note
// Route::post('event360-mobile-api/add-lead-note', array("as" => "event360_mobile_api.add_lead_note", "uses" => 'MobileRestApiController@addLeadNote'));

// // get lead notes list
// Route::post('event360-mobile-api/get-lead-notes-list', array("as" => "event360_mobile_api.get_lead_notes_list", "uses" => 'MobileRestApiController@getLeadNotesList'));

// // share lead
// Route::post('event360-mobile-api/share-lead', array("as" => "event360_mobile_api.share_lead", "uses" => 'MobileRestApiController@shareLead'));

// // get share leads list
// Route::post('event360-mobile-api/get-share-leads-list', array("as" => "event360_mobile_api.get_share_leads_list", "uses" => 'MobileRestApiController@getShareLeadsList'));

// // get event360 enquiry leads list
// Route::post('event360-mobile-api/get-event360-enquiry-leads-list', array("as" => "event360_mobile_api.get_event360_enquiry_leads_list", "uses" => 'MobileRestApiController@getEvent360EnquiryLeadsList'));

// // get event360 enquiry lead details
// Route::post('event360-mobile-api/get-event360-enquiry-lead-details', array("as" => "event360_mobile_api.get_event360_enquiry_lead_details", "uses" => 'MobileRestApiController@getEvent360EnquiryLeadDetails'));

// // save lead quotation
// Route::post('event360-mobile-api/save-lead-quotation', array("as" => "event360_mobile_api.save_lead_quotation", "uses" => 'MobileRestApiController@saveLeadQuotation'));

// // get call leads
// Route::post('event360-mobile-api/get-call-leads', array("as" => "event360_mobile_api.get_call_leads", "uses" => 'MobileRestApiController@getCallLeads'));

// // get message leads
// Route::post('event360-mobile-api/get-message-leads', array("as" => "event360_mobile_api.get_message_leads", "uses" => 'MobileRestApiController@getMessageLeads'));

// // send message
// Route::post('event360-mobile-api/send-message', array("as" => "event360_mobile_api.send_message", "uses" => 'MobileRestApiController@sendMessage'));

// // get your website leads
// Route::post('event360-mobile-api/get-widget-your-website-leads', array("as" => "event360_mobile_api.get_widget_your_website_leads", "uses" => 'MobileRestApiController@getWidgetYourWebsiteLeads'));

// // get your event360 leads
// Route::post('event360-mobile-api/get-widget-your-event360-leads', array("as" => "event360_mobile_api.get_widget_your_event360_leads", "uses" => 'MobileRestApiController@getWidgetYourEvent360Leads'));

// // get your event360 message leads
// Route::post('event360-mobile-api/get-widget-your-event360-message-leads', array("as" => "event360_mobile_api.get_widget_your_event360_message_leads", "uses" => 'MobileRestApiController@getWidgetYourEvent360Messages'));

// // get widget web360 engagement report
// Route::post('event360-mobile-api/get-widget-web360-engagement-report', array("as" => "event360_mobile_api.get_widget_web360_engagement_report", "uses" => 'MobileRestApiController@getWidgetWeb360EngagementReport'));

// // get widget web360 roi metric report
// Route::post('event360-mobile-api/get-widget-web360-roi-metric-report', array("as" => "event360_mobile_api.get_widget_web360_roi_metric_report", "uses" => 'MobileRestApiController@getWidgetWeb360ROIMetricReport'));

// // get widget web360 visits break down by medium
// Route::post('event360-mobile-api/get-widget-web360-visits-break-down-by-medium', array("as" => "event360_mobile_api.get_widget_web360_visits_break_down_by_medium", "uses" => 'MobileRestApiController@getWidgetWeb360VisitsBreakDownByMedium'));

// // get widget web360 leads break down by medium
// Route::post('event360-mobile-api/get-widget-web360-leads-break-down-by-medium', array("as" => "event360_mobile_api.get_widget_web360_leads_break_down_by_medium", "uses" => 'MobileRestApiController@getWidgetWeb360LeadsBreakdownByMedium'));

// // get widget web360 lead conversion rate by medium
// Route::post('event360-mobile-api/get-widget-web360-lead-conversion-rate-by-medium', array("as" => "event360_mobile_api.get_widget_web360_lead_conversion_rate_by_medium", "uses" => 'MobileRestApiController@getWidgetWeb360LeadConversionRateByMedium'));

// // get widget web360 latency by medium
// Route::post('event360-mobile-api/get-widget-web360-latency-by-medium', array("as" => "event360_mobile_api.get_widget_web360_latency_by_medium", "uses" => 'MobileRestApiController@getWidgetWeb360LatencyByMedium'));

// // get widget event360 engagement metric data
// Route::post('event360-mobile-api/get-widget-event360-engagement-metric-data', array("as" => "event360_mobile_api.get_widget_event360_engagement_metric_data", "uses" => 'MobileRestApiController@getWidgetEvent360EngagementMetricData'));

// // get widget event360 roi metric data
// Route::post('event360-mobile-api/get-widget-event360-roi-metric-data', array("as" => "event360_mobile_api.get_widget_event360_roi_metric_data", "uses" => 'MobileRestApiController@getWidgetEvent360ROIMetricReport'));

// // get widget event360 lead volume comparison data
// Route::post('event360-mobile-api/get-widget-event360-lead-volume-comparison-data', array("as" => "event360_mobile_api.get_widget_event360_lead_volume_comparison_data", "uses" => 'MobileRestApiController@getWidgetEvent360LeadVolumeComparisonData'));

// // get widget event360 lead breakdown by type
// Route::post('event360-mobile-api/get-widget-event360-lead-breakdown-by-type', array("as" => "event360_mobile_api.get_widget_event360_lead_breakdown_by_type", "uses" => 'MobileRestApiController@getWidgetEvent360LeadBreakdownByType'));

// // get widget event360 leads breakdown by lead rating
// Route::post('event360-mobile-api/get-widget-event360-leads-breakdown-by-lead-rating', array("as" => "event360_mobile_api.get_widget_event360_leads_breakdown_by_lead_rating", "uses" => 'MobileRestApiController@getWidgetEvent360LeadsBreakdownByLeadRating'));



/*
 * ------------------------------
 * Web 360 V2 Mobile API functions
 * ------------------------------
 */

// // login user
// Route::post('mobile-api/v2/login', array("as" => "mobile_api.v2.login", "uses" => 'MobileRestApiV2Controller@login'));

// // forgot password
// Route::post('mobile-api/v2/forgot-password', array("as" => "mobile_api.v2.forgot_password", "uses" => 'MobileRestApiV2Controller@forgotPassword'));

// // get organization employees list
// Route::post('mobile-api/v2/get-organization-employees-list', array("as" => "mobile_api.v2.get_organization_employees_list", "uses" => 'MobileRestApiV2Controller@getOrganizationEmployeesList'));

// // get leads list
// Route::post('mobile-api/v2/get-leads-list', array("as" => "mobile_api.v2.get_leads_list", "uses" => 'MobileRestApiV2Controller@getLeadsList'));

// // get campaigns list
// Route::post('mobile-api/v2/get-campaigns-list', array("as" => "mobile_api.v2.get_campaigns_list", "uses" => 'MobileRestApiV2Controller@getCampaignsList'));

// // get lead details
// Route::post('mobile-api/v2/get-lead-details', array("as" => "mobile_api.v2.get_lead_details", "uses" => 'MobileRestApiV2Controller@getLeadDetails'));

// // update lead details
// Route::post('mobile-api/v2/update-lead-details', array("as" => "mobile_api.v2.update_lead_details", "uses" => 'MobileRestApiV2Controller@updateLeadDetails'));

// // update lead campaign
// Route::post('mobile-api/v2/update-lead-campaign', array("as" => "mobile_api.v2.update_lead_campaign", "uses" => 'MobileRestApiV2Controller@updateLeadCampaign'));

// // update lead assigned to
// Route::post('mobile-api/v2/update-lead-assigned-to', array("as" => "mobile_api.v2.update_lead_assigned_to", "uses" => 'MobileRestApiV2Controller@updateLeadAssignedTo'));

// // update lead rating
// Route::post('mobile-api/v2/update-lead-rating', array("as" => "mobile_api.v2.update_lead_rating", "uses" => 'MobileRestApiV2Controller@updateLeadRating'));

// // get lead notes list
// Route::post('mobile-api/v2/get-lead-notes-list', array("as" => "mobile_api.v2.get_lead_notes_list", "uses" => 'MobileRestApiV2Controller@getLeadNotesList'));

// // save lead note
// Route::post('mobile-api/v2/save-lead-note', array("as" => "mobile_api.v2.save_lead_note", "uses" => 'MobileRestApiV2Controller@saveLeadNote'));

// // get customer contacts list
// Route::post('mobile-api/v2/get-customer-contacts-list', array("as" => "mobile_api.v2.get_customer_contacts_list", "uses" => 'MobileRestApiV2Controller@getCustomerContactsList'));

// // update customer contact details
// Route::post('mobile-api/v2/update-customer-contact-details', array("as" => "mobile_api.v2.update_customer_contact_details", "uses" => 'MobileRestApiV2Controller@updateCustomerContactDetails'));

// // create new customer contact
// Route::post('mobile-api/v2/create-new-customer-contact', array("as" => "mobile_api.v2.create_new_customer_contact", "uses" => 'MobileRestApiV2Controller@createNewCustomerContact'));

// // update customer primary contact
// Route::post('mobile-api/v2/update-customer-primary-contact', array("as" => "mobile_api.v2.update_customer_primary_contact", "uses" => 'MobileRestApiV2Controller@updateCustomerPrimaryContact'));

// // get lead meta details
// Route::post('mobile-api/v2/get-lead-meta-details', array("as" => "mobile_api.v2.get_lead_meta_details", "uses" => 'MobileRestApiV2Controller@getLeadMetaDetails'));

// // get customers list by name
// Route::post('mobile-api/v2/get-customers-list-by-name', array("as" => "mobile_api.v2.get_customers_list_by_name", "uses" => 'MobileRestApiV2Controller@getCustomersListByName'));

// // create and assign new customer for lead
// Route::post('mobile-api/v2/create-and-assign-new-customer-for-lead', array("as" => "mobile_api.v2.create_and_assign_new_customer_for_lead", "uses" => 'MobileRestApiV2Controller@createAndAssignNewCustomerForLead'));

// // update customer for lead
// Route::post('mobile-api/v2/update-customer-for-lead', array("as" => "mobile_api.v2.update_customer_for_lead", "uses" => 'MobileRestApiV2Controller@updateCustomerForLead'));

// // create new lead
// Route::post('mobile-api/v2/create-new-lead', array("as" => "mobile_api.v2.create_new_lead", "uses" => 'MobileRestApiV2Controller@createNewLead'));

// // get customer time line
// Route::post('mobile-api/v2/get-customer-time-line', array("as" => "mobile_api.v2.get_customer_time_line", "uses" => 'MobileRestApiV2Controller@getCustomerTimeLine'));

// // get my sales pipeline data
// Route::post('mobile-api/v2/get-my-sales-pipeline-data', array("as" => "mobile_api.v2.get_my_sales_pipeline_data", "uses" => 'MobileRestApiV2Controller@getMySalesPipelineData'));

// // get my contracted sales data
// Route::post('mobile-api/v2/get-my-contracted-sales-data', array("as" => "mobile_api.v2.get_my_contracted_sales_data", "uses" => 'MobileRestApiV2Controller@getMyContractedSalesData'));

// // get my sales dashboard sales value by campaign
// Route::post('mobile-api/v2/get-my-sales-dashboard-sales-value-by-campaign', array("as" => "mobile_api.v2.get_my_sales_dashboard_sales_value_by_campaign", "uses" => 'MobileRestApiV2Controller@getMySalesDashboardSalesValueByCampaigns'));

// // get my sales dashboard sales volume by campaign
// Route::post('mobile-api/v2/get-my-sales-dashboard-sales-volume-by-campaign', array("as" => "mobile_api.v2.get_my_sales_dashboard_sales_volume_by_campaign", "uses" => 'MobileRestApiV2Controller@getMySalesDashboardSalesVolumeByCampaigns'));

// // get my sales dashboard sales value by sales person
// Route::post('mobile-api/v2/get-my-sales-dashboard-sales-value-by-sales-person', array("as" => "mobile_api.v2.get_my_sales_dashboard_sales_value_by_sales_person", "uses" => 'MobileRestApiV2Controller@getMySalesDashboardSalesValueBySalesPersons'));

// // get my sales dashboard sales volume by sales person
// Route::post('mobile-api/v2/get-my-sales-dashboard-sales-volume-by-sales-person', array("as" => "mobile_api.v2.get_my_sales_dashboard_sales_volume_by_sales_person", "uses" => 'MobileRestApiV2Controller@getMySalesDashboardSalesVolumeBySalesPersons'));

// // get full customer contacts list for picker
// Route::post('mobile-api/v2/get-full-customer-contacts-list-for-picker', array("as" => "mobile_api.v2.get_full_customer_contacts_list_for_picker", "uses" => 'MobileRestApiV2Controller@getFullCustomerContactsListForPicker'));

// // add call
// Route::post('mobile-api/v2/add-call', array("as" => "mobile_api.v2.add_call", "uses" => 'MobileRestApiV2Controller@addCall'));

// // add meeting
// Route::post('mobile-api/v2/add-meeting', array("as" => "mobile_api.v2.add_meeting", "uses" => 'MobileRestApiV2Controller@addMeeting'));

// // add email
// Route::post('mobile-api/v2/add-email', array("as" => "mobile_api.v2.add_email", "uses" => 'MobileRestApiV2Controller@addEmail'));

// // edit call
// Route::post('mobile-api/v2/edit-call', array("as" => "mobile_api.v2.edit_call", "uses" => 'MobileRestApiV2Controller@editCall'));

// // edit meeting
// Route::post('mobile-api/v2/edit-meeting', array("as" => "mobile_api.v2.edit_meeting", "uses" => 'MobileRestApiV2Controller@editMeeting'));

// // edit email
// Route::post('mobile-api/v2/edit-email', array("as" => "mobile_api.v2.edit_email", "uses" => 'MobileRestApiV2Controller@editEmail'));

// // get my meetings list
// Route::post('mobile-api/v2/get-my-meetings-list', array("as" => "mobile_api.v2.get_my_meetings_list", "uses" => 'MobileRestApiV2Controller@getMyMeetingsList'));

// // update meeting status
// Route::post('mobile-api/v2/update-meeting-status', array("as" => "mobile_api.v2.update_meeting_status", "uses" => 'MobileRestApiV2Controller@updateMeetingStatus'));

// // update meeting summary
// Route::post('mobile-api/v2/update-meeting-summary', array("as" => "mobile_api.v2.update_meeting_summary", "uses" => 'MobileRestApiV2Controller@updateMeetingSummary'));

// // get meeting status logs list
// Route::post('mobile-api/v2/get-meeting-status-logs-list', array("as" => "mobile_api.v2.get_meeting_status_logs_list", "uses" => 'MobileRestApiV2Controller@getMeetingStatusLogsList'));

// // get employee profile details
// Route::get('mobile-api/v2/get-employee-profile-details', array("as" => "mobile_api.v2.get_employee_profile_details", "uses" => 'MobileRestApiV2Controller@getEmployeeProfileDetails'));

// // update employee profile details
// Route::post('mobile-api/v2/update-employee-profile-details', array("as" => "mobile_api.v2.update_employee_profile_details", "uses" => 'MobileRestApiV2Controller@updateEmployeeProfileDetails'));

// // get country code list
// Route::get('mobile-api/v2/get-country-code-list', array("as" => "mobile_api.v2.get_country_code_list", "uses" => 'MobileRestApiV2Controller@getCountryCodeList'));

// // get quotations list
// Route::post('mobile-api/v2/get-quotations-list', array("as" => "mobile_api.v2.get_quotations_list", "uses" => 'MobileRestApiV2Controller@getQuotationsList'));

// // view quotation pdf
// Route::get('mobile-api/v2/quotations/generate-pdf', array("as" => "mobile_api.v2.quotations.generate_pdf", "uses" => "MobileRestApiV2Controller@generateQuotationPDF"));

// // get tasks list
// Route::post('mobile-api/v2/get-tasks-list', array("as" => "mobile_api.v2.get_tasks_list", "uses" => 'MobileRestApiV2Controller@getTasksList'));

// // create task
// Route::post('mobile-api/v2/create-task', array("as" => "mobile_api.v2.create_task", "uses" => 'MobileRestApiV2Controller@createTask'));

// // update task
// Route::post('mobile-api/v2/update-task', array("as" => "mobile_api.v2.update_task", "uses" => 'MobileRestApiV2Controller@updateTask'));

// // get reminders list
// Route::post('mobile-api/v2/get-reminders-list', array("as" => "mobile_api.v2.get_reminders_task", "uses" => 'MobileRestApiV2Controller@getRemindersList'));

// // create reminder
// Route::post('mobile-api/v2/create-reminder', array("as" => "mobile_api.v2.create_reminder", "uses" => 'MobileRestApiV2Controller@createReminder'));

// // update reminder
// Route::post('mobile-api/v2/update-reminder', array("as" => "mobile_api.v2.update_reminder", "uses" => 'MobileRestApiV2Controller@updateReminder'));

// // delete reminder
// Route::post('mobile-api/v2/delete-reminder', array("as" => "mobile_api.v2.delete_reminder", "uses" => 'MobileRestApiV2Controller@deleteReminder'));