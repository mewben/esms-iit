import Em from 'ember';

export default Em.Route.extend({
	model: function() {
		return this.store.find('bcode');
	},

	actions: {
		delete: function(m) {
			if(confirm(this.controllerFor('g').get('msg.del_confirm'))) {
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