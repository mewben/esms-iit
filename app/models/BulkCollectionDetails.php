<?php

class BulkCollectionDetails extends \Eloquent {

	protected $table = 'bulk_collection_details';
	protected $primaryKey = null;
	protected $fillable = [
		'refno',
		'feecode',
		'amt',
		'lastuser'
	];
	public $timestamps = false;
}