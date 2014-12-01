import Ember from 'ember';

export default Ember.Mixin.create({

	setupController: function(controller, model) {
		this._super(controller, model);
		this.controller.set('g.pageTitle', this.controller.get('pageTitle', ''));
	}

});