<?php

class Fee extends \Eloquent {

	protected $table = 'fees';
	protected $primaryKey = 'feecode';

	public function getFees()
	{
		$model = new static;
		$model = $model->orderBy('feecode');
		$data = $model->get();

		return $data->toArray();
	}

	public function loadUnpaidAssessment($data)
	{
		if (!$data['q'] && !$data['sy'] && !$data['sem'])	throw new Exception('Either no Student Id, Sy, or Sem.', 409);
		
		$unpaid = DB::select("SELECT * FROM get_unpaidassessment(?, ?, ?)", array($data['sy'], $data['sem'], $data['q']));
		if (!$unpaid)
			throw new Exception('Zero Assessment or Zero Balance.', 409);

		return $unpaid;
	}

	public function getPayment($q)
	{
		extract($q);

		if (isset($cat)) {
			if ($cat == 'payee')
				$q = strtoupper($q);

			$model = new BulkCollectionHeader;
			$model = $model->with('details');
			$model = $model->where($cat, 'LIKE', '%' . $q . '%');
			$data = $model->get();

			$r = array();
			foreach ($data as $key => $value) {
				$r[$key]['studid'] = $value->studid;
				$r[$key]['payee'] = $value->payee;
				$r[$key]['refno'] = $value->refno;
				$r[$key]['amt'] = $value->details->sum('amt');
			}

			if (count($r) == 1) {
				// return all the data
				return $this->_loadPayment($r[0]['refno']);
			} else {
				// return the list
				return $r;
			}
		} else {
			// find refno
			return $this->_loadPayment($q);
		}
	}

	public function deletePayment($refno)
	{
		return BulkCollectionHeader::where('refno', $refno)->delete();
	}

	private function _loadPayment($refno)
	{
		$data['h'] = BulkCollectionHeader::where('refno', $refno)->first()->toArray();
		$data['details'] = DB::select("
			SELECT * FROM bulk_collection_details
				LEFT JOIN fees USING(feecode)
				WHERE refno=?
			", array($refno));
		return $data;
	}
}