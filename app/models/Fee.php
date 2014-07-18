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
			throw new Exception('No assessment.', 409);

		return $unpaid;
	}

	public function getPayment($data)
	{
		if (isset($data['cat'])) {
			if ($data['cat'] == 'payee')
				$data['q'] = strtoupper($data['q']);

			$model = new BulkCollectionHeader;
			$model = $model->where($data['cat'], 'LIKE', '%' . $data['q'] . '%');

			$data = $model->get()->toArray();
			if (count($data) == 1) {
				// return all the data
				return $this->_loadPayment($data[0]['refno']);
			} else {
				// return the list
				return $data;
			}
		} else {
			// find refno
			return $this->_loadPayment($data['q']);
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