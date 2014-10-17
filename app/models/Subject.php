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

	public static function search($q)
	{
		extract($q);

		try {
			return DB::select("SELECT * FROM subject LEFT JOIN semsubject USING(subjcode) WHERE sy=? AND sem=? AND subjcode LIKE ?", array($sy, $sem, $subjcode.'%'));
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 409);
		}
	}
}