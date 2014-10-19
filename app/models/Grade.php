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
			WHERE subjcode=?
		", array($subjcode));

		$data['meta'] = $subj[0];
		$data['meta']->section = $section;

 		//print_r($data);
		return $data;
	}

	public function save($data)
	{
		foreach ($data as $v) {
			extract($v);

			$ave = ($prelim1 + $prelim2) / 2;
			$grade = floor($ave * 10) / 10;

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
				$prelim1,
				$prelim2,
				$grade,
				$gcompl,
				$studid,
				$subjcode,
				$section,
				$sy,
				$sem
			));
		}
		return true;
	}
}