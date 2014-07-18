import Em from 'ember';

var Router = Em.Router.extend({
  location: EsmsUiENV.locationType
});

Router.map(function() {
	this.route('dashboard', {path: '/'});

	// Actions
	this.route('payment');

	// Automate
	this.route('importpay');

	// Reports
	this.route('collections');
	this.resource('ledgers', function() {
		this.route('search', {path: 'search/:studid'});
		this.route('ledger', {path: ':studid'});
	});
	this.resource('refunds', function() {
		this.route('new');
		this.route('edit', {path: ':id'});
	});
	this.route('receivables');
	this.route('sumbilling');


	// Print
	this.resource('print', function() {
		this.route('ledger', {path: 'ledger/:studid'});
		this.route('sumbilling', {path: 'sumbilling/:sy/:sem'});
	});

});

export default Router;
