<?php

trait Helper {

	/**
	 * Encodes to utf8. 
	 * Issue on the database on &Ntilde;
	 */
	public static function _encode($v)
	{
		if (is_object($v))	$v = (array) $v;
		if (is_array($v)) {
			$new = array();
			foreach ($v as $k => $val) {
				$new[$k] = static::_encode($val);
			}
		} else { // string
			$new = utf8_encode($v);
		}

		return $new;
	}

	/**
	 * Sanitizes the values using e()
	 */
	public static function _sanitize(&$input)
	{
		array_walk_recursive($input, function(&$v) {
			$v = e($v);
		});

		return $input;
	}
}