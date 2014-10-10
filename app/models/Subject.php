<?php

class Subject {

	public static function getEnrolledSubjects($studid, $sy, $sem)
	{
		try {
			return DB::select("SELECT * FROM registration LEFT JOIN subject USING(subjcode) WHERE studid=? AND sy=? AND sem=?", array($studid, $sy, $sem));
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 409);
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