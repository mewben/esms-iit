import Em from 'ember';

export default Em.Controller.extend({
	needs: 'g',
	g: Em.computed.alias('controllers.g'),

	version: Em.computed.oneWay('g.version'),
	sy: Em.computed.oneWay('g.sem.sy'),
	sem: Em.computed.oneWay('g.sem.sem'),
	currentDate: Em.computed.oneWay('g.currentDate'),

	// enable/disable submit button
	disb: function() {
		return !this.get('sy') || !this.get('sem') || this.get('g.isProc');
	}.property('sy', 'sem', 'g.isProc'),

	actions: {
		search: function(url, param) {
			var self = this;

			this.get('g').getJSON(url, param)
				.done(function(res) {
					self.set('data', res);
				});
		},
		searchStud: function(id, trans) {
			var self = this;

			this.get('g').getJSON('/students?', {q: id})
				.done(function(res) {
					if(res.length === 1 && trans) {
						self.transitionToRoute(trans, res[0].studid);
					} else {
						self.set('data', res);
					}
				});
		}
	}
});