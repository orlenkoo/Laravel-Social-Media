<?php

class TaskGuest extends \Eloquent
{
    protected $table = "task_guests";

    protected $fillable = [
        'id',
        'task_id',
        'guest_email',
    ];

    public function task()
    {
        return $this->belongsTo('Task', 'task_id');
    }

}