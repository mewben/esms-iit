<?php

class RefundsController extends \BaseController {

	protected $model;
	protected $data = array();

	public function __construct(Refund $model)
	{
		$this->model = $model;
		$this->data['sy'] = Session::get('user.sem.sy', '2014-2015');
		$this->data['sem'] = Session::get('user.sem.sem', '1');
	}

	public function check($id)
	{
		$this->data['studid'] = $id;
		return Response::json($this->model->check($this->data));
	}

	public function save()
	{
		$data = array_merge(Input::all(), $this->data);
		return Response::json($this->model->process($data));
	}

	public function search()
	{
		$data = array_merge(Input::all(), $this->data);
		return Response::json($this->model->search($data));
	}

	public function show($id)
	{
		$this->data['refno'] = $id;
		return Response::json($this->model->show($this->data));
	}

	public function destroy()
	{
		return Response::json($this->model->deleteRefund(Input::get('q')));
	}

}