<?php


class EmailAttachment extends \Eloquent
{

    protected $fillable = [
        'email_id',
        'title',
        'description',
        'email_attachment_gcs_file_url',
        'email_attachment_file_url'
    ];

    // relationships

    public function email()
    {
        return $this->belongsTo('Email', 'email_id');
    }

}