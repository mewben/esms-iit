import Ember from 'ember';

export default Ember.Route.extend({
	model: function(param) {
		param.queryParams.studid = param.queryParams.studid.replace(/\+/g, ' ');
		return this.controllerFor('g').getJSON('/registration-cor/' + param.queryParams.studid);
	}
});