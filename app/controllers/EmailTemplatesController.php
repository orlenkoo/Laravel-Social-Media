<?php

class EmailTemplatesController extends \BaseController {

    public function ajaxLoadEmailTemplateUserDefinedTagsList()
    {
        $organization_id = Session::get('user-organization-id');

        $email_template_user_defined_tags = EmailTemplateUserDefinedTag::where('organization_id', $organization_id)->paginate(10);

        return View::make('settings.email_templates._ajax_partials.user_defined_tags_list', compact('email_template_user_defined_tags'))->render();
    }


    public function ajaxSaveUserDefinedTag(){

        $organization_id = Session::get('user-organization-id');

        $name = Input::get('name');
        $value = Input::get('value');
        $tag = EmailTemplateUserDefinedTag::getTagFromName($name);

        $data_email_template_user_defined_tag = array(
            'organization_id' => $organization_id,
            'tag' => $tag,
            'name' => $name,
            'value' => $value,
            'status' => 1
        );

        $email_template_user_defined_tag = EmailTemplateUserDefinedTag::create($data_email_template_user_defined_tag);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $email_template_user_defined_tag->id,
            'data' => $email_template_user_defined_tag
        );
        AuditTrail::addAuditEntry("EmailTemplateUserDefinedTag", json_encode($audit_action));

        return "Tag Saved Successfully.";
    }

    public function ajaxLoadEmailTemplateUserDefinedActionButtonsList()
    {
        $organization_id = Session::get('user-organization-id');

        $email_template_user_defined_action_buttons = EmailTemplateUserDefinedActionButton::where('organization_id', $organization_id)->paginate(10);

        return View::make('settings.email_templates._ajax_partials.user_defined_action_buttons_list', compact('email_template_user_defined_action_buttons'))->render();
    }

    public function ajaxSaveUserDefinedActionButton(){

        $organization_id = Session::get('user-organization-id');

        $button_name = Input::get('button_name');
        $url = Input::get('url');
        $value_text = Input::get('value_text');
        $style = Input::get('style');
        $button_tag = EmailTemplateUserDefinedActionButton::getTagFromName($button_name);

        $data_email_template_user_defined_action_button = array(
            'organization_id' => $organization_id,
            'button_tag' => $button_tag,
            'button_name' => $button_name,
            'url' => $url,
            'value_text' => $value_text,
            'style' => $style,
            'status' => 1
        );

        $email_template_user_defined_action_button = EmailTemplateUserDefinedActionButton::create($data_email_template_user_defined_action_button);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $email_template_user_defined_action_button->id,
            'data' => $email_template_user_defined_action_button
        );
        AuditTrail::addAuditEntry("EmailTemplateUserDefinedActionButton", json_encode($audit_action));

        return "Action Button Saved Successfully.";
    }

    public function ajaxLoadEmailTemplatesList(){

        $organization_id = Session::get('user-organization-id');

        $email_templates = EmailTemplate::where('organization_id', $organization_id)->paginate(10);

        return View::make('settings.email_templates._ajax_partials.email_templates_list', compact('email_templates'))->render();
    }

    public function ajaxSaveEmailTemplate(){

        $organization_id = Session::get('user-organization-id');
        $name = Input::get('name');
        $subject = Input::get('subject');
        $to = Input::get('to');
        $cc = Input::get('cc');
        $bcc = Input::get('bcc');
        $body = Input::get('body');


        $data_email_template = array(
            'organization_id' => $organization_id,
            'name' => $name,
            'subject' => $subject,
            'body' => $body,
            'to' => $to,
            'cc' => $cc,
            'bcc' => $bcc,
            'status' => 1
        );

        $email_template = EmailTemplate::create($data_email_template);

        $audit_action = array(
            'action' => 'create',
            'model-id' => $email_template->id,
            'data' => $email_template
        );
        AuditTrail::addAuditEntry("EmailTemplate", json_encode($audit_action));

        return "Email Template Saved Successfully.";

    }

    public function ajaxUpdateEmailTemplate(){

        $email_template_id = Input::get('email_template_id');
        $name = Input::get('name');
        $subject = Input::get('subject');
        $to = Input::get('to');
        $cc = Input::get('cc');
        $bcc = Input::get('bcc');
        $body = Input::get('body');

        $data_email_template = array(
            'name' => $name,
            'subject' => $subject,
            'body' => $body,
            'to' => $to,
            'cc' => $cc,
            'bcc' => $bcc,
        );

        $email_template = EmailTemplate::findOrFail($email_template_id);

        $email_template->update($data_email_template);

        $audit_action = array(
            'action' => 'update',
            'model-id' => $email_template->id,
            'data' => $email_template
        );
        AuditTrail::addAuditEntry("EmailTemplate", json_encode($audit_action));

        return "Email Template Updated Successfully.";

    }


    public function checkUserDefinedTagExists(){//checking tag already exists

        $organization_id = Session::get('user-organization-id');

        $name = Input::get('name');
        $count = EmailTemplateUserDefinedTag::where('name',$name)
            ->where('organization_id', $organization_id)
            ->count();

        if($count){
            return json_encode(array(
                'status' => 1
            ));
        }else{
            return json_encode(array(
                'status' => 0
            ));
        }

    }

    public function checkUserDefinedActionButtonExists(){//checking action button already exists

        $organization_id = Session::get('user-organization-id');

        $button_name = Input::get('button_name');
        $count = EmailTemplateUserDefinedActionButton::where('button_name',$button_name)
            ->where('organization_id', $organization_id)
            ->count();

        if($count){
            return json_encode(array(
                'status' => 1
            ));
        }else{
            return json_encode(array(
                'status' => 0
            ));
        }

    }

    public function ajaxUpdateUserDefinedActionButtonName(){

        $button_id = Input::get('button_id');
        $button_name = Input::get('button_name');
        $button_tag = EmailTemplateUserDefinedActionButton::getTagFromName($button_name);

        $button = EmailTemplateUserDefinedActionButton::findOrFail($button_id);

        $button_old_tag = $button->button_tag;

        $button->update(array(
            'button_tag' => $button_tag,
            'button_name' => $button_name
        ));


        EmailTemplate::updateEmailTemplateTags($button_old_tag,$button_tag);

        $audit_action = array(
            'action' => 'update',
            'model-id' => $button->id,
            'data' => $button
        );
        AuditTrail::addAuditEntry("EmailTemplateUserDefinedActionButton", json_encode($audit_action));


        return "Action Button Updated Successfully.";
    }

    public function ajaxUpdateUserDefinedTagName(){

        $tag_id = Input::get('tag_id');
        $tag_name = Input::get('name');
        $tag_tag = EmailTemplateUserDefinedTag::getTagFromName($tag_name);

        $tag = EmailTemplateUserDefinedTag::findOrFail($tag_id);

        $old_tag = $tag->tag;

        $tag->update(array(
            'tag' => $tag_tag,
            'name' => $tag_name
        ));

        EmailTemplate::updateEmailTemplateTags($old_tag,$tag_tag);

        $audit_action = array(
            'action' => 'update',
            'model-id' => $tag->id,
            'data' => $tag
        );
        AuditTrail::addAuditEntry("EmailTemplateUserDefinedTag", json_encode($audit_action));

        return "Tag Updated Successfully.";

    }
    public function ajaxGetTemplatesList()
    {
        $organization_id = Session::get('user-organization-id');

        $templates = EmailTemplate::where('organization_id',$organization_id)
            ->where('status',1)
            ->orderBy('name', 'asc')
            ->get();

        $templates_json = json_encode($templates);

        return Response::make($templates_json);
    }

    public function ajaxGetTemplateDetails(){

        $email_template_id = Input::get('email_template_id');
        $email_template = EmailTemplate::findOrFail($email_template_id);
        $email_template->body = EmailTemplate::renderEmailTemplateBody($email_template->body);
        $email_template_json = json_encode($email_template);
        return Response::make($email_template_json);

    }

}
