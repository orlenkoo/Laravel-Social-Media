<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/8/16
 * Time: 1:11 PM
 */
if (getenv('APP_ENGINE_ENVIRONMENT') == 'PRODUCTION') {
    $webtics_pixel_reporting_api_url = 'https://reporting-dot-webtics-pixel.appspot.com';
    $event360_from_email = "webtics.product@webnatics.biz";
    $event360_website_url = "https://event360.asia/";
    $google_api_key = "AIzaSyCE62awDkuJmr5aRzhD83hhpRQOPc2rB0M"; // same as staging for now
    $services_360_url = "http://35.198.200.160/api/"; // same as staging for now
} else {
    $webtics_pixel_reporting_api_url = 'https://reporting-staging-dot-webtics-pixel.appspot.com';
    $event360_from_email = "apps.staging@webnatics.biz";
    $event360_website_url = "https://staging-dot-event360-website.appspot.com/";
    $google_api_key = "AIzaSyCE62awDkuJmr5aRzhD83hhpRQOPc2rB0M";
    $services_360_url = "http://35.198.200.160/api/";
}


return array(
    'project_name' => 'Event 360',
    'webnatics_customer_project_id' => 1,
    'event360_project_id' => 2,
    'organization_configurations' => array(
        'disable_quotations_feature_id' => 1, // disable quotations feature id
    ),
    'event360_from_email' => $event360_from_email,
    'webtics_pixel_reporting_api_url' => $webtics_pixel_reporting_api_url,
    'event360_website_url' => $event360_website_url,
    'send_grid_api_key' => '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', // created on 08/Jun/2020 -- API Key ID: ZH_mccmiR7mT8megiiFmMA
    'google_api_key' => $google_api_key,
    'services_360_url' => $services_360_url
);
