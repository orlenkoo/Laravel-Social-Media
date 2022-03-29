<?php

/**
 * Created by PhpStorm.
 * User: Roshane De Silva
 * Date: 18/05/2016
 * Time: 07:48
 */
class LeadNote extends \Eloquent
{
    // Add your validation rules here
    public static $rules = [

    ];

    protected $table = 'lead_notes';

    // Don't forget to fill this array
    protected $fillable = [
        'lead_id',
        'note',
        'datetime',
        'created_by',
    ];

    public function lead()
    {
        return $this->belongsTo('Lead', 'lead_id');
    }


    public function leadNoteCreatedBy()
    {
        return $this->belongsTo('Employee', 'created_by');
    }

    public static function createLeadNote($lead_id, $note, $employee_id)
    {
        date_default_timezone_set("Asia/Singapore");
        $lead_note = LeadNote::create(
            array(
                'lead_id' => $lead_id,
                'note' => $note,
                'datetime' => date('Y-m-d H:i:s'),
                'created_by' => $employee_id,
            )
        );

        return $lead_note;
    }

}