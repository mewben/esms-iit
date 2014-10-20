<?php 

	class Registration extends \Eloquent {
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
					sy, sem, studid, subjcode, section, prelim1, prelim2, grade, gcompl, subjdesc, subjlec, subjlab
				FROM registration
				LEFT JOIN subject
				USING(subjcode)
				WHERE
					studid=? AND
					sy=? AND
					sem=?
			", array($studid, $sy, $sem));

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
			$data['meta'] = $meta[0];

			return $data;
		}
	}

?>