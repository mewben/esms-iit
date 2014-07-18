<?php

class Report {

	public function getCollections($q)
	{
		extract($q);
		$data['datefrom'] = '2014-06-01';
		$data['dateto'] = '2014-07-14';
		$data['fund'] = 'STF';
		if ($bcode == 'CASHIER') {
			$rep = DB::select("SELECT * FROM get_bulkcollections_bydate(?, ?, ?, ?, ?)", array($datefrom, $dateto, $fund, '', ''));
		} else {
			$rep = DB::select("SELECT * FROM get_bulkcollections_bydate(?, ?, ?, ?, ?)", array($datefrom, $dateto, $fund, '1', $bcode));
		}
		
		$exp = [];
		$summary = [];
		if ($rep) {
			$c = 1;
			foreach ($rep as $k => $v) {
				$summary[$v->acctcode]['name'] = $v->acctname;
				$summary[$v->acctcode]['total'][] = $v->amount;
				if ((isset($rep[$k - 1]->refno) && $rep[$k - 1]->refno != $v->refno) || $k == 0) {
					$exp[$k] = array(
						$v->paydate,
						$v->refno,
						$v->studid,
						$v->payee,
						$v->acctcode,
						$v->acctname,
						$v->amount,
						'=SUM(G'. ($k+1) .':G'. ($k+1) .')'
					);
					if ($c > 1) {
						$exp[$k - $c][7] = '=SUM(G' . ($k - $c + 1) . ':G' . ($k) . ')';
						$c = 1;
					}
				} else {
					$exp[$k] = array(
						'',
						'',
						'',
						'',
						$v->acctcode,
						$v->acctname,
						$v->amount
					);

					if ($k == (count($rep) - 1)) {
						if ($c > 1) {
							$exp[$k - $c][7] = '=SUM(G' . ($k - $c + 1) . ':G' . ($k + 1) . ')';
							$c = 1;
						}
					}

					$c++;
				}
			}

			$x = count($exp);
			$exp[$x++] = array(
				'',
				'',
				'',
				'',
				'',
				'',
				'TOTAL:',
				'=SUM(H1:H' . ($x -1) . ')'
			);
		}

		$exp[$x++] = array();
		$exp[$x++] = array('', '', '', 'SUMMARY:');
		$c = $x;
		foreach ($summary as $k => $v) {
			$exp[$x][] = '';
			$exp[$x][] = '';
			$exp[$x][] = '';
			$exp[$x][] = '';
			$exp[$x]['code'] = $k;
			$exp[$x]['name'] = $v['name'];
			$exp[$x]['total'] = array_sum($v['total']);
			$x++;
		}
		$exp[$x] = array(
			'',
			'',
			'',
			'',
			'TOTAL:',
			'',
			'=SUM(G' . $c . ':G' . $x . ')'
		);

		Excel::create('Collections', function($excel) use ($exp) {
			$excel->setTitle('Our new awesome title');
			$excel->setDescription('A demonstration');

			$excel->sheet('Sheet 1', function($sheet) use ($exp) {
				$sheet->fromArray($exp, null, 'A1', false, false);
			});
		})->download('xlsx');
	}

	public function getReceivables($q)
	{
		extract($q);
		DB::beginTransaction();

			$conn = DB::connection()->getPdo();

			$query = $conn->prepare("SELECT * FROM get_studbalance(?, ?, ?, 'cursor', ?)");
			$query->execute(array($sy, $sem, $college, $date));

			$query = $conn->query('FETCH ALL IN cursor');
			$results = $query->fetchAll();

		DB::commit();
		unset($query);

		// prepare results
		foreach ($results as $k => $v) {
			$data['data'][$k] = [
				'studid' 		=> $v['studid'],
				'fullname' 		=> utf8_encode($v['fullname']),
				'total_assess' 	=> $v['total_assess'],
				'total_pay'		=> $v['total_pay'],
				'total_refund'	=> $v['total_refund'],
				'balance'		=> $v['balance'],
				'studmajor'		=> $v['studmajor'],
				'schdesc'		=> $v['schdesc']
			];
		}
		$data['total'] = [
			'count' => count($data['data']),
			'assess' => array_sum(array_column($data['data'], 'total_assess')),
			'paid' => array_sum(array_column($data['data'], 'total_pay')),
			'refund' => array_sum(array_column($data['data'], 'total_refund')),
			'balance' => array_sum(array_column($data['data'], 'balance'))
		];

		return $data;
	}

	public function getSubLedger($id)
	{
		$data = [];

		$res = DB::select("SELECT * FROM get_studentsubledger(?)", [$id]);
		foreach ($res as $key => $v) {
			$v->num = $key + 1;
			$k = $v->sy. ' &bull; '. $v->sem;
			$data['data'][$k]['name'] = $k;
			$data['data'][$k]['data'][] = $v;
		}

		$data['data'] = array_values($data['data']);
		$data['details'] = Student::getStudentWithMajor($id, $v->sy, $v->sem);

		return $data;
	}

	public function getSumBilling($q)
	{
		$data = [];
		$res = DB::select("SELECT * FROM get_summaryofbilling2(?, ?)", [$q['sy'], $q['sem']]);
		$data['data'] = $res;
		$data['sy'] = $q['sy'];
		$data['sem'] = $q['sem'];
		$data['total'] = 0;
		foreach ($res as $v) {
			$data['total'] += $v->amount;
		}

		if (isset($q['model']))	{ // for model structure
			$ret = [];
			$ret['data'] = $data;
			return $ret;
		} else {
			return $data;
		}
	}

}