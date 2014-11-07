import Ember from 'ember';

export default Ember.Route.extend({
	model: function(param) {
		return this.controllerFor('g').getJSON('/reports/certbilling/' + param.studid);
	}
});