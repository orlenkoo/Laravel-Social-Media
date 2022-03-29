<?php

class EmailModuleEmailList extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    protected $table = 'email_module_email_lists';

    // Don't forget to fill this array
    protected $fillable = [
        'organization_id',
        'email_list_name',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function emailModuleEmailListContactAssignments()
    {
        return $this->hasMany('EmailModuleEmailListContactAssignment');
    }

    public function getSelectedContactIds($email_list_id)
    {
        $contact_ids = EmailModuleEmailListContactAssignment::where('email_module_email_list_id', $email_list_id)->lists('contact_id');
        return $contact_ids;
    }
}