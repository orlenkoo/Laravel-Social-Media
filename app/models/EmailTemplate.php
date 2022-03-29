<?php

class EmailTemplate extends \Eloquent
{

    protected $fillable = [
        'organization_id',
        'name',
        'subject',
        'body',
        'to',
        'cc',
        'bcc',
        'status',
    ];


    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public static function updateEmailTemplateTags($old_tag,$new_tag){

        $organization_id = Session::get('user-organization-id');

        //get all email templates for the organization to update
        $email_templates = EmailTemplate::where('organization_id', $organization_id)->get();

        foreach ($email_templates as $email_template){ //for each email template replace old tag with new tag

            $email_template_body = $email_template->body;

            $email_template_body = str_replace($old_tag,$new_tag,$email_template_body);

            $email_template->update(array(
                'body' => $email_template_body
            ));
        }


    }

    public static function renderEmailTemplateBody($email_template_body){

        $email_template_user_defined_action_buttons = EmailTemplateUserDefinedActionButton::getAllEmailTemplateUserDefinedActionButtonsForOrganization();
        $email_template_user_defined_tags = EmailTemplateUserDefinedTag::getAllEmailTemplateUserDefinedTagsForOrganization();
        $email_template_pre_defined_tags = EmailTemplatePreDefinedTag::getAllEmailTemplatePreDefinedTags();

        //find all pre defined tag usages and replace with text
        foreach ($email_template_pre_defined_tags as $email_template_pre_defined_tag){

            $email_template_body = str_replace($email_template_pre_defined_tag->tag,$email_template_pre_defined_tag->value,$email_template_body);

        }

        //find all user defined action buttons and replace with action button html
        foreach ($email_template_user_defined_action_buttons as $email_template_user_defined_action_button){

            $button_html = "<a href='".$email_template_user_defined_action_button->url."' class='".$email_template_user_defined_action_button->style." button'>".$email_template_user_defined_action_button->value_text."</a>";
            $email_template_body = str_replace($email_template_user_defined_action_button->button_tag,$button_html,$email_template_body);

        }

        //find all user defined tag usages and replace with text
        foreach ($email_template_user_defined_tags as $email_template_user_defined_tag){

            $email_template_body = str_replace($email_template_user_defined_tag->tag,$email_template_user_defined_tag->value,$email_template_body);

        }

        //appending the users email signature and signature file at the end of the email
        $email_template_body = $email_template_body.Employee::getEmailSignatureHTML();

        return $email_template_body;

    }
}