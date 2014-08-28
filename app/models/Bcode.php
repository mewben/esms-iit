<?php

class Bcode extends BaseModel {

	protected $table = 'bcodes';
	protected $fillable = [
		'bcode',
		'desc'
	];

	public $timestamps = false;

	protected $rules = [
		'bcode' => 'required'
	];

	public function getAll()
	{
		$data['bcodes'] = static::orderBy('bcode')->get()->toArray();

		return $data;
	}

}