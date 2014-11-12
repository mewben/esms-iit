import Em from 'ember';
import Base from './base';

export default Base.extend({
	init: function() {
		this._super();
		this._getBcodes();
	},
	funds: [
		{v: 'GF', name: 'General Fund'},
		{v: 'STF', name: 'Special Trust Fund'},
		{v: 'TF', name: 'Trust Fund'}
	],
	cashier: false,
	bcode: null,
	fund: 'STF',
	tdata: [],

	disb: function() {
		return !this.get('datefrom') || (!this.get('cashier') && !this.get('bcode')) || !this.get('dateto') || this.get('g.isProc');
	}.property('datefrom', 'bcode', 'cashier', 'dateto'),

	tlink: function() {
		return EsmsUiENV.ApiHost + 'reports/ReportCollections.xlsx';
	}.property('tlink'),

	// for print preview
	params: function() {
		var p = this._params(true);
		p.print = true;
		return p;
	}.property('cashier', 'bcode', 'datefrom', 'dateto', 'fund'),

	actions: {
		preview: function() {
			var self = this;
			var param = this._params(true);
			this.get('g').getJSON('/reports/certbilling/', param)
				.done(function(res) {
					self.set('res', res);
				});
		}, 

		export: function() {
			var param = this._params();
			window.open(EsmsUiENV.ApiHost + EsmsUiENV.Api + '/reports/collections?' + Em.$.param(param));
		}
	},

	_params: function(preview) {
		var bcode, desc;
		if (this.get('cashier')) {
			bcode = 'CASHIER';
			desc = 'Cashier Collections';
		} else {
			bcode = this.get('bcode');
			desc = this.get('sbcode.desc');
		}
		return {
			bcode: bcode,
			datefrom: this.get('datefrom'),
			dateto: this.get('dateto'),
			fund: this.get('fund'),
			desc: desc,
			preview: preview
		};
	}
});