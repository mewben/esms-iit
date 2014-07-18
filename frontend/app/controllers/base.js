import Em from 'ember';

export default Em.Controller.extend({
	needs: 'g',
	g: Em.computed.alias('controllers.g'),

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
		}
	}
});