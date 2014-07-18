<?php

class Student extends \Eloquent {

	protected $table = 'student';
	protected $primaryKey = 'studid';

	public function major()
	{
		return $this->hasOne('SemStudent', 'studid', 'studid');
	}

	public function search($q)
	{
		// search for id
		$model = static::find($q);

		if ($model) {
			$data = $model->toArray();
			return array_map('utf8_encode', $data);
		} else {

			return static::searchByLastName($q);
		}
	}

	public function searchByLastName($q)
	{
		$data = static::where('studlastname', 'LIKE', strtoupper($q) . '%')
				->orderBy('studlastname')
				->orderBy('studfirstname')
				->get(['studid', 'studlastname', 'studfirstname'])
				->toArray();

		return static::_encode($data);
	}

	public static function getStudentWithMajor($id, $sy, $sem)
	{
		$data = static::with('major')->whereHas('major', function($q) use ($id, $sy, $sem) {
			$q->where('studid', $id)
				->where('sy', $sy)
				->where('sem', $sem);
		})->get()->toArray();

		$data = static::_encode($data);
		return $data[0];
	}

	private static function _encode(&$arr)
	{
		array_walk_recursive($arr, function(&$val, $key) {
			$val = utf8_encode($val);
		});

		return $arr;
	}
}