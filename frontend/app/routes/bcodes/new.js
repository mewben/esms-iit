import Em from 'ember';

export default Em.Route.extend({
	model: function() {
		return this.store.createRecord('bcode');
	},

	deactivate: function() {
		this.currentModel.rollback();
	},

	actions: {
		save: function() {
			var route = this;

			this.currentModel.save().then(function() {
				route.transitionTo('bcodes.index');
			});
		}
	}
});