import Em from 'ember';

export default Em.Route.extend({
	model: function() {
		return this.store.find('bcode');
	},

	actions: {
		delete: function(m) {
			if(confirm(this.controllerFor('g').get('msg.del_confirm'))) {
				//var m = this.currentModel,
				var	route = this;

				m.deleteRecord();
				m.save().then(function() {
					route.transitionTo('bcodes');
				}, function() {
					m.rollback();
				});

			}
		}
	}
});