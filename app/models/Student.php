<?php

class Student extends \Eloquent {
	use \Helper;

	protected $table = 'student';
	protected $primaryKey = 'studid';

	public function major()
	{
		return $this->hasOne('SemStudent', 'studid', 'studid');
	}

	public function search($q)
	{
		extract($q);

		if (is_array($q)) {

			$data = DB::table('studfullnames')
				->whereIn('studid', $q)
				->get(array('studid', 'fullname'));

			return static::_encode($data);

		} else {

			// search for id
			//$model = static::find($q);
			$data = DB::table('studfullnames')
					->where('studid', $q)
					->get(['studid', 'fullname']);

			if ($data) {
				//$data = $model->toArray();
				$r[0] = static::_encode($data[0]);

				if(isset($d)) { // direct return not array
					return $r[0];
				} else {
					return $r;
				}
			} else {
				return static::searchByLastName($q);
			}
		}
	}

	public function searchBySyBySem($q) {
		extract($q);

		if(is_numeric($search)) {
			//By ID
			$data = DB::select("
				SELECT *
				FROM student
				LEFT JOIN semstudent
				USING(studid)
				WHERE
					studid=? AND
					sem=? AND
					sy=?
			", array($search, $sem, $sy));

			return static::_encode($data);
		} else {
			//By Lastname or Fullname
			$data = DB::select("
				SELECT *
				FROM student
				LEFT JOIN semstudent
				USING(studid)
				WHERE
					studfullname LIKE ? AND
					sem=? AND
					sy=?
			", array(strtoupper($search).'%', $sem, $sy));

			return static::_encode($data);
		}
	}

	public function searchByLastName($q)
	{
		$data = DB::table('studfullnames')
				->where('fullname', 'LIKE', strtoupper($q) . '%')
				->orderBy('fullname')
				->get(['studid', 'fullname']);
/*		$data = static::where('studlastname', 'LIKE', strtoupper($q) . '%')
				->orderBy('studlastname')
				->orderBy('studfirstname')
				->get(['studid', 'studfullname'])
				->toArray();
*/
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

	public static function searchByLastNameBySyBySem($q) {
		extract($q);

		$data = DB::select("
			SELECT *
			FROM student
			LEFT JOIN semstudent
			USING(studid)
			WHERE
				sem=? AND
				sy=? AND
				studfullname LIKE ?
		", array($sem, $sy, strtoupper($search).'%'));
	}
}