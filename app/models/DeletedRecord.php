<?php

class DeletedRecord extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['deleted_by', 'model_id', 'date', 'model_type', 'model'];

    public static function addDeleteEntry($model_id, $model_type, $model){
        date_default_timezone_set("Asia/Singapore");



        $date = date("Y-m-d H:i:s");

        $data = array(
            'deleted_by' => Session::get('user-id'),
            'model_id' => $model_id,
            'model_type' => $model_type,
            'model' => $model,
            'date' => $date
        );
        DeletedRecord::create($data);

        /*DB::table('audit_trail')->insert(
            array('employee_id' => Session::get('user-id'), 'page' => $page, 'action' => $action, 'datetime' => $date)
        );*/
    }

    public function deletedBy() {
        return $this->belongsTo('Employee', 'deleted_by');
    }

}