import Em from 'ember';
import Base from './base';

export default Base.extend({
	bcodes: [
		{v: 'FCB', name: 'FCB'},
		{v: 'ALAY', name: 'Alay-lakad Inc.'},
		{v: 'LGUTAG', name: 'LGU-Tagbilaran'},
		{v: 'DOST', name: 'DOST'},
		{v: 'CPG', name: 'CPG'}
	],
	searchCat: [
		{v: 'refno', name: 'Reference Number'},
		{v: 'studid', name: 'Student Id'},
		{v: 'payee', name: 'Last Name'}
	],
	bcode: 'FCB',
	cat: 'refno',
	data: [],
	succ: false,
	searchRes: [],

	disb: function() {
		return !this.get('refno') || !this.get('payee') || !this.get('currentDate') || this.get('total') === 0 || this.get('succ') || this.get('g.isProc');
	}.property('refno', 'payee', 'currentDate', 'total', 'succ'),

	disb2: function() {
		return !this.get('q') || this.get('g.isProc');
	}.property('q'),

	total: function() {
		return this.get('data').reduce(function(t, p) {
			return t + (p.amt - 0);
		}, 0);
	}.property('data.@each.amt'),

	fc: function() {
		Em.run.once(this, 'fcChanged');
	}.observes('feecode'),

	fcChanged: function() {
		var fcode = this.get('feecode');
		var m = this.get('model');
		var d = this.get('data');

		if (fcode) {
			// add to table - data
			for(var i = 0; i < m.length; i++) {
				if (fcode === m[i].feecode) {
					var c = {
						feecode: m[i].feecode,
						feedesc: m[i].feedesc,
						acctcode: m[i].acctcode,
						amt: null
					};
					d.unshiftObject(c);
					this.set('data', d);
					break;
				}
			}
		}
	},

	not_succ: function() {
		return !this.get('succ');
	}.property('succ'),

	actions: {
		lookup: function() {
			this.send('searchStud', this.get('stud'));
		},

		qStudSelect: function(id) {
			this._loadId(id);
		},
		
		searchId: function() {
			this._loadId(this.get('id'));
		},

		loadUnpaid: function() {
			var self = this;
			var param = {
				q: this.get('id'),
				sy: this.get('sy'),
				sem: this.get('sem')
			};
			this.get('g').getJSON('/load-unpaid?', param)
				.done(function(res) {
					self.set('data',res);
				});
		},

		submit: function() {
			var self = this;
			var post = {
				h : {
					refno: this.get('refno'),
					bcode: this.get('bcode'),
					paydate: this.get('currentDate'),
					sy: this.get('sy'),
					sem: this.get('sem'),
					payee: this.get('payee'),
					studid: this.get('studid')
				},
				details: this.get('data')
			};
			this.get('g').post('/payment', post)
				.done(function() {
					self.set('succ', true);
				});
		},

		new: function() {
			this._reset();
			this.set('succ', false);
		},

		cancel: function() {
			this._reset();
		},

		delete: function(t) {
			this.get('data').removeObject(t);
		},

		deleteP: function() {
			var self = this;
			if(confirm(this.get('g.msg.del_confirm'))) {
				this.get('g').post('/delete-payment', {q: this.get('refno')})
					.done(function() {
						self._reset();
					});				
			}
		},

		select: function(refno) {
			this._loadPayment({q: refno});
		},

		searchP: function() {
			var param = {
				cat: this.get('cat'),
				q: this.get('q')
			};
			this._loadPayment(param);
		}
	},

	_reset: function() {
		this.setProperties({
			refno: null,
			studid: null,
			payee: null,
			id: null,
			data: []
		});
	},

	_loadPayment: function(param) {
		var self = this;
		this.get('g').getJSON('/payment?', param)
			.done(function(res) {
				if (res.h) {						
					// populate all data
					self.setProperties({
						data: res.details,
						refno: res.h.refno,
						bcode: res.h.bcode,
						currentDate: res.h.paydate,
						sy: res.h.sy,
						sem: res.h.sem,
						studid: res.h.studid,
						id: res.h.studid,
						payee: res.h.payee,
						succ: true
					});
				} else {
					// show search results
					self.set('sr', res);
				}
			});
	},

	_loadId: function(id) {
		var self = this;

		this.get('g').getJSON('/students?', {q: id, d: true})
			.done(function(res) {
				if (res) {
					self.set('studid', res.studid);
					self.set('payee', res.fullname);
					self.set('id', res.studid);
				} else {
					// remove payee and studid
					self.set('studid', undefined);
					self.set('payee', undefined);
				}
			}); 
	}
});