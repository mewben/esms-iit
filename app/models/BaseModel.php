<?php

use Watson\Validating\ValidatingTrait;

class BaseModel extends \Eloquent {
	use ValidatingTrait;
	use \Helper;

	public function store($input, $id = null)
	{
		$input = static::_sanitize($input);

		if(!isset($id))	$model = new static;
		else 			$model = static::findOrFail($id);

		$model->fill($input);

		if (!$model->save())	throw new Exception($model->getErrors(), 408);

		return $model->toArray();
	}
}