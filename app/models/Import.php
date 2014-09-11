<?php

class Import {

	// import payments from banks
	public function payments($data)
	{
		// 1. Get unpaid assessment of the student
		$unpaid = DB::select("SELECT * FROM get_unpaidassessment(?, ?, ?)", array($data['sy'], $data['sem'], $data['studid']));
		if (!$unpaid)
			throw new Exception('Zero assessment or Zero Balance', 409);

		// 2. Allocate paid from the unpaid assessment
		$amt = $data['amt'] - 0;
		$details = [];

		$t = 0;
		foreach($unpaid as $k => $v) {
			if ($amt > 0) {
				$sub = $amt - ($v->amt - 0);			
				$paid = ($sub < 0) ? $amt : $v->amt;
				$amt = $sub;

				$details[] = [
					'refno' => $data['refno'],
					'feecode' => $v->feecode,
					'amt' => $paid
				];

				$t += $paid;
			}
		}

		if ($t != $data['amt'])
			throw new Exception('Excess payment. Please use the Note payment module and set the excess to TUITIONFEE.', 409);
		
		try {
			//dd($data);
			// 3. Save to bulk_collection_header
			BulkCollectionHeader::create($data);

			// 4. Save to bulk_collection_details
			DB::table('bulk_collection_details')->insert($details);
			
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			//throw new Exception('This reference number is already saved.', 409);
		}
		return true;
	}

	// save single payment
	/*
	$data = array(
		'h' => array(
			'refno' => 'REF #',
			'sy' => '2014-2015',
			'sem' => '1'
			'studid' => '013515',
			'payee' => 'LASTNAME, FIRSTNAME',
			'bcode' => 'FCB',
			'paydate' => '2014-07-31',
		),
		'details' => array(
			0 => array(
				'refno' => 'REF #',
				'feecode' => 'REPDFEE',
				'amt' => '1000'
			)
		)
	);
	 */
	public function payment($data)
	{
		if (isset($data['h']['studid']) && $data['h']['studid'] == '')	unset($data['h']['studid']);

		$details = [];
		foreach ($data['details'] as $k => $v) {
			if ($v['amt'] != '' && $v['amt'] != 0) {
				$data['details'][$k]['refno'] = $data['h']['refno'];
				$details[$k]['refno'] = $data['h']['refno'];
				$details[$k]['feecode'] = $v['feecode'];
				$details[$k]['amt'] = $v['amt'];
			}
		}
		$data['details'] = $details;

		DB::transaction(function() use ($data) {
			BulkCollectionHeader::create($data['h']);
			DB::table('bulk_collection_details')->insert($data['details']);
			return true;
		});
	}
}