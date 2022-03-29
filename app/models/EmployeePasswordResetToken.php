<?php

class EmployeePasswordResetToken extends \Eloquent {

	// Don't forget to fill this array
	protected $fillable = [
		'token',
		'user_id',
		'created_timestamp',
		'status'
	];

}