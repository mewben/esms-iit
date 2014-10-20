import Em from 'ember';

Em.$(document).ready(function() {
	var inpgrades; // Grades input boxes
 
	Em.$(document).keypress(function(e) {
		
		// Enable Enter key to focus next input grade
		if (e.which === 13 && Em.$(e.target).parents('.grade')) {
			inpgrades = Em.$('.grade input:enabled');
 
			var currentindex = inpgrades.index(e.target),
				next = inpgrades.eq(currentindex + 1).length ? inpgrades.eq(currentindex + 1) : inpgrades.eq(0);
			
			next.focus().select();
		}
	});
});

export default Em.Route.extend({
	actions: {
		print: function() {
			window.print();
		},
		toprint2: function(route, p, p2) {
			var url;
			if (p2) {
				url = this.router.generate(route, p, p2);
			} else {
				url = this.router.generate(route, p);
			}
			window.open(url);
		},

		toprint: function(route, param) {
			var p = '';
			if (param) {
				p = '?' + Em.$.param(param);
			}
			var url = this.router.generate(route);
			window.open(url+p);
		}
	}
});