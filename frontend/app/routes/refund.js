import Em from 'ember';

export default Em.Route.extend ({
	beforeModel: function() {
		var access = false;
		window.menu.forEach(function(val){
			val.items.forEach(function(v){
				if(v.location === 'refund') {
					access = true;
					return;
				}
			});
		});
		
		if(!access) {
			this.transitionTo('dashboard');
		}
	}
});