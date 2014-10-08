import Em from 'ember';

export default Em.Route.extend({
	beforeModel: function() {
		var access = false;
		window.menu.forEach(function(val){
			val.items.forEach(function(v){
				if(v.location === 'bcodes') {
					access = true;
					return;
				}
			});
		});
		
		if(!access) {
			this.transitionTo('dashboard');
		}
	},

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