import Ember from 'ember';

export default Ember.ObjectController.extend({
	params: function() {
		var p = this._params(true);
		//p.print = true;
		console.log(p);
		return p;
	}.property('h.studid'),

	actions: {
		preview: function() {
			var self = this;
			var param = this._params(true);
			this.get('g').getJSON('/reports/collections?' + param.studid)
				.done(function(res) {
					self.set('res', res);
				});
		},
	},

	_params: function() {
		return {
			studid: this.get('h.studid'),
			//preview: preview,
		};
	}
});