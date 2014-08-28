import Em from 'ember';

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