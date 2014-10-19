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

		public function getStudentSubjects() {
			//Get subjects all enrolled subject of a student
			$data = [];

			$data['subj'] = DB::select("
				SELECT *
				FROM registration
				WHERE
					studid = '016351' AND
					sy = '2014-2015' AND
					sem = '1'
			");

			$data['meta'] = DB::select("
				SELECT
					studid,
					studfullname,
					studmajor,
					studlevel
				FROM student
				LEFT JOIN semstudent
				USING(studid)
				WHERE
					studid = '016351' AND
					sy = '2014-2015' AND
					sem = '1'
			");

			return $data;
		}
	}

?>