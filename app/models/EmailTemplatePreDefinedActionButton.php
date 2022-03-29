<?php

class EmailTemplatePreDefinedActionButton extends \Eloquent
{
    protected $fillable = [
        'button_tag',
        'button_name',
        'url',
        'value_text',
        'style'
    ];

    public static $styles = array(
        'alert' => 'Alert',
        'success' => 'Success',
        'info' => 'Info',
        'warning' => 'Warning'
    );

    public static function getAllEmailTemplatePreDefinedActionButtonsForOrganization(){

        $email_template_user_defined_action_buttons = EmailTemplatePreDefinedActionButton::get();

        return $email_template_user_defined_action_buttons;
    }
}