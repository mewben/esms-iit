<?php

trait Helper {

	/**
	 * Encodes to utf8. 
	 * Issue on the database on &Ntilde;
	 */
	public static function encode($v)
	{
		if (is_object($v))	$v = (array) $v;
		if (is_array($v)) {
			$new = array();
			foreach ($v as $k => $val) {
				$new[$k] = static::encode($val);
			}
		} else { // string
			$new = utf8_encode($v);
		}

		return $new;
	}
}