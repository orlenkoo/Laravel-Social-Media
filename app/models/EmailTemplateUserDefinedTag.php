<?php

class EmailTemplateUserDefinedTag extends \Eloquent
{

    protected $fillable = [
        'organization_id',
        'tag',
        'name',
        'value',
        'status',
    ];

    public static $tag_prefix = 'wtu_';

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public static function getAllEmailTemplateUserDefinedTagsForOrganization(){

        $organization_id = Session::get('user-organization-id');

        $email_template_user_defined_tags = EmailTemplateUserDefinedTag::where('organization_id', $organization_id)
            ->where('status',1)
            ->get();

        return $email_template_user_defined_tags;
    }

    public static function getTagFromName($name){
        $name = trim($name);
        $name = str_replace(' ', '_', strtolower($name));
        $tag = "[[".EmailTemplateUserDefinedTag::$tag_prefix.$name."]]";
        return $tag;
    }
}