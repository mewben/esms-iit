import Em from 'ember';

export default Em.Route.extend({
	model: function(params) {
		return this.controllerFor('g').getJSON('/reports/certbilling/' + params.studid );
	}
});