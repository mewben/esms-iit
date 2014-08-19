import Em from 'ember';

export default Em.Route.extend({
	actions: {
		print: function() {
			window.print();
		},
		toprint: function(route, p, p2) {
			var url;
			if (p2) {
				url = this.router.generate(route, p, p2);
			} else {
				url = this.router.generate(route, p);
			}
			window.open(url);
		}
	}
});