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

		/**
		* Update student Grade
		*
		* @param 
		*/
		public function updateGrades()
		{
			DB::statement("UPDATE registration SET prelim1='1.0', prelim2='1.0', grade='1.0' WHERE sy=? AND sem=? AND subjcode=? AND studid=?", array('2014-2015', '1', 'ME 1A', '016351'));
			// DB::table('registration')
			// 	->where('studid', '016351')
			// 	->where('subjcode', 'ME 1A')
			// 	->where('sy', '2014-2015')
			// 	->where('sem', '1')
			// 	->update(array(
			// 			'prelim1' => '1.0',
			// 			'prelim2' => '1.0',
			// 			'grade' => '1.0',
			// 		));
			return 'hello!';			

			//return DB::select("SELECT * FROM registration WHERE sy=? AND sem=? AND subjcode=? AND studid=?", array('2014-2015', '1', 'ME 1A', '016351'));
		}
	}

?>