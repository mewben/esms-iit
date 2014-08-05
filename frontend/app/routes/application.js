import Em from 'ember';

export default Em.Route.extend({
	actions: {
		print: function() {
			window.print();
		},
		toprint: function(route, p) {
			console.log(route);
			console.log(p);
			var url = this.router.generate(route, p);
			console.log(url);
		}
	}
});