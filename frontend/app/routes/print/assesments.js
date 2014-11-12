import Ember from 'ember';

export default Ember.Route.extend({
	model: function(param) {
		param.queryParams.studid = param.queryParams.studid.replace(/\+/g, ' ');
		return this.controllerFor('g').getJSON('/reports/certbilling/' + param.queryParams.studid);
	}
});