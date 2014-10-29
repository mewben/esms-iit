import Ember from 'ember';

export default Ember.Route.extend({

	model: function(params) {
		//get model from php
		return this.controllerFor('g').getJSON('/grades/' + encodeURIComponent(params.subjcode) + '/' + params.section);
	},

	setupController: function(controller, model) {
		var oData = Ember.copy(model.data, true);
		controller.set('oData', oData);
		controller.set('model', model);
	}
});