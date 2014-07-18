import Base from './base';

export default Base.extend({
	disb: function() {
		return !this.get('stud') || this.get('g.isProc');
	}.property('stud', 'g.isProc'),

	actions: {
		lookup: function() {
			var self = this;
			var param = {
				q: this.get('stud')
			};

			this.get('g').getJSON('/students?', param)
				.done(function(res) {
					if(res.studid) {
						self.transitionToRoute('ledgers.ledger', res.studid);
					} else {
						self.set('data', res);
					}
				});
		}
	}
});