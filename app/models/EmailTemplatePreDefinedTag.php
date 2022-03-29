<?php

class EmailTemplatePreDefinedTag extends \Eloquent
{
    protected $fillable = [
        'tag',
        'name',
        'value'
    ];

    public static function getAllEmailTemplatePreDefinedTags(){

        $email_template_user_defined_tags = EmailTemplatePreDefinedTag::get();

        return $email_template_user_defined_tags;
    }

}