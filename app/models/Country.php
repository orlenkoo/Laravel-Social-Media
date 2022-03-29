<?php

class Country extends \Eloquent {

    // Add your validation rules here
	public static $rules = [
		// 'title' => 'required'


	];

	// Don't forget to fill this array
	protected $fillable = [
        'country',
        'status',
    ];

    public function customers() {
        return $this->hasMany('Customer');
    }


}