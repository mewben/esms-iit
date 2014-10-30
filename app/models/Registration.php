<?php 

	class Registration extends \Eloquent {
		use \Helper;

		protected $table = 'registration';
		protected $fillable = [
			'prelim1',
			'prelim2',
			'grade',
			'gcompl',
			'lock',
			'remarks'
		];
		public $timestamps = false;

		public function getStudentSubjects($q) {
			extract($q);

			//Get subjects all enrolled subject of a student
			$data = [];

			$data['subj'] = DB::select("
				SELECT
					sy, sem, studid, subjcode, section, prelim1, prelim2, grade, gcompl, subjdesc, subjlec, subjlab, subjlec_units, subjlab_units, subjgpa
				FROM registration
				LEFT JOIN subject
				USING(subjcode)
				WHERE
					studid=? AND
					sy=? AND
					sem=?
			", array($studid, $sy, $sem));

			//encode uricomponents
			foreach ($data['subj'] as $v) {
				$v->subjcoded = rawurlencode($v->subjcode);
				$v->isEmpty = "";
			}

			$meta = DB::select("
				SELECT
					studid,
					studfullname,
					studmajor,
					studlevel,
					sy,
					sem
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

			return static::_encode($data);
		}
	}

?>