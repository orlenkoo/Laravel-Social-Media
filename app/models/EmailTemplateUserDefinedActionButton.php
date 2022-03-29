<?php

class EmailTemplateUserDefinedActionButton extends \Eloquent
{

    protected $fillable = [
        'organization_id',
        'button_tag',
        'button_name',
        'url',
        'value_text',
        'style',
        'status',
    ];

    public static $tag_prefix = 'wbu_'; //web360 button user defined tag

    public static $styles = array(
      'alert' => 'Alert',
      'success' => 'Success',
      'info' => 'Info',
      'warning' => 'Warning'
    );

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public static function getAllEmailTemplateUserDefinedActionButtonsForOrganization(){
        $organization_id = Session::get('user-organization-id');

        $email_template_user_defined_action_buttons = EmailTemplateUserDefinedActionButton::where('organization_id', $organization_id)
            ->where('status',1)
            ->get();

        return $email_template_user_defined_action_buttons;
    }

    public static function getTagFromName($name){
        $name = trim($name);
        $name = str_replace(' ', '_', strtolower($name));
        $tag = "[[".EmailTemplateUserDefinedActionButton::$tag_prefix.$name."]]";
        return $tag;
    }

}