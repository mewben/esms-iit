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
		toprint: function(route) {
			
		},

		test: function() {
			var param = {
				order: 'asc',
				where: 'klerj'
			};
			var t = this.router.generate('print.collections');
			console.log(t);
		},

	}
});