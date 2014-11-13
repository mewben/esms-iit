<?php 

	class Registration extends \Eloquent {
		use \Helper;

		protected $table = 'registration';
		protected $fillable = ['prelim1', 'prelim2', 'grade', 'gcompl', 'lock', 'remarks'];
		public $timestamps = false;

		public function getStudentSubjects($q) {
			extract($q);
			$data = [];

			$data['subj'] = DB::select("
				SELECT
					sy, sem, studid, subjcode, section, prelim1, prelim2, grade, gcompl, subjdesc, subjlec, subjlab, subjlec_units, subjlab_units, subjgpa, subjcredit, subjdept
				FROM registration
				LEFT JOIN subject
				USING(subjcode)
				WHERE
					studid=? AND
					sy=? AND
					sem=?
			", array($studid, $sy, $sem));

			//encode uricomponents & set isEmpty flag
			foreach ($data['subj'] as $v) {
				$v->subjcoded = rawurlencode($v->subjcode);
				$v->isEmpty = "";
			}

			$meta = DB::select("
				SELECT
					studid, studfullname, studmajor, studlevel, sy, sem, studmajor, regdate
				FROM student
				LEFT JOIN semstudent
				USING(studid)
				WHERE
					studid=? AND
					sy=? AND
					sem=?
			", array($studid, $sy, $sem));
			if($meta) {
				$data['meta'] = $meta[0];
			}

			//Set GPA and Load Units
			//$data['meta']->gpa = static::getGPA($data['subj']);
			$data['meta']->units = static::getUnits($data['subj']);

			return static::_encode($data);
		}

		public function getGPA($data) {
			$lec = 0;
			$lab = 0;
			$grade = 0;
			$total = 0;
			$gpa = 0;
			$subjwgrade = 0;
			$subjforgpa = 0;

			foreach ($data as $v) {
				if($v->grade > 3) {
					if ($v->subjgpa == 1 && is_numeric($v->prelim1) && is_numeric($v->prelim2) && is_numeric($v->gcompl)) {
						$subjwgrade++;
					}
				} else {
					if ($v->subjgpa == 1 && is_numeric($v->prelim1) && is_numeric($v->prelim2)) {
						$subjwgrade++;
					}
				}
				if ($v->subjgpa && is_numeric($v->prelim1) && is_numeric($v->prelim2)) {
					$lec += $v->subjlec_units;
					$lab += $v->subjlab_units;
					if(!$v->gcompl) {
						$grade = $v->grade;
					} else {
						$grade = $v->gcompl;
					}
					$total += $grade * ($v->subjlec_units + $v->subjlab_units);
					$subjforgpa++;
				}
			}
			
			if($subjwgrade == $subjforgpa) {
				$gpa = $total / ($lec + $lab);
				return number_format($gpa, 5);
			}
			return 'N/A';
		}

		public function getUnits($data) {
			$lec = 0;
			$lab = 0;
			$units = 0;

			foreach ($data as $v) {
				$lec += $v->subjlec_units;
				$lab += $v->subjlab_units;
			}
			$units = $lec + $lab;
			return $units;
		}
	}

?>