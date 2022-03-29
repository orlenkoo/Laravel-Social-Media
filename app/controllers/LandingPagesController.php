<?php
class LandingPagesController extends BaseController
{

    public function index()
    {
        return View::make('landing_pages.index');
    }

    public function ajaxLoadLandingPagesList(){

        $landing_pages = LandingPage::orderBy('id','desc')->paginate(5);

        return View::make('landing_pages._ajax_partials.landing_pages_list', compact('landing_pages'))->render();
    }

    public function ajaxAddLandingPage(){

        $data = Input::all();
        $data['organization_id'] = Session::get('user-organization-id');
        $data['status'] = 'Pending';
        $landing_page = LandingPage::create($data);


        return Redirect::route('landing_pages.ajax.show_landing_page_builder', array('landing_page_id' => $landing_page->id));
    }

    public function ajaxShowLandingPageBuilder(){

        $landing_page_id = Input::get('landing_page_id');
        $landing_page = LandingPage::findOrFail($landing_page_id);


        return View::make('landing_pages._partials.landing_page_builder',compact('landing_page'))->render();
    }

    public function ajaxUpdateLandingPageDetails(){

        $landing_page_id = Input::get('landing_page_id');
        $landing_page = LandingPage::findOrFail($landing_page_id);
        $data = Input::all();


        $landing_page->update($data);

        return "Successfully Updated Landing Page Details";

    }

    public function ajaxGetLandingPageHTML($landing_page_id){

        $landing_page = LandingPage::findOrFail($landing_page_id);

        $landing_page_html = stripslashes($landing_page->landing_page_html);

        $landing_page_html_data = [
            'gjs-html' => $landing_page_html,
            'gjs-css'  => $landing_page->landing_page_css,
        ];

        return Response::json($landing_page_html_data);
    }


    public function ajaxSaveLandingPageHTML($landing_page_id){

        $landing_page = LandingPage::findOrFail($landing_page_id);
        $landing_page_html = Input::get('gjs-html');
        $landing_page_css = Input::get('gjs-css');

        $landing_page->update([
            'landing_page_html' => ''.$landing_page_html.'',
            'landing_page_css'  => $landing_page_css,
        ]);


    }




}

