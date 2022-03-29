<?php

use google\appengine\api\cloud_storage\CloudStorageTools;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/5/2017
 * Time: 5:50 PM
 */
class SettingsController extends BaseController
{
    public function index()
    {
        $organization_id = Session::get('user-organization-id');
        $organization = Organization::where('id', $organization_id)->first();
        $organization_preference = OrganizationPreference::where('organization_id', $organization_id)->first();

        return View::make('settings.index', compact('organization_preference', 'organization'))->render();
    }

    public function ajaxLoadMediaChannelsList()
    {
        $search_query_media_channel = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('search_query_media_channel'));
        $organization_id = Session::get('user-organization-id');

        $build_query = MediaChannel::where('organization_id', $organization_id);

        if ($search_query_media_channel != '') {
            $build_query->where('media_channel', 'LIKE', '%' . $search_query_media_channel . '%');
        }

        $media_channels = $build_query->paginate(10);

        return View::make('settings.media_channels._ajax_partials.media_channels_list', compact('media_channels'))->render();
    }

    public function ajaxSaveMediaChannel()
    {
        $organization_id = Session::get('user-organization-id');

        $media_channel = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('media_channel'));

        $data_media_channels = array(
            'organization_id' => $organization_id,
            'media_channel' => $media_channel,
            'status' => 1
        );

        $media_channel = MediaChannel::create($data_media_channels);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $media_channel->id,
            'data' => $data_media_channels
        );
        AuditTrail::addAuditEntry("MediaChannel", json_encode($audit_action));

        return "Updated Successfully.";
    }

    public function ajaxSavePreferenceChanges()
    {

        $organization_id = Session::get('user-organization-id');

        $payment_terms = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('payment_terms'));
        $terms_and_conditions = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('terms_and_conditions'));
        $tax_percentage = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('tax_percentage'));
        $send_quotation_for_approval = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('send_quotation_for_approval'));
        $send_quotation_follow_up_email_reminder = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('send_quotation_follow_up_email_reminder'));
        $organization = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('organization'));
        $business_registration_number = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('business_registration_number'));
        $address_line_1 = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('address_line_1'));
        $address_line_2 = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('address_line_2'));
        $city = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('city'));
        $postal_code = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('postal_code'));
        $state = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('state'));
        $country_id = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('country_id'));
        $phone_number_country_code = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('phone_number_country_code'));
        $phone_number = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('phone_number'));
        $fax_number_country_code = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('fax_number_country_code'));
        $fax_number = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('fax_number'));
        $email = GeneralCommonFunctionHelper::sanitizeUserInput(Input::get('email'));

        $data_organization = array(
            'organization' => $organization,
            'business_registration_number' => $business_registration_number,
            'address_line_1' => $address_line_1,
            'address_line_2' => $address_line_2,
            'city' => $city,
            'postal_code' => $postal_code,
            'state' => $state,
            'country_id' => $country_id,
            'phone_number_country_code' => $phone_number_country_code,
            'phone_number' => $phone_number,
            'fax_number_country_code' => $fax_number_country_code,
            'fax_number' => $fax_number,
            'email' => $email,
        );

        $organization = Organization::findOrFail($organization_id);

        $organization->update($data_organization);

        $data_preferences = array(
            'payment_terms' => $payment_terms,
            'terms_and_conditions' => $terms_and_conditions,
            'tax_percentage' => $tax_percentage,
            'send_quotation_for_approval' => $send_quotation_for_approval,
            'send_quotation_follow_up_email_reminder' => $send_quotation_follow_up_email_reminder
        );

        if (Input::hasFile('logo_image')) {

            $logo_image = Input::file('logo_image');
            $file_name = Input::get('file_name');

            if (in_array($logo_image->getClientOriginalExtension(), ['jpeg', 'jpg', 'png', 'gif'])) {
                // generate file name
                $file_name = 'organization_preference_logo_image_' . $file_name . '_' . $organization_id . '.' . $logo_image->getClientOriginalExtension();

                $file_save_data = GCSFileHandler::saveFile($logo_image, $file_name);

                $data_preferences['logo_gcs_file_url'] = $file_save_data['gcs_file_url'];
                $data_preferences['logo_image_url'] = $file_save_data['image_url'];
            } else {
                return false;
            }
        }

        $organization_preference = OrganizationPreference::firstOrCreate(array('organization_id' => $organization_id));

        $organization_preference->update($data_preferences);

        $audit_action = array(
            'action' => 'update',
            'model-id' => $organization_preference->id,
            'data' => $data_preferences
        );
        AuditTrail::addAuditEntry("OrganizationPreference", json_encode($audit_action));

        return "Updated Successfully.";
    }

    public function ajaxLoadOrganizationPreferenceLogoImage()
    {

        $organization_preference_id = Input::get('organization_preference_id');
        $organization_preference = OrganizationPreference::find($organization_preference_id);

        return View::make('settings.organization_preferences._ajax_partials.organization_preference_logo_image', compact('organization_preference'))->render();
    }
}
