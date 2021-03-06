import Em from 'ember';

export default Em.Route.extend({
	beforeModel: function() {
		var access = false;
		window.menu.forEach(function(val){
			val.items.forEach(function(v){
				if(v.location === 'certbilling') {
					access = true;
					return;
				}
			});
		});
		
		if(!access) {
			this.transitionTo('dashboard');
		}
	},
	model: function(params) {
		return this.controllerFor('g').getJSON('/reports/certbilling/' + params.studid );
	}
});