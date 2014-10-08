import N from './new';

export default N.extend({
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
	model: function(params) {
		return this.store.find('bcode', params.id);
	}
});