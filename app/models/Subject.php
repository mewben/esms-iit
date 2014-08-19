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
}