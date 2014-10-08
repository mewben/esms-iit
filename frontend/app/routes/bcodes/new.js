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