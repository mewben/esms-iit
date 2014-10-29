import Em from 'ember';

var Router = Em.Router.extend({
  location: EsmsUiENV.locationType
});

Router.map(function() {
	this.route('dashboard', {path: '/'});

	// Actions
	this.route('payment');
	this.route('refund');

	// Automate
	this.route('importpay');

	// Reports
	this.resource('certbilling', function() {
		this.route('stud', {path: ':studid'});
	});
	this.route('collections');
	this.resource('ledgers', function() {
		this.route('ledger', {path: ':studid'});
	});
	this.route('receivables');
	this.route('refunds');
	this.route('sumbilling');

	// Manage
	this.resource('bcodes', function() {
		this.route('new', {path: '/new'});
		this.route('edit', {path: '/:id/edit'});
	});

	// Print
	this.resource('print', function() {
		this.route('certbilling', {path: 'certbilling/:studid'});
		this.route('collections');
		this.route('refunds');
		this.route('ledger', {path: 'ledger/:studid'});
		this.route('sumbilling');
		this.route('grade');
		this.route('print');
	});

	// Grade Module
	this.resource('grades', function() {
		this.route('grade', {path: '/:subjcode/:section'});
	});

	// Registration Module
	this.resource('registrations', function() {
		this.route('registration', {path: '/:studid'});
	});

});

export default Router;
