<?php

class CustomerTagAssignment extends \Eloquent {

    // Add your validation rules here
	public static $rules = [
		// 'title' => 'required'


	];

	// Don't forget to fill this array
	protected $fillable = [
        'customer_id',
        'customer_tag_id',
    ];

    public function customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function customerTag()
    {
        return $this->belongsTo('CustomerTag', 'customer_tag_id');
    }


}