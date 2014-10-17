<?php

class Subject extends \Eloquent {
	use \Helper;

	public static function getEnrolledSubjects($studid, $sy, $sem)
	{
		try {
			return DB::select("SELECT * FROM registration LEFT JOIN subject USING(subjcode) WHERE studid=? AND sy=? AND sem=?", array($studid, $sy, $sem));
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 409);
		}
	}

	public static function search($q) {
		extract($q);

		if (is_array($q)) {

			$data = DB::table('semsubject')
				->whereIn('subjcode', $q)
				->get(array('subjcode', 'section'));

			return static::_encode($data);

		} else {
			$data = DB::table('semsubject')
					->where('subjcode', $q)
					->get(['subjcode', 'section']);

			
			$r[0] = static::_encode($data[0]);

			if(isset($d)) {
				return $r[0];
			} else {
				return $r;
			}
		}
	}

	public static function getOfferedSubjects($subjcode, $sy, $sem)
	{
		try {
			return DB::select("SELECT * FROM subject LEFT JOIN semsubject USING(subjcode) WHERE subjcode=? AND sy=? AND sem=?", array($subjcode, $sy, $sem));
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 409);
		}
	}
}