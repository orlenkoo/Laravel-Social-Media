<?php
/**
 * Created by PhpStorm.
 * User: Suren Dias
 * Date: 3/12/2017
 * Time: 3:09 AM
 */

class PDFGenerator
{
    public static function generatePDF($content)
    {

        $services_360_url = Config::get('project_vars.services_360_url').'generate-pdf';

        $postdata = http_build_query(
            array(
                'html_content' => $content
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);

        $result = file_get_contents($services_360_url, false, $context);

                syslog(LOG_INFO,"result  -- $result");

        return $result;
    }
}