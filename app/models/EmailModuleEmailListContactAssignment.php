<?php

class EmailModuleEmailListContactAssignment extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    protected $table = 'email_module_email_list_contacts_assignments';

    // Don't forget to fill this array
    protected $fillable = [
        'email_module_email_list_id',
        'contact_id',
    ];

    public function emailModuleEmailList()
    {
        return $this->belongsTo('EmailModuleEmailList', 'email_module_email_list_id');
    }

    public function contact()
    {
        return $this->belongsTo('Contact', 'contact_id');
    }

}