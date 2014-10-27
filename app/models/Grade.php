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
		return static::_encode($data);
	}

	public function store($data)
	{
		foreach ($data as $v) {
			if($v->isChanged) {
				//if grades are deleted
				if(is_null($v->prelim1) && is_null($v->prelim2)) {
					DB::statement("
							UPDATE registration
							SET
								prelim1=Null,
								prelim2=Null,
								grade=Null,
								gcompl=Null
							WHERE
								studid=? AND
								subjcode=? AND
								section=? AND
								sy=? AND
								sem=?
						", array($v->studid, $v->subjcode, $v->section, $v->sy, $v->sem));
				}
				else {
					//encoding midterm grades only
					if(is_numeric($v->prelim1) && !$v->prelim2) {
						$prelim1 = number_format($v->prelim1, 1);

						//update prelim1
						DB::statement("
							UPDATE registration
							SET
								prelim1=?,
								prelim2=Null,
								grade=Null,
								gcompl=Null
							WHERE
								studid=? AND
								subjcode=? AND
								section=? AND
								sy=? AND
								sem=?
						", array($prelim1, $v->studid, $v->subjcode, $v->section, $v->sy, $v->sem));
					}
					//encoding both midterm and final term
					elseif (is_numeric($v->prelim1) && is_numeric($v->prelim2)) {
						//compute final grade
						$ave = ($v->prelim1 + $v->prelim2) / 2;
						$nf = number_format($ave, 2);
						$grade = number_format(floor($nf * 10) / 10, 1);

						$prelim1 = number_format($v->prelim1, 1);
						$prelim2 = number_format($v->prelim2, 1);

						//if if no removal grade
						if(!$v->gcompl) {
							//update grade
							DB::statement("
								UPDATE registration
								SET
									prelim1=?,
									prelim2=?,
									grade=?,
									gcompl=Null
								WHERE
									studid=? AND
									subjcode=? AND
									section=? AND
									sy=? AND
									sem=?
							", array($prelim1, $prelim2, $grade, $v->studid, $v->subjcode, $v->section, $v->sy, $v->sem));
						}
						//if has removal grade
						else {
							$gcompl = number_format($v->gcompl, 1);

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
							", array($prelim1, $prelim2, $grade, $gcompl, $v->studid, $v->subjcode, $v->section, $v->sy, $v->sem));
						}
					}
					//if dropped on final term
					elseif (is_numeric($v->prelim1) && !is_numeric($v->prelim2)) {
						//update grade
						$prelim1 = number_format($v->prelim1, 1);
						$prelim2 = strtoupper($v->prelim2);
						DB::statement("
								UPDATE registration
								SET
									prelim1=?,
									prelim2=?,
									grade=Null,
									gcompl=Null
								WHERE
									studid=? AND
									subjcode=? AND
									section=? AND
									sy=? AND
									sem=?
							", array($prelim1, $prelim2, $v->studid, $v->subjcode, $v->section, $v->sy, $v->sem));
					}
					//if dropped on midterm and finalterm
					elseif (!is_numeric($v->prelim1) || !is_numeric($v->prelim2)) {
						$prelim1 = strtoupper($v->prelim1);
						$prelim2 = strtoupper($v->prelim2);
						//update grade
						DB::statement("
								UPDATE registration
								SET
									prelim1=?,
									prelim2=?,
									grade=Null,
									gcompl=Null
								WHERE
									studid=? AND
									subjcode=? AND
									section=? AND
									sy=? AND
									sem=?
							", array($prelim1, $prelim2, $v->studid, $v->subjcode, $v->section, $v->sy, $v->sem));
					}
				}
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