import Ember from 'ember';

export default Ember.Route.extend({
	model: function(params) {
		params.queryParams.subjcode = params.queryParams.subjcode.replace(/\+/g, ' ');
		return this.controllerFor('g').getJSON('/grades/' + params.queryParams.subjcode + '/' + params.queryParams.section);
	}
});