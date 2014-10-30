<?php

class AdminController extends \BaseController {

	public function change_semester()
	{
		$data = Input::all();

		// TODO: change session here
		$sem = Semester::where('sy', $data['sy'])
					->where('sem', $data['sem'])
					->get()
					->toArray();

		if (!$sem)	throw new Exception('Invalid Semester', 409);
		
		Session::put('user.sem', $sem[0]);
		return Response::json($sem[0]);
	}

	public function doLogin()
	{
		$ismember = DB::select("SELECT * FROM ismember('" . Input::get('username') . "', 'ngas_admin')");
		$ismember = $ismember[0];
		
		if($ismember->ismember) {
			$menu = [
						[
				          'group' => 'Actions',
				          'print' => false,
				          'icon' => 'fa-desktop',
				          'items' => [
				            ['type' => 'route', 'title'  => 'Refund', 'location'  => 'refund']
				          ]
				        ],
				        [
				        	'group' => 'Reports',
				        	'print' => false,
				        	'icon' => 'fa-list-alt',
				        	'items' => [
				        		['type' => 'route', 'title' => 'Account Receivables', 'location' => 'receivables'],
				        		['type' => 'route', 'title' => 'Report of Collections', 'location' => 'collections'],
				            	['type' => 'route', 'title' => 'Summary of Billing', 'location' => 'sumbilling'],
				            	['type' => 'route', 'title' => 'Report of Refunds', 'location' => 'refunds'],

				            	['type' => 'resource', 'title' => 'Certificate of Billing', 'location' => 'certbilling',
					              'sub' => [
					                ['route' => 'stud', 'path' => ':studid']
					              ]
					            ],
					            ['type' => 'resource', 'title' => 'Subsidiary Ledger', 'location' => 'ledgers',
					              'sub' => [
					                ['route' => 'ledger', 'path' => ':studid']
					              ]
					            ]
				        	]
				        ],
				        [
				        	'group' => 'Prints',
					          'print' => true,
					          'icon' => '',
					          'items' => [
					            ['type' => 'resource', 'title' => 'Print', 'location' => 'print',
					              'sub' => [
					                ['route' => 'certbilling', 'path' => ':certbilling/ =>studid'],
					                ['route' => 'collections', 'path' => ''],
					                ['route' => 'ledger', 'path' => 'ledger/:studid'],
					                ['route' => 'sumbilling', 'path' => ''],
					              ]
					            ]
					          ]
				        ]
					];
		} else {
			$menu = [
						[
				          'group' => 'Actions',
				          'print' => false,
				          'icon' => 'fa-desktop',
				          'items' => [
				          	['type' => 'route', 'title'  => 'Payment', 'location'  => 'payment'],
				            ['type' => 'route', 'title'  => 'Refund', 'location'  => 'refund'],
				            ['type' => 'resource', 'title' => 'Grades', 'location' => 'grades',
				            	'sub' => [
				            		['route' => 'grade', 'path' => '/:subjcode/:section']
				            	]
				            ],
				            ['type' => 'resource', 'title' => 'Registration', 'location' => 'registrations',
				            	'sub' => [
				            		['route' => 'registration', 'path' => '/:studid']
				            	]
				            ]
				          ]
				        ],
				        [
				        	'group' => 'Automate',
				        	'print' => false,
				        	'icon' => 'fa-crosshairs',
				        	'items' => [
				        		['type' => 'route', 'title'  => 'Import Payment', 'location'  => 'importpay']
				        	]
				        ],
				        [
				        	'group' => 'Manage',
				        	'print' => false,
				        	'icon' => 'fa-list-alt',
				        	'items' => [
				        		['type' => 'resource', 'title' => 'BCodes', 'location' => 'bcodes',
					              'sub' => [
					                ['route' => 'new', 'path' => '/new'],
					                ['route' => 'edit', 'paty' => ':/id/edit']
					              ]
					            ]
				        	]
				        ],
				        [
				        	'group' => 'Reports',
				        	'print' => false,
				        	'icon' => 'fa-list-alt',
				        	'items' => [
				        		['type' => 'route', 'title' => 'Account Receivables', 'location' => 'receivables'],
				        		['type' => 'route', 'title' => 'Report of Collections', 'location' => 'collections'],
				            	['type' => 'route', 'title' => 'Summary of Billing', 'location' => 'sumbilling'],
				            	['type' => 'route', 'title' => 'Report of Refunds', 'location' => 'refunds'],

				            	['type' => 'resource', 'title' => 'Certificate of Billing', 'location' => 'certbilling',
					              'sub' => [
					                ['route' => 'stud', 'path' => ':studid']
					              ]
					            ],
					            ['type' => 'resource', 'title' => 'Subsidiary Ledger', 'location' => 'ledgers',
					              'sub' => [
					                ['route' => 'ledger', 'path' => ':studid']
					              ]
					            ]
				        	]
				        ],
				        [
				        	'group' => 'Prints',
					          'print' => true,
					          'icon' => '',
					          'items' => [
					            ['type' => 'resource', 'title' => 'Print', 'location' => 'print',
					              'sub' => [
					                ['route' => 'certbilling', 'path' => ':certbilling/ =>studid'],
					                ['route' => 'collections', 'path' => ''],
					                ['route' => 'ledger', 'path' => 'ledger/:studid'],
					                ['route' => 'sumbilling', 'path' => ''],
					              ]
					            ]
					          ]
				        ]
					];
		}

		$sem = Semester::where('current', '=', 't')->get()->toArray();
		if (!$sem) {
			$sem[0] = array('sy' => '2014-2015', 'sem' => '1');
		} 
		$currentDate = date('Y-m-d');
			
		Session::put('user.sem', $sem[0]);
		Session::put('user.currentDate', $currentDate);
		Session::put('user.menu', $menu);

		return Redirect::to('/');
	}

	private function _in_array($q, $arr) {
		foreach ($arr as $v) {
			if($v->rolname == $q) {
				return true;
			}
		}
		return false;
	}
}
