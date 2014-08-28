import Em from 'ember';
import Base from './base';

export default Base.extend({
	bcodes: [
		{v: 'CASHIER', name: 'CASHIER'},
		{v: 'FCB', name: 'FCB'}
	],
	funds: [
		{v: 'GF', name: 'General Fund'},
		{v: 'STF', name: 'Special Trust Fund'},
		{v: 'TF', name: 'Trust Fund'}
	],
	cashier: false,
	bcode: null,
	fund: 'STF',

	disb: function() {
		return !this.get('datefrom') || (!this.get('cashier') && !this.get('bcode')) || !this.get('dateto') || this.get('g.isProc');
	}.property('datefrom', 'bcode', 'cashier', 'dateto'),

	tlink: function() {
		return EsmsUiENV.ApiHost + 'reports/ReportCollections.xlsx';
	}.property('tlink'),

	actions: {
		preview: function() {
			//var param = this._params()
			// query to database get summary of collections
		},
		export: function() {
			var param = this._params();
			window.open(EsmsUiENV.ApiHost + EsmsUiENV.Api + '/reports/collections?' + Em.$.param(param));	
		}
	},

	_params: function() {
		var bcode;
		if (this.get('cashier')) {
			bcode = 'CASHIER';
		} else {
			bcode = this.get('bcode');
		}
		return {
			bcode: bcode,
			datefrom: this.get('datefrom'),
			dateto: this.get('dateto'),
			fund: this.get('fund')
		};
	}
});