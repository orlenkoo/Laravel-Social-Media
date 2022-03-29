<?php
use google\appengine\api\mail\Message;

class EmailsController extends \BaseController {

    public static function sendWeb360CustomerEmails($subject, $body, $to, $sender, $cc, $bcc, $to_name = "", $from_name = "", $attachments = null)
    {
        $to_array = explode(',', $to);
        $cc_array = explode(',', $cc);
        $bcc_array = explode(',', $bcc);

        if(is_object($sender)){
            $sender_email = $sender->email;
        }else{
            $sender_email = $sender;
        }

        if(is_array($to_array)) {
            foreach($to_array as $to_address) {
                if($to_address != '') {
                    EmailsController::sendGridEmailSender($subject, $body, $to_address, $sender_email,$to_name,$from_name,$attachments);
                }
            }
        }

        if(is_array($cc_array)) {
            foreach($cc_array as $cc_address) {
                if($cc_address != '') {
                    EmailsController::sendGridEmailSender($subject, $body, $cc_address, $sender_email,$to_name,$from_name,$attachments);
                }

            }
        }

        if(is_array($bcc_array)) {
            foreach($bcc_array as $bcc_address) {
                if($bcc_address != '') {
                    EmailsController::sendGridEmailSender($subject, $body, $bcc_address, $sender_email,$to_name,$from_name,$attachments);
                }

            }
        }
    }


    public function sendEmail($subject, $mailBody, $to, $from = null, $cc = null, $bcc = null, $attachments = null, $reply_to = null)
    {
        // Notice that $image_content_id is the optional Content-ID header value of the
//  attachment. Must be enclosed by angle brackets (<>)
        // $image_content_id = '<image-content-id>';

// Notice that $image_data is the raw file data of the attachment.
        try {
            $fullMailBody = '';
            $header = $this->getEmailHeader();
            $footer = $this->getEmailFooter();
            $fullMailBody .= '' . $header;
            $fullMailBody .= '' . $mailBody;
            $fullMailBody .= '' . $footer;


            $message = new Message();

            if ($from == null) {

                if (getenv('APP_ENGINE_ENVIRONMENT') == 'PRODUCTION') {
                    $from = "Web360 Team <noreply@web360.asia>";
                } else {
                    $from = "apps.staging@webnatics.biz";
                }

            }

            if ($cc != null) {
                $message->addCc($cc);
            }

            if ($bcc != null) {
                $message->addBcc($bcc);
            }

            if ($reply_to != null) {
                $message->setReplyTo($reply_to);
            }


            $message->setSender($from);
            $message->addTo($to);
            $message->setSubject($subject);
            $message->setHTMLBody($fullMailBody);

            if ($attachments != null) {
                foreach ($attachments as $attachment) {
                    //return dd($attachment);


                    $my_file = file_get_contents($attachment['file_path']);

                    $message->addAttachment($attachment['file_name'], $my_file);
                }
            }

            //$message->addAttachment('image.jpg', $image_data, $image_content_id);
            $message->send();
        } catch (InvalidArgumentException $e) {
            syslog(LOG_ERR, 'error when sending email -' . $e->getMessage());
        }

    }


    public function getEmailHeader()
    {
        $header = ''
            . '<html>'

            . '<body>'
            /* . '<div style="text-align: center; background: #c8ad11; padding: 10px;">'
             . '<img src="http://elegant-cipher-95902.appspot.com/assets/img/logo-new.png" class="logo" alt="Web Clickz">'
             . '</div>'*/
            . '';

        return $header;
    }

    public function getEmailFooter()
    {
        $footer = ''
            . '<hr/>'
            . '<p style="font-size: 12px; color: #b0b0b0; text-center: center;">&copy; Web360 ' . date('Y') . '</p>'
            . ''
            . ''
            . '</body>'
            . '</html>'
            . '';

        return $footer;
    }

    public static function sendGridEmailSender($subject, $mailBody, $to, $from = null, $to_name = "", $from_name = "", $attachments = null)
    {

        if($from == null) {
            if (getenv('APP_ENGINE_ENVIRONMENT') == 'PRODUCTION') {
                $from = "noreply@web360.asia";
                $from_name = "Web360 Team";
            } else {
                $from = "apps.staging@webnatics.biz";
            }
        }

        syslog(LOG_INFO, 'subject -- '. $subject);
        syslog(LOG_INFO, '$mailBody -- '. $mailBody);
        syslog(LOG_INFO, '$to -- '. $to);
        syslog(LOG_INFO, '$from -- '. $from);
        syslog(LOG_INFO, '$to_name -- '. $to_name);
        syslog(LOG_INFO, '$from_name -- '. $from_name);

        $email = new \SendGrid\Mail\Mail();

        $email->setFrom($from, $from_name);
        $email->setSubject($subject);
        $email->addTo($to, $to_name);
        $email->addContent(
            "text/html", $mailBody
        );

        if ($attachments != null) {
            foreach ($attachments as $attachment) {
                //return dd($attachment);

                $my_file = file_get_contents($attachment['file_path']);



                $file_encoded = base64_encode($my_file);
                $sendGridAttachment = new SendGrid\Mail\Attachment();
                $sendGridAttachment->setContent($file_encoded);
                $sendGridAttachment->setType("application/pdf");
                $sendGridAttachment->setDisposition("attachment");
                $sendGridAttachment->setFilename($attachment['file_name']);

                $email->addAttachment($sendGridAttachment);
            }
        }

        $apiKey = Config::get('project_vars.send_grid_api_key');
        $sendgrid = new \SendGrid($apiKey);

        try {
            $response = $sendgrid->send($email);
            syslog(LOG_INFO, $response->statusCode());
            syslog(LOG_INFO, json_encode($response->headers()));
            syslog(LOG_INFO, $response->body());
        } catch (Exception $e) {
            syslog(LOG_ERR, 'Caught exception: '.  $e->getMessage());
        }



    }

}
