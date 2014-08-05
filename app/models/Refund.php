<?php

class Refund extends \Eloquent {

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
		$q['bcode'] = 'RF'; // Hardcode bcode for Refund
		extract($q);

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

		$diff = array_diff_assoc($d_ass, $d_paid);

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
		foreach ($ins as $v) {
			if($v['amt'] < 0) {
				// insert into bulk_collection_header
				$p['details'][] = array(
					'feecode' => $v['feecode'],
					'amt' => $v['amt'] * -1
				);
			} else {
				// insert into refund_header
				$r['details'][] = array(
					'feecode' => $v['feecode'],
					'amt' => $v['amt']
				);
			}
		}

		// Save to bulk_collection_header
		if (count($p['details']) > 0) {
			$p['h'] = $q;
			(new Import)->payment($p);
		}

		// Save refund
		if (count($r['details']) > 0) {
			$r['h'] = $q;
			self::make($r);
		}
		return true;
	}

	/**
	 * shows the refundable amount of a student
	 *
	 * @param 	array $q Associate array studid, sy, sem
	 * @throws 	Exception If no amount refundable for the semester
	 * @return 	array Returns studid, studfullname, amt
	 */
	public function show($q)
	{
		extract($q);

		$excess = self::checkRefund($studid, $sy, $sem);

		// return student studid, studfullname, amt
		$s = DB::select("SELECT studid, studfullname FROM student WHERE studid=?", array($studid));
		$s[0]->amt = $excess;

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
		$refund = DB::select("SELECT SUM(amt) AS t FROM refund_details WHERE studid=? AND sy=? AND sem=?", array($studid, $sy, $sem));
	
		$excess = $paid[0]->t - $ass[0]->t + $refund[0]->t;
		if ($excess <= 0)	throw new Exception('No amount refundable for this semester.', 409);

		return $excess;
	}
}