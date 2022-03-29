<?php
use google\appengine\api\cloud_storage\CloudStorageTools;
/**
 * Created by PhpStorm.
 * User: Suren Dias
 *
 * Date: 29/8/2016
 * Time: 4:25 PM
 */
class GCSFileHandler
{

    public static function getGCSFileURL(){

        if (getenv('APP_ENGINE_ENVIRONMENT') == 'PRODUCTION') {
            $bucket_name = getenv('PRODUCTION_UPLOAD_BUCKET_NAME');
        } else {
            $bucket_name = getenv('STAGING_UPLOAD_BUCKET_NAME');
        }

        $gcs_file_url = 'gs://' . $bucket_name . '/';

        return $gcs_file_url;
    }

    public static function saveFile($uploaded_file, $file_name) {

        $gcs_file_url = GCSFileHandler::getGCSFileURL();

        syslog(LOG_INFO, 'file name -- ' . $file_name);

        // move file to google cloud storage
        $gcs_file_url .= $file_name;
        move_uploaded_file($uploaded_file, $gcs_file_url);

        $fp = fopen($gcs_file_url, 'r');
        $mime_type = CloudStorageTools::getContentType($fp);

        fclose($fp);

        $image_url = CloudStorageTools::getPublicUrl($gcs_file_url, false);

        return array('gcs_file_url' => $gcs_file_url, 'image_url' => $image_url, 'mime_type' => $mime_type); // return file url and mime_type
    }

    public static function writeFile($content,$file_name){

        $gcs_file_url = GCSFileHandler::getGCSFileURL();

        $gcs_file_url .= $file_name;

        $fp = fopen($gcs_file_url, 'w');
        fwrite($fp, $content);

        $mime_type = CloudStorageTools::getContentType($fp);

        fclose($fp);

        $image_url = CloudStorageTools::getPublicUrl($gcs_file_url, false);

        return array('gcs_file_url' => $gcs_file_url, 'image_url' => $image_url, 'mime_type' => $mime_type); // return file url and mime_type
    }

    public static function removeFile($file_name){

        $gcs_file_url = GCSFileHandler::getGCSFileURL();

        $gcs_file_url .= $file_name;

        unlink($gcs_file_url);
    }

    public static function saveExportExcel($excel){

        $date = new DateTime();
        $filename = $excel->filename.'_'.$date->getTimestamp();

        $gcs_file_url = GCSFileHandler::getGCSFileURL();

        $root_path = $gcs_file_url. 'data_export';

        $excel->filename = $filename;

        $excel->store('xls', $root_path);

        $full_file_path = $root_path.'/'.$filename.'.xls';

        $report_file_urls = array();
        $report_file_urls['full_path'] = $full_file_path;
        $public_url = CloudStorageTools::getPublicUrl($full_file_path, false);
        $report_file_urls['public_url'] = $public_url;
        return $report_file_urls;

    }

}