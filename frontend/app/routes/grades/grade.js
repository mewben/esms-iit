import Ember from 'ember';

export default Ember.Route.extend({

	model: function(params) {
		return this.controllerFor('g').getJSON('/grades/' + params.subjcode + '/' + params.section);
	}
});