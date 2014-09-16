<?php

class Report {
	use \Helper;

	/**
	 * Gets the data for the certificate of billing
	 *
	 * @param 	array $q 'studid', 'sy', 'sem'
	 * @return 	array assoc
	 *
	 * @ignore 	return data
	 * ---
	 *	'h' => 'studid', 'sy', 'sem', 'studfullname2', 'studmajor', 'studlevel', 'tuiamt', 'labamt', 't_unit', 't_as', 't_misc'
	 * 	's' => array => 'subjcodedsp', 'subjlec_units', 'subjlab_units', 'subjcredit'
	 * 	'misc' => array
	 *	'lab' => object
	 * 	'reg' => object
	 * 	'tui' => object
	 * 	'refund' => object
	 * ---
	 */
	public function getCertBilling($q)
	{
		$data = [];
		extract($q);

		// Get headers
		$h = DB::select("
			SELECT studid, t1.sy, t1.sem, studfullname2, studmajor, studlevel, t2.amt AS tuiamt, t3.amt AS labamt 
			FROM semstudent AS t1 
			LEFT JOIN student USING(studid)
			LEFT JOIN tuitionmatrix_new AS t2
				ON (
					t1.payment_sy = t2.sy AND
					t1.payment_sem = t2.sem AND
					t1.studmajor = t2.progcode
				)
			LEFT JOIN labmatrix_new AS t3
				ON (
					t1.payment_sy = t3.sy AND
					t1.payment_sem = t3.sem
				)
			WHERE studid=? AND t1.sy=? AND t1.sem=? AND t3.subjcode='DEFAULT        ' AND registered=true
		", array($studid, $sy, $sem));

		if (!$h)	throw new Exception('Student Not Enrolled in this Semester', 409);
		
		$h = static::_encode($h);
		$data['h'] = $h[0];

		// Get the subjects enrolled with lec lab total units
		$subj = Subject::getEnrolledSubjects($studid, $sy, $sem);
		$data['s'] = $subj;
		$t = 0;

		foreach ($subj as $v) {
			$t += $v->subjcredit;
		}
		$data['h']['t_unit'] = number_format($t, 2);

		// Get assessment details
		$as = Assessment::getAssessmentDetails($studid, $sy, $sem);
		$t = 0;
		$m = 0;
		foreach ($as as $v) {
			switch($v->feecode) {
				case 'TUITIONFEE  ':
					$data['tui'] = $v;
					break;
				case 'REGFEE      ':
					$data['reg'] = $v;
					break;
				case 'LABFEE      ':
					$data['lab'] = $v;
					break;
				default:
					$data['misc'][] = $v;
					$m += $v->amt;
			}
			$t += $v->amt;
		}

		$data['h']['t_as'] = $t;
		$data['h']['t_misc'] = $m;

		// Get paid
		$p = Collection::getPaid($studid, $sy, $sem);
		$data['paid'] = $p;
		$data['h']['t_paid'] = null;
		
		$t = 0;
		foreach ($p as $v) {
			$t += $v->amt;
		}
		$data['h']['t_paid'] = $t;

		// Get Refund
		$r = Refund::getRefund($studid, $sy, $sem);
		$data['h']['t_refund'] = null;
		if ($r) {
			$data['refund'] = $r;
			$t = 0;
			foreach ($r as $v) {
				$t += $v->amt;
			}
			$data['h']['t_refund'] = $t;
		}

		$data['h']['bal'] = $data['h']['t_as'] - $data['h']['t_paid'] + $data['h']['t_refund'];
		$data['currentDate'] = date('Y-m-d');
		
		return $data;
	}

	public function getCollectionSummary($q)
	{
		$ret['res']['meta'] = $q;
		extract($q);

		if($bcode == 'CASHIER') {
			$r = DB::select("SELECT * FROM get_summaryofcollectionbydate(?, ?, ?, ?, ?)", array($datefrom, $dateto, $fund, '', ''));
		} else {
			$r = DB::select("SELECT * FROM get_summaryofcollectionbydate(?, ?, ?, ?, ?)", array($datefrom, $dateto, $fund, '1', $bcode));
		}

		// get total
		$total = 0;
		foreach ($r as $v) {
			$total += $v->amount;
		}

		$ret['res']['data'] = $r;
		$ret['res']['meta']['total'] = $total;

		if(isset($print) && $print)
			return $ret;
		else
			return $ret['res'];
	}

	// for export
	public function getCollections($q)
	{
		extract($q);

		if ($bcode == 'CASHIER') {
			$rep = DB::select("SELECT * FROM get_bulkcollections_bydate(?, ?, ?, ?, ?)", array($datefrom, $dateto, $fund, '', ''));
		} else {
			$rep = DB::select("SELECT * FROM get_bulkcollections_bydate(?, ?, ?, ?, ?)", array($datefrom, $dateto, $fund, '1', $bcode));
		}

		$ret = array();
		$data = array();
		$i = 1;
		foreach ($rep as $key => $value) {
			$value->payee = static::_encode($value->payee);
			if (array_key_exists($value->refno, $data)) {
				$data[$value->refno]->adtl[] = $value;

				$num_adtl = count($data[$value->refno]->adtl);

				$data[$value->refno]->s2 = $data[$value->refno]->s1 + $num_adtl;
			
			} else {
				$value->i = $i;
				$value->s1 = $key+1;
				$value->s2 = $key+1;
				
				$data[$value->refno] = $value;
				
				$i++;
			}
		}
		
		$ret['data'] = array_values($data);
		$ret['meta']['total'] = count($rep);

		return $ret;
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

		if (isset($q['print']))	{ // for model structure
			$ret = [];
			$ret['data'] = $data;
			return $ret;
		} else {
			return $data;
		}
	}

}