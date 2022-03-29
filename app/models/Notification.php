<?php

class Notification extends \Eloquent
{
    private $sms_body = '';
    private $email_body = '';
    private $email_subject = 'WEB360 Notification: ';
    private $mobile_app_notification_body = '';

    // Add your validation rules here
    public static $rules = [];

    protected $table = "notifications";

    // Don't forget to fill this array
    protected $fillable = [
        'employee_id',
        'organization_id',
        'type_of_alert',
        'sms',
        'email',
        'mobile_app_notification',
        'status',
    ];

    public static $types_of_alerts = [
        'new_lead_is_received' => 'When a New Lead is Received',
        'lead_is_assigned' => 'When a Lead is Assigned',
        'quotation_is_created' => 'When a Quotation is Created',
        'quotation_status_is_changed' => 'When a Quotation Status is Changed',
    ];

    public static $marketing_type_of_alerts = [
        'lead_is_assigned_to_me' => 'When a Lead is Assigned to me',
        'my_quotation_status_is_updated_by_client' => 'When my Quotation Status is Updated by Client',
    ];

    // relationships

    public function employee()
    {
        return $this->belongsTo('Employee', 'employee_id');
    }
    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    // logic

    public function getNotificationsList($type_of_alert, $organization_id, $employee_id = null)
    {
        $build_query = Notification::where('type_of_alert', $type_of_alert)
            ->where('status', 1)
            ->where('organization_id', $organization_id);

        if ($employee_id) {
            $build_query->where('employee_id', $employee_id);
        }

        $notifications = $build_query->get();

        return $notifications;
    }

    public function sendNotification($notification, $notify_specific_emails = null)
    {
        $employee = is_object($notification->employee) ? $notification->employee : null;

        /* SMS, Push System Notifications have been temporarily stopped */

        if ($employee) {
            if ($notification->sms == 1) {
                //$phone_number = $employee->getPhoneNumber();
                //SMSGatewayHandler::sendSMS($phone_number, $this->sms_body);
            }

            if ($notification->email == 1) {
                $email = $employee->email;
                if (!$notify_specific_emails) {
                    EmailsController::sendGridEmailSender($this->email_subject, $this->email_body, $email);
                } else {
                    foreach ($notify_specific_emails as $notify_specific_email) {
                        EmailsController::sendGridEmailSender($this->email_subject, $this->email_body, $notify_specific_email);
                    }
                }
            }

            if ($notification->mobile_app_notification == 1) {
                // TODO: implement push notifications for the app
            }
        }
    }

    public function leadAssignmentToMeNotification($lead, $lead_assignment, $notify_specific_emails = null)
    {

        try {
            $this->email_body = View::make('notifications.email.lead_assigned_to_me', compact('lead', 'lead_assignment'))->render();
            $this->email_subject .= 'Lead Assigned To You : ' . date('Y-m-d');
            $this->sms_body = View::make('notifications.sms.lead_assigned_to_me', compact('lead'))->render();
            $this->mobile_app_notification_body = View::make('notifications.app_notification.lead_assigned_to_me', compact('lead'))->render();

            $notifications = $this->getNotificationsList('lead_is_assigned_to_me', $lead->organization_id, $lead->assigned_to);

            foreach ($notifications as $notification) {
                $this->sendNotification($notification, $notify_specific_emails);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, $e->getTraceAsString());
        }
    }

    public function myQuotationStatusUpdatedByClientNotification($quotation)
    {
        try {
            $this->email_body = View::make('notifications.email.my_quotation_status_updated_by_client', compact('lead'))->render();
            $this->email_subject .= 'Your Quotations Status Updated By Client : ' . date('Y-m-d');
            $this->sms_body = View::make('notifications.sms.my_quotation_status_updated_by_client', compact('lead'))->render();
            $this->mobile_app_notification_body = View::make('notifications.app_notification.my_quotation_status_updated_by_client', compact('lead'))->render();

            $notifications = $this->getNotificationsList('my_quotation_status_is_updated_by_client', $quotation->organization_id, $quotation->quoted_by);

            foreach ($notifications as $notification) {
                $this->sendNotification($notification);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, $e->getTraceAsString());
        }
    }

    public function newLeadReceivedNotification($lead, $notify_specific_emails = null)
    {
        try {
            $this->email_body = View::make('notifications.email.new_lead_is_received', compact('lead'))->render();
            $this->email_subject .= 'New Lead has been received : ' . date('Y-m-d');
            $this->sms_body = View::make('notifications.sms.new_lead_is_received', compact('lead'))->render();
            $this->mobile_app_notification_body = View::make('notifications.app_notification.new_lead_is_received', compact('lead'))->render();

            $notifications = $this->getNotificationsList('new_lead_is_received', $lead->organization_id);

            foreach ($notifications as $notification) {
                $this->sendNotification($notification, $notify_specific_emails);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, $e->getTraceAsString());
        }
    }

    public function leadAssignedNotification($lead, $lead_assignment, $notify_specific_emails = null)
    {
        try {
            $this->email_body = View::make('notifications.email.lead_is_assigned', compact('lead', 'lead_assignment'))->render();
            $this->email_subject .= 'Lead Assigned : ' . date('Y-m-d');
            $this->sms_body = View::make('notifications.sms.lead_is_assigned', compact('lead'))->render();
            $this->mobile_app_notification_body = View::make('notifications.app_notification.lead_is_assigned', compact('lead'))->render();

            $notifications = $this->getNotificationsList('lead_is_assigned', $lead->organization_id);

            foreach ($notifications as $notification) {
                $this->sendNotification($notification, $notify_specific_emails);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, $e->getTraceAsString());
        }
    }

    public function quotationCreatedNotification($quotation)
    {
        try {
            $this->email_body = View::make('notifications.email.quotation_is_created', compact('lead'))->render();
            $this->email_subject .= 'Quotation is created : ' . date('Y-m-d');
            $this->sms_body = View::make('notifications.sms.quotation_is_created', compact('lead'))->render();
            $this->mobile_app_notification_body = View::make('notifications.app_notification.quotation_is_created', compact('lead'))->render();

            $notifications = $this->getNotificationsList('quotation_is_created', $quotation->organization_id);

            foreach ($notifications as $notification) {
                $this->sendNotification($notification);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, $e->getTraceAsString());
        }
    }

    public function quotationStatusChangedNotification($quotation)
    {
        try {
            $this->email_body = View::make('notifications.email.quotation_status_is_changed', compact('lead'))->render();
            $this->email_subject .= 'Quotation Status Changed : ' . date('Y-m-d');
            $this->sms_body = View::make('notifications.sms.quotation_status_is_changed', compact('lead'))->render();
            $this->mobile_app_notification_body = View::make('notifications.app_notification.quotation_status_is_changed', compact('lead'))->render();

            $notifications = $this->getNotificationsList('quotation_status_is_changed', $quotation->organization_id);

            foreach ($notifications as $notification) {
                $this->sendNotification($notification);
            }
        } catch (Exception $e) {
            syslog(LOG_ERR, $e->getTraceAsString());
        }
    }


    /*** Temporary Email Notification **/

    public static function newLeadReceivedEmailNotification($lead, $employees_in_organzation_to_notify)
    {

        $email_body = View::make('notifications.email.new_lead_is_received', compact('lead'))->render();
        $email_subject = 'WEB360 Notification: New Lead has been received : ' . date('Y-m-d H:i') . ' | ' . $lead->id;

        foreach ($employees_in_organzation_to_notify as $employees_in_organization) {
            syslog(LOG_INFO, '$employees_in_organzation_to_notify -- ' . $employees_in_organzation_to_notify);
            EmailsController::sendGridEmailSender($email_subject, $email_body, $employees_in_organization->email);
        }
    }

    public static function leadAssignmentToMeEmailNotification($lead, $lead_assignment, $employees_in_organzation_to_notify)
    {

        $email_body = View::make('notifications.email.lead_is_assigned_to_me', compact('lead', 'lead_assignment'))->render();
        $email_subject = 'WEB360 Notification: Lead Assigned To You : ' . date('Y-m-d');

        foreach ($employees_in_organzation_to_notify as $employees_in_organization) {
            syslog(LOG_INFO, '$employees_in_organzation_to_notify -- ' . $employees_in_organzation_to_notify);
            EmailsController::sendGridEmailSender($email_subject, $email_body, $employees_in_organization->email);
        }
    }

    public static function leadAssignedEmailNotification($lead, $lead_assignment, $employees_in_organzation_to_notify)
    {

        $email_body = View::make('notifications.email.lead_is_assigned', compact('lead', 'lead_assignment'))->render();
        $email_subject = 'WEB360 Notification: Lead Assigned : ' . date('Y-m-d');

        foreach ($employees_in_organzation_to_notify as $employees_in_organization) {
            syslog(LOG_INFO, '$employees_in_organzation_to_notify -- ' . $employees_in_organzation_to_notify);
            EmailsController::sendGridEmailSender($email_subject, $email_body, $employees_in_organization->email);
        }
    }

    public static function quotationStatusChangedEmailNotification($quotation, $quotation_status_changed_by, $employees_in_organzation_to_notify)
    {

        $email_body = View::make('notifications.email.quotation_status_is_changed', compact('quotation', 'quotation_status_changed_by'))->render();
        $email_subject = 'WEB360 Notification: Quotation Status Changed : ' . date('Y-m-d');

        foreach ($employees_in_organzation_to_notify as $employees_in_organization) {
            syslog(LOG_INFO, '$employees_in_organzation_to_notify -- ' . $employees_in_organzation_to_notify);
            EmailsController::sendGridEmailSender($email_subject, $email_body, $employees_in_organization->email);
        }
    }

    public static function quotationCreatedEmailNotification($quotation, $employees_in_organzation_to_notify)
    {

        $email_body = View::make('notifications.email.quotation_is_created', compact('quotation'))->render();
        $email_subject = 'WEB360 Notification: Quotation is created : ' . date('Y-m-d');

        foreach ($employees_in_organzation_to_notify as $employees_in_organization) {
            syslog(LOG_INFO, '$employees_in_organzation_to_notify -- ' . $employees_in_organzation_to_notify);
            EmailsController::sendGridEmailSender($email_subject, $email_body, $employees_in_organization->email);
        }
    }
}
