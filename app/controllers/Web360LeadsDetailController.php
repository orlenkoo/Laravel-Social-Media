<?php

/**
 * Created by PhpStorm.
 * User: DeAlwis
 * Date: 9/1/2016
 * Time: 1:07 PM
 */
class Web360LeadsDetailController extends BaseController
{
    public function index()
    {
        return View::make('webtics_product.web360.leads_detail.index')->render();
    }

    public function getAjaxEvent360WebsiteFormSubmission()
    {

        $organization_id = Session::get('user-organization-id');

        $search_query = Input::get('search_query');
        if ($search_query == '') {
            $search_query = '%';
        }

        $leads = Lead::where('lead_source', 'LIKE', '%' . $search_query . '%')->where('organization_id', '=',$organization_id)->orderby('lead_source')->paginate(10);

        return View::make('webtics_product.web360.leads_detail._ajax_partials.event360_website_form_list', compact('leads'))->render();
    }


}