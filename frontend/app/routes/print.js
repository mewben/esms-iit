import Em from 'ember';

export default Em.Route.extend({
	activate: function() {
		this.controllerFor('g').set('pclass', 'print');
	},
	deactivate: function() {
		this.controllerFor('g').set('pclass', 'screen');	
	}
});