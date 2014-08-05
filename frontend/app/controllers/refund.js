import Base from './base';

export default Base.extend({
	disb: function() {
		return !this.get('stud') || this.get('g.isProc');
	}.property('stud', 'g.isProc'),

	disbissue: function() {
		return !this.get('refno') || !this.get('currentDate') || !this.get('studid') || !this.get('amt') || this.get('g.isProc');
	}.property('refno', 'currentDate', 'studid', 'amt', 'g.isProc'),

	actions: {
		lookup: function() {
			this.send('searchStud', this.get('stud'));
		},

		checkRefund: function(studid) {
			var self = this;
			// check refund in current semester.
			this.get('g').getJSON('/refund/' + studid)
				.done(function(res) {
					self.setProperties({
						'studid': res.studid,
						'payee': res.studfullname,
						'amt': res.amt
					});
				});
		},

		issue: function() {
			var param = {
				refno: 		this.get('refno'),
				studid: 	this.get('studid'),
				payee: 		this.get('payee'),
				paydate: 	this.get('currentDate'),
				remarks: 	this.get('remarks')
			};

			this.get('g').post('/refund', param)
				.done(function(res) {
					//console.log(res);
				});
		}
	}
});