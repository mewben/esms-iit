import Base from './base';

export default Base.extend({
	succ: false,
	searchCat: [
		{v: 'refno', name: 'Reference Number'},
		{v: 'studid', name: 'Student Id'},
		{v: 'payee', name: 'Last Name'}
	],
	cat: 'refno',

	disbissue: function() {
		return !this.get('refno') || !this.get('currentDate') || !this.get('studid') || !this.get('amt') || this.get('succ') || this.get('g.isProc');
	}.property('refno', 'currentDate', 'studid', 'amt', 'succ', 'g.isProc'),

	not_succ: function() {
		return !this.get('succ');
	}.property('succ'),
	total: function() {
		return this.get('det').reduce(function(t, p) {
			return t + (p.amount - 0);
		}, 0);
	}.property('det.@each.amount'),

	actions: {
		lookup: function() {
			this.send('searchStud', this.get('stud'));
		},

		checkRefund: function(studid) {
			var self = this;
			// check refund in current semester.
			this._reset();
			this.get('g').getJSON('/refund-check/' + studid)
				.done(function(res) {
					self.setProperties({
						studid: res.studid,
						payee: res.studfullname,
						amt: res.amount,
						det: res.detail,
						succ: false
					});
				});
		},

		issue: function() {
			var self = this;
			var param = {
				refno: 		this.get('refno'),
				studid: 	this.get('studid'),
				payee: 		this.get('payee'),
				paydate: 	this.get('currentDate'),
				remarks: 	this.get('remarks'),
				detail: 	this.get('det')
			};

			this.get('g').post('/refund', param)
				.done(function() {
					self.set('succ', true);
				});
		},

		delete: function() {
			console.log('delete');
			var self = this;
			if(confirm(this.get('g.msg.del_confirm'))) {
				this.get('g').post('/delete-refund', {q: this.get('refno')})
					.done(function() {
						self._reset();
					});				
			}
		},

		searchP: function() {
			var self = this;
			var param = {
				cat: this.get('cat'),
				q: this.get('q')
			};
			this.get('g').getJSON('/refund-search?', param)
				.done(function(res) {
					self.set('sr', res);
				});
		},

		select: function(refno) {
			var self = this;
			this.get('g').getJSON('/refund/' + refno)
				.done(function(res) {
					self.setProperties({
						no_data: false,
						studid: res.studid,
						payee: res.payee,
						refno: res.refno,
						paydate: res.paydate,
						remarks: res.remarks,
						amt: res.amt,
						succ: true
					});
				});
		}
	},

	_reset: function() {
		this.setProperties({
			refno: '',
			remarks: '',
			amt: 0
		});
	}
});