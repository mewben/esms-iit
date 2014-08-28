import Em from 'ember';
import Base from './base';

export default Base.extend({
	init: function() {
		this._super();
		this._getBcodes();
	},
	bcode: 'FCB',
	data: [],

	enSubmit: function() {
		var d = this.get('data');

		var l = this.get('data').filter(function(item) {
			if (!Em.isEmpty(item.payee)) {
				return item;
			}
		});

		return d.length === l.length;
	}.property('data.@each.payee'),

	disb: function() {
		return !this.get('csv') || !this.get('bcode') || this.get('g.isProc');
	}.property('csv', 'bcode', 'g.isProc'),

	actions: {
		// parse csv file frontend
		parse: function() {
			var self = this;
			var total = 0;

			Em.$("#csv").parse({
				config: {
					dynamicTyping: false
				},
				complete: function(res) {
					if(res.errors.length === 0) {
						for(var i = 0; i<res.results.rows.length; i++) {
							total += (res.results.rows[i].amt - 0);
						}
						self.set('total', total);
						self.set('data', res.results.rows);
					}
				}
			});
		},

		// verify student id
		// returns studfullname
		verify: function() {
			var self = this;
			var bcode = this.get('bcode');

			// loop through data and get the studfullname of the id to verify
			var data = this.get('data');

			data.map(function(item) {
				Em.set(item, 'proc', true);
				Em.set(item, 'bcode', bcode);
				self.get('g').getJSON('/students?', {q: item.studid, d: true})
					.done(function(res) {
						Em.set(item, 'proc', false);
						Em.set(item, 'payee', res.fullname);
					});
			});
		},

		// process payment for import
		submit: function() {
			var self = this;

			var data = this.get('data');

			data.forEach(function(item) {
				Em.set(item, 'proc', true);
				self.get('g').post('/import-payment', item)
					.done(function() {
						Em.set(item, 'success', true);
					})
					.fail(function(err) {
						Em.set(item, 'fail', true);
						Em.set(item, 'err', err.responseJSON);
					})
					.always(function() {
						Em.set(item, 'proc', false);
					});
			});
		}
	},

	getStudentName: function(id) {
		this.get('g').getJSON('/students?', {q: id});

	}
});