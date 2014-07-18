<?php

class BulkCollectionHeader extends \Eloquent {

	protected $table = 'bulk_collection_header';
	protected $primaryKey = 'refno';
	protected $fillable = [
		'refno',
		'fund',
		'sy',
		'sem',
		'studid',
		'payee',
		'bcode',
		'paydate',
		'lastuser'
	];
	public $timestamps = false;

	public function details()
	{
		return $this->hasMany('BulkCollectionDetails', 'refno', 'refno');
	}
}