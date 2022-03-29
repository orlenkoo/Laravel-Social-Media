<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/5/2017
 * Time: 5:50 PM
 */
class CampaignsController extends BaseController
{
    public function index()
    {
        return View::make('campaigns.index')->render();
    }

    public function ajaxLoadCampaignsList()
    {
        $search_query = Input::get('search_query');
        $date_filter = Input::get('date_filter');
        $status_filter = Input::get('status_filter');
        $organization_id = Session::get('user-organization-id');

        $build_query = Campaign::where('organization_id', $organization_id);

        if($search_query != '') {
            $build_query->where('campaign_name', 'LIKE', '%' . $search_query . '%')->orWhere('campaign_content', 'LIKE', '%' . $search_query . '%');
        }

        if($status_filter != ''){
            $build_query->where('status', $status_filter);
        }

        if($date_filter != ''){
            if($date_filter == 'Start Date'){
                $build_query->where('start_date', $date_filter);
            }else if($date_filter == 'End Date'){
                $build_query->where('end_date', $date_filter);
            }
        }

        $campaigns = $build_query->orderBy('id','desc')->paginate(5);

        return View::make('campaigns._ajax_partials.campaigns_list', compact('campaigns'))->render();
    }

    public function ajaxSaveCampaign(){

        $organization_id = Session::get('user-organization-id');

        $campaign_name = Input::get('campaign_form_campaign_name');
        $campaign_content = Input::get('campaign_form_campaign_content');
        $cost = Input::get('campaign_form_cost');
        $start_date = Input::get('campaign_form_start_date');
        $end_date = Input::get('campaign_form_end_date');
        $media_channels = Input::get('campaign_form_media_channels');
        $point_of_contact = Input::get('campaign_form_point_of_contact');

        $data_campaigns = array(
            'organization_id' => $organization_id,
            'campaign_name' => $campaign_name,
            'campaign_content' => $campaign_content,
            'cost' => $cost,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => 1,
            'point_of_contact' => $point_of_contact,

        );

        $campaign = Campaign::create($data_campaigns);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $campaign->id,
            'data' => $data_campaigns
        );
        AuditTrail::addAuditEntry("Campaign", json_encode($audit_action));

        if(is_array($media_channels)) {
            foreach($media_channels as $key => $value){

                $data_media_channel = array(
                    'campaign_id' => $campaign->id,
                    'media_channel_id' => $value
                );

               $create_media_channel =  CampaignMediaChannel::create($data_media_channel);
            }

            $audit_action = array(
                'action' => 'create',
                'model-id' => $create_media_channel->id,
                'data' => $data_media_channel
            );
            AuditTrail::addAuditEntry("CampaignMediaChannel", json_encode($audit_action));
        }

        return "Added Successfully.";
    }

    public function ajaxUpdateCampaignTableMediaChannel(){

        $campaign_id = Input::get('campaign_id');
        $campaign_form_media_channels = Input::get('campaign_form_media_channels');

        CampaignMediaChannel::where('campaign_id', $campaign_id)->delete();

        foreach($campaign_form_media_channels as $key => $value){
             $data_media_channel = array(
                'campaign_id' => $campaign_id,
                'media_channel_id' => $value
            );

            $update_media_channel =  CampaignMediaChannel::create($data_media_channel);
        }

        $audit_action = array(
            'action' => 'update',
            'model-id' => $update_media_channel->id,
            'data' => $data_media_channel
        );
        AuditTrail::addAuditEntry("CampaignMediaChannel", json_encode($audit_action));

        return "Updated Successfully.";

    }

    public function ajaxGetCampaignsList()
    {
        $campaigns = Lead::select(DB::raw('distinct(auto_tagged_campaign) as auto_tagged_campaign'))->where('organization_id', Session::get('user-organization-id'))->orderBy('auto_tagged_campaign', 'asc')->get();

        $campaigns_json = json_encode($campaigns);

        return Response::make($campaigns_json);
    }

    public function ajaxSaveCampaignURL(){



        $campaign_id = Input::get('campaign_id');
        $website_url = Input::get('website_url');
        $campaign_source = Input::get('campaign_source');
        $campaign_medium = Input::get('campaign_medium');
        $campaign_name = Input::get('campaign_name');
        $campaign_term = Input::get('campaign_term');
        $campaign_content = Input::get('campaign_content');

        $data_campaign_urls = array(
            'campaign_id' => $campaign_id,
            'website_url' => $website_url,
            'campaign_source' => $campaign_source,
            'campaign_medium' => $campaign_medium,
            'campaign_name' => $campaign_name,
            'campaign_term' => $campaign_term,
            'campaign_content' => $campaign_content,

        );

        $campaign_url = CampaignUrl::create($data_campaign_urls);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $campaign_url->id,
            'data' => $data_campaign_urls
        );
        AuditTrail::addAuditEntry("CampaignUrl", json_encode($audit_action));

        return "Added Successfully.";
    }

    public function ajaxLoadCampaignUrlsList()
    {
        $campaign_id = Input::get('campaign_id');

        $campaign_urls = CampaignUrl::where('campaign_id', $campaign_id)->paginate(10);

        return View::make('campaigns._ajax_partials.campaign_urls_list', compact('campaign_urls', 'campaign_id'))->render();
    }
}