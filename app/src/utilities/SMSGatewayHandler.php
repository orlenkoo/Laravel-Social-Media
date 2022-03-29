<?php
use Twilio\Rest\Client;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/5/2016
 * Time: 12:37 PM
 */
class SMSGatewayHandler
{
    public static function sendSMS($phone_number, $message)
    {
        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'AC2a9ac10724feb180b83f272c929a747a';
        $token = '6c5ad15f12848d9caa5225f09cd39000';
        $client = new Client($sid, $token);

        // Use the client to do fun stuff like send text messages!
        $client->messages->create(
            // the number you'd like to send the message to
            $phone_number,
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+14153600697 ',
                // the body of the text message you'd like to send
                'body' => $message
            )
        );
    }

}