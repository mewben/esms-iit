<?php

class Assessment {

	public static function getAssessmentDetails($studid, $sy, $sem)
	{
		try {
			return DB::select("SELECT * FROM ass_details LEFT JOIN fees USING(feecode) WHERE studid=? AND sy=? AND sem=?", array($studid, $sy, $sem));
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 409);
		}
	}
}