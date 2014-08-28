<?php

class BcodesController extends \BaseController {

	protected $model;

	public function __construct(Bcode $model)
	{
		$this->model = $model;
	}

	public function index()
	{
		return Response::json($this->model->getAll());
	}

	public function store()
	{
		$data['bcode'] = $this->model->store(Input::all()['bcode']);
		return Response::json($data);
	}

	public function update($id)
	{
		$data['bcode'] = $this->model->store(Input::all()['bcode'], $id);
		return Response::json($data);
	}

	public function destroy($id)
	{
		return Response::json($this->model->destroy($id));
	}
}