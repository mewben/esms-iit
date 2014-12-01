import Ember from 'ember';
import config from './config/environment';

var Router = Ember.Router.extend({
  location: config.locationType
});

Router.map(function() {
	this.route('login');
	this.route('logout');

	this.route('dashboard', {path: '/'});

	this.resource('manage', function() {
		// bcodes
		this.resource('bcodes', function() {
			this.route('new');
			this.route('edit', {path: '/:id/edit'});
		});

		// Fees
		this.resource('fees', function() {
			this.route('new');
			this.route('edit', {path: '/:id/edit'});
		});
	});
});

export default Router;
