<?php


class GoogleCloudNaturalLanguageUtilities
{

    public static function getAPIKey(){

        $api_key = Config::get('project_vars.google_api_key');

        return $api_key;
    }

    /*
     * @param  string  $content
     * @param  string  $content_type  DEFAULT  PLAIN_TEXT
     */
    public static function getContentClassification($content,$content_type = 'PLAIN_TEXT'){

        $api_key = GoogleCloudNaturalLanguageUtilities::getAPIKey();
        $url = "https://language.googleapis.com/v1/documents:classifyText?key=$api_key";

        $data = [
            "document" => [
                "type" => $content_type,
                "content" => $content
            ]
        ];

        $data_string = json_encode($data);

        $headers = "accept: */*\r\n" .
            "Content-Type: application/json\r\n" .
            "Content-Length: " . strlen($data_string) ;

        $context = [
            'http' => [
                'method' => 'POST',
                'header' => $headers,
                'content' => $data_string,
            ]
        ];

        $context = stream_context_create($context);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result,true);

        $categories = $result['categories'];

        return $categories;
    }

}