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

		$excess = self::checkRefund($studid, $sy, $sem);

		// Refund Header Contents
		$data['h']['sy'] = $sy;
		$data['h']['sem'] = $sem;
		$data['h']['studid'] = $studid;
		$data['h']['refno'] = $refno;
		$data['h']['payee'] = $payee;
		$data['h']['remarks'] = $remarks;
		$data['h']['paydate'] = $paydate;

		// Refund Detail Contents
		$i = 0;
		$t = 0;
		foreach ($detail as $v) {
			if ($v['amount'])  {		
				$data['d'][$i]['refno'] = $refno;
				$data['d'][$i]['feecode'] = $v['feecode'];
				$data['d'][$i]['amt'] = $v['amount'];
				$t += $v['amount'];
			}
			$i++;
		}

		if($t > $excess)	throw new Exception("Amount refunded is greater than the refundable amount.", 409);

		// Insert to refund
		DB::transaction(function() use ($data) {
			Refund::create($data['h']);
			DB::table('refund_details')->insert($data['d']);
			return true;
		});
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
		// get refunded
		$refunded = DB::select("
			SELECT *
			FROM refund_header
			LEFT JOIN refund_details
			USING(refno)
			WHERE
				studid=? AND
				sy=? AND
				sem=?
		", array($studid, $sy, $sem));

		// Transform arrays into feecodes key
		foreach ($ass as $v) {
			$d_ass[$v->feecode] = $v->amt;
		}
		foreach ($paid as $v) {
			$d_paid[$v->feecode] = $v->amt;
		}
		foreach ($refunded as $v) {
			$d_refunded[$v->feecode] = $v->amt;
		}
		$diff = array_diff_assoc($d_paid, $d_ass);

		// Remove refunded amount
		if($refunded) {
			foreach($d_refunded as $k => $v) {
				$pi = $d_ass[$k] - $d_paid[$k] - $v;
				if($pi != 0)	unset($diff[$k]);
			}
		}

		// DELETED ENTRY ON ASSESSMENT ARE NOT SHOWN ON REFUND BREAKDOWN!!!
		// NEED TO CHECK IF ENTRIES IN PAYMENT DON'T EXCESS IN ASSESMENT

		// Finalize refund breakdown
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

		// Return student studid, studfullname, amt
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