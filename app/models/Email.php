<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26/7/2015
 * Time: 11:34 PM
 */
class Email extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [


    ];

    protected $table = "emails";

    protected $fillable = [
        'id',
        'customer_id',
        'to',
        'cc',
        'bcc',
        'subject',
        'body',
        'sent_by_id',
        'sent_on',
        'status',
        'attachment_urls_json',

    ];

    public static $status = array(
        'draft' => 'Draft',
        'Sent' => 'Sent',
    );

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function sentBy()
    {
        return $this->belongsTo('Employee', 'sent_by_id');
    }

    public function customerTimeLineItems()
    {
        return $this->hasMany('CustomerTimeLineItem');
    }

    public static function getNumberOfEmailsForGivenCustomer($customer){
        $email_count = is_object($customer) ? sizeof($customer->emails) : 0 ;
        return $email_count;
    }

    public static function saveEmail($data_emails, $employee_id = null){

        $customer_id = $data_emails['customer_id'];
        $datetime = $data_emails['sent_on'];
        $email_id = $data_emails['email_id'];

        if($email_id == ''){
            $email = Email::create($data_emails);
            $audit_action = array(
                'action' => 'create',
                'model-id' => $email->id,
                'data' => $data_emails
            );
            AuditTrail::addAuditEntry("Email", json_encode($audit_action), $employee_id);
        }else{
            $email = Email::find($email_id);

            if($email->status == 'sent'){
                $email = Email::create($data_emails);
                $audit_action = array(
                    'action' => 'create',
                    'model-id' => $email->id,
                    'data' => $data_emails
                );

                //attach all previous attachments
                $previous_email_attachments = EmailAttachment::where('email_id', $email_id)->get();
                if(count($previous_email_attachments) > 0){
                    foreach($previous_email_attachments as $previous_email_attachment){
                        EmailAttachment::create(array(
                            'email_id' => $email->id,
                            'title' => $previous_email_attachment->title,
                            'email_attachment_gcs_file_url'  => $previous_email_attachment->email_attachment_gcs_file_url,
                            'email_attachment_file_url' => $previous_email_attachment->email_attachment_file_url
                        ));
                    }
                }

                AuditTrail::addAuditEntry("Email", json_encode($audit_action), $employee_id);

            }else{
                $email->update($data_emails);
                $audit_action = array(
                    'action' => 'update',
                    'model-id' => $email->id,
                    'data' => $data_emails
                );
                AuditTrail::addAuditEntry("Email", json_encode($audit_action), $employee_id);
            }

        }


        $data_customer_time_line_items = array(
            'customer_id' => $customer_id,
            'time_line_item_source' => "Email",
            'time_line_item_source_id' => $email->id,
            'datetime' => $datetime
        );

        CustomerTimeLineItem::create($data_customer_time_line_items);

        return $email;
    }

    public static function saveEmailAttachment($email_id,$title,$attachment_file){

        $data_email_attachment = array(
            'email_id' => $email_id,
            'title' => $title
        );

        $email_attachment = EmailAttachment::create($data_email_attachment);

        // generate file name
        $file_name = 'email_attachment_' . $email_attachment->id . '.' . $attachment_file->getClientOriginalExtension();

        $file_save_data = GCSFileHandler::saveFile($attachment_file, $file_name);

        $data_attachment['email_attachment_gcs_file_url'] = $file_save_data['gcs_file_url'];
        $data_attachment['email_attachment_file_url'] = $file_save_data['image_url'];

        $email_attachment->update($data_attachment);

        return $data_email_attachment;

    }

    public static function getAttachmentList($email_id){

        $email = Email::find($email_id);
        if(is_object($email)){

            $attachments = [];
            $email_attachments = EmailAttachment::where('email_id', $email_id)->get();

            foreach($email_attachments as $email_attachment){
                array_push($attachments,array(
                    'email_attachment_id' => $email_attachment->id,
                    'title' => $email_attachment->title,
                    'file_path' => $email_attachment->email_attachment_file_url,
                    'file_name' => basename($email_attachment->email_attachment_file_url)
                ));
            }

            return $attachments;
        }else{
            return [];
        }
    }

    public static function formatEmailsSendingArrayBeforeSending($to){
        $to = explode(',', $to);
        $to_array = array();
        foreach($to as $send_to){
            $send_to = trim($send_to);
            preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $send_to, $matches);
            $send_to = $matches[0];
            $send_to = implode("|",$send_to);
            array_push($to_array,$send_to);
        }
        $send_tos = implode(",",$to_array);
        return $send_tos;
    }

}