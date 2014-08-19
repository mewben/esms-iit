<?php

class Collection {

	public static function getPaid($studid, $sy, $sem)
	{
		try {
			return DB::select("SELECT * FROM get_paid(?, ?, ?)", array($studid, $sy, $sem));
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 409);
		}
	}
}