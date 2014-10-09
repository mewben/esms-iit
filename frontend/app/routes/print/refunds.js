import Em from 'ember';

export default Em.Route.extend({
	model: function(params) {
		//params.queryParams.desc = params.queryParams.desc.replace(/\+/g, ' ');
		return this.controllerFor('g').getJSON('/reports/refunds?', params.queryParams);
	}
});