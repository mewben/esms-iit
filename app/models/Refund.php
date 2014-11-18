<?php

class Refund extends \Eloquent {
	use \Helper;

	protected $table = 'refund_header';
	protected $primaryKey = 'refno';
	protected $fillable = [
		'refno',
		'sy',
		'sem',
		'studid',
		'payee',
		'paydate',
		'remarks'
	];
	public $timestamps = false;

	/**
	 * Delete a refund
	 *
	 * @param 	string $refno Reference number
	 */
	public function deleteRefund($refno)
	{
		return Refund::where('refno', $refno)->delete();
	}

	/**
	 * Do the actual save into the refunds table
	 *
	 * @param 	array The data to be saved.
	 * @return 	boolean True if successfull
	 * @ignore 	
	 * ---
	 * array_composition
	 *		'h' => array 'refno', 'sy', 'sem', 'studid', 'payee', 'bcode', 'paydate', 'remarks'
	 * 		'details' => array > array 'refno', 'feecode', 'amt'
	 * ---
	 */
	public static function make($data)
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
			Refund::create($data['h']);
			DB::table('refund_details')->insert($data['details']);
			return true;
		});
	}

	/**
	 * Processes the refund
	 *
	 * @param 	array $q 'refno', 'sy', 'sem', 'studid', 'payee', 'payee', 'paydate', 'remarks'
	 * @return 	boolean True if saved
	 */
	public function process($q)
	{
		extract($q);
		$bcode = 'RF'; // Hardcode bcode for Refund

		$excess = self::checkRefund($studid, $sy, $sem);
		$total_ass = 0;
		$total_paid = 0;

		// get assessment
		$ass = DB::select("SELECT * FROM ass_details WHERE studid=? AND sy=? AND sem=?", array($studid, $sy, $sem));

		// get paid with details
		$paid = DB::select("SELECT * FROM get_paiddetails(?,?,?)", array($studid, $sy, $sem));

		// Transform arrays into feecodes key
		foreach ($ass as $v) {
			$d_ass[$v->feecode] = $v->amt;
		}
		foreach ($paid as $v) {
			$d_paid[$v->feecode] = $v->amt;
		}

		$diff = array_diff_assoc($d_paid, $d_ass);

		// Prepare the data to be inserted in the refund_details
		$i = 0;
		foreach ($diff as $k => $v) {
			$d_ass[$k] = isset($d_ass[$k]) ? $d_ass[$k] : 0;
			$d_paid[$k] = isset($d_paid[$k]) ? $d_paid[$k] : 0;

			$ins[$i]['feecode'] = $k;
			$ins[$i]['amt'] = $d_paid[$k] - $d_ass[$k];
			$i++;
		}

		// Separate into Payment or Refund
		$p = array();
		$r = array();
		$t1 = 0;
		$t2 = 0;
		foreach ($ins as $v) {
			if($v['amt'] < 0) {
				// insert into bulk_collection_header
				$p['details'][] = array(
					'feecode' => $v['feecode'],
					'amt' => $v['amt'] * -1
				);
				$t1 += $v['amt'] * -1;
			} else {
				// insert into refund_header
				$r['details'][] = array(
					'feecode' => $v['feecode'],
					'amt' => $v['amt']
				);
				$t2 += $v['amt'];
			}
		}

		//check if data to be inserted is correct
		if ($excess - $t1 - $t2 != 0) 	throw new Exception("Amounts not equal.. Notify the developer.", 409);

		// Save to bulk_collection_header
		if (isset($p['details']) && count($p['details']) > 0) {
			$p['h'] = $q;
			(new Import)->payment($p);
		}

		// Save refund
		if (isset($r['details']) && count($r['details']) > 0) {
			$r['h'] = $q;
			self::make($r);
		}
		return true;
	}


	/**
	 * Search refund by refno, studid, lname
	 *
	 * @param 	array $q 'q', 'cat', 'sy', 'sem'
	 * @return 	array
	 */
	public function search($q)
	{
		extract($q);

		$q = strtoupper($q);

		$data = DB::table('refund_header')
			->where('refund_header.' . $cat, 'LIKE', '%' . $q . '%')
			->leftJoin('refund_details', 'refund_header.refno', '=', 'refund_details.refno')
			->groupBy('refund_header.refno')
			->groupBy('studid')
			->groupBy('payee')
			->groupBy('paydate')
			->get(array('studid', 'payee', 'refund_header.refno', 'paydate', DB::RAW('SUM(amt) AS amt')));

		return $data;
	}

	public function show($q)
	{
		extract($q);

		$data = static::where('refno', $refno)
					->where('sy', $sy)
					->where('sem', $sem)
					->get()
					->toArray();
		if (!$data)	throw new Exception('Reference # not found.');
		//$model = static::findOrFail($refno);

		//$data = $model->toArray();
		$amt = DB::select("SELECT SUM(amt) AS amt FROM refund_details WHERE refno = ?", array($refno));
		$data[0]['amt'] = $amt[0]->amt;

		return $data[0];
	}

	/**
	 * Checks and returns the refundable amount of a student
	 *
	 * @param 	array $q Associate array studid, sy, sem
	 * @throws 	Exception If no amount refundable for the semester
	 * @return 	array Returns studid, studfullname, amt
	 */
	public function check($q)
	{
		extract($q);

		$excess = self::checkRefund($studid, $sy, $sem);

		// get assessment
		$ass = DB::select("SELECT * FROM ass_details WHERE studid=? AND sy=? AND sem=?", array($studid, $sy, $sem));
		// get paid with details
		$paid = DB::select("SELECT * FROM get_paiddetails(?,?,?)", array($studid, $sy, $sem));

		// Transform arrays into feecodes key
		foreach ($ass as $v) {
			$d_ass[$v->feecode] = $v->amt;
		}
		foreach ($paid as $v) {
			$d_paid[$v->feecode] = $v->amt;
		}
		$diff = array_diff_assoc($d_paid, $d_ass);

		$i = 0;
		foreach ($ass as $val) {
			foreach ($diff as $k => $v) {
				if($val->feecode === $k) {
					$d_ref[$i] = new stdClass();
					$d_ref[$i]->feecode = $k;
					$d_ref[$i]->amount = number_format(($v - $val->amt), 2);
					$i++;
				}
			}
		}

		// return student studid, studfullname, amt
		$s = DB::select("SELECT studid, studfullname FROM student WHERE studid=?", array($studid));
		$s[0]->amount = $excess;
		$s[0]->detail = $d_ref;
		$s = static::_encode($s);

		return $s[0];
	}

	/**
	 * Checks if there is an amount refundable
	 *
	 * @param 	string $studid Student ID
	 * @param 	string $sy School-Year
	 * @param 	string $sem Semester
	 * @throws 	Exception If no amount
	 * @return 	number Excess amount
	 */
	private static function checkRefund($studid, $sy, $sem)
	{
		$ass = DB::select("SELECT SUM(amt) AS t FROM ass_details WHERE studid=? AND sy=? AND sem=?", array($studid, $sy, $sem));
		$paid = DB::select("SELECT SUM(amt) AS t FROM get_paid(?,?,?)", array($studid, $sy, $sem));
		$refund = DB::select("SELECT SUM(amt) AS t FROM get_refund(?,?,?)", array($studid, $sy, $sem));

		$excess = $paid[0]->t - $ass[0]->t - $refund[0]->t;
		if ($excess <= 0)	throw new Exception('No amount refundable for this semester.', 409);

		return $excess;
	}


	/**
	 * Gets the refund of a student
	 *
	 * @param 	string $studid
	 * @param 	string $sy
	 * @param 	string $sem
	 * @return 	array
	 */
	public static function getRefund($studid, $sy, $sem)
	{
		try {
			return DB::select("SELECT * FROM get_refund(?,?,?)", array($studid, $sy, $sem));
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 409);
		}
	}
}