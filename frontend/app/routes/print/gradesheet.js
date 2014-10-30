import Em from 'ember';

export default Em.Route.extend({
	model: function(params) {
		console.log(params);
	}
});