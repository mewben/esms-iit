import Em from 'ember';

export default Em.Route.extend({
	model: function(params) {
		/*var param = {
			sy: params.sy,
			sem: params.sem,
			model: true
		};*/
		return this.controllerFor('g').getJSON('/reports/sumbilling?', params.queryParams );
	}
});