<?php

class CustomerTag extends \Eloquent {

    // Add your validation rules here
	public static $rules = [
		// 'title' => 'required'


	];

	// Don't forget to fill this array
	protected $fillable = [
        'organization_id',
        'tag',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function customerTagAssignments()
    {
        return $this->hasMany('CustomerTagAssignment');
    }

}