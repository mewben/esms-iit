<?php

class Grade {
	use \Helper;

	/**
	 * $q 	array 'sy', 'sem', 'subjcode', 'section'
	 */
	public function getStudentsBySection($q)
	{
		$data = [];

		extract($q);

		$rec = DB::select("
			SELECT *
			FROM registration
			LEFT JOIN studfullnames USING(studid)
			WHERE
				sy=? AND
				sem=? AND
				subjcode=? AND
				section=?
			ORDER BY fullname
		", array($sy, $sem, $subjcode, $section));

		$rec = static::_encode($rec);

		$data['data'] = $rec;

		// meta
		$subj = DB::select("
			SELECT *
			FROM subject
			LEFT JOIN semsubject
			USING(subjcode)
			WHERE
				subjcode=? AND
				section=? AND
				sy=? AND
				sem=?
		", array($subjcode, $section, $sy, $sem));

		$data['meta'] = $subj[0];
		$data['meta']->section = $section;

 		//print_r($data);
		return $data;
	}

	public function store($data)
	{
		foreach ($data as $v) {
			//extract($v);

			if($v->prelim1 && !$v->prelim2) {
				//update prelim1
				DB::statement("
					UPDATE registration
					SET
						prelim1=?
					WHERE
						studid=? AND
						subjcode=? AND
						section=? AND
						sy=? AND
						sem=?
				", array(
					$v->prelim1,
					$v->studid,
					$v->subjcode,
					$v->section,
					$v->sy,
					$v->sem
				));
			}

			elseif ($v->prelim1 && $v->prelim2) {
				//compute final grade
				$ave = ($v->prelim1 + $v->prelim2) / 2;
				$round = floor($ave * 10) / 10;
				$grade = number_format($round, 1);

				//update grade
				DB::statement("
					UPDATE registration
					SET
						prelim1=?,
						prelim2=?,
						grade=?,
						gcompl=?
					WHERE
						studid=? AND
						subjcode=? AND
						section=? AND
						sy=? AND
						sem=?
				", array(
					$v->prelim1,
					$v->prelim2,
					$grade,
					$v->gcompl,
					$v->studid,
					$v->subjcode,
					$v->section,
					$v->sy,
					$v->sem
				));
			}
		}
		return true;
	}

	public function lock($data, $lock) {
		DB::statement("
			UPDATE semsubject
			SET
				lock=?
			WHERE
				subjcode=? AND
				section=? AND
				sy=? AND
				sem=?
		", array(
			$lock,
			$data->subjcode,
			$data->section,
			$data->sy,
			$data->sem
		));

		return true;
	}
}