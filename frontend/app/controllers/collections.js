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
	bcode: 'FCB',
	fund: 'STF',

	disb: function() {
		return !this.get('datefrom') || !this.get('dateto') || this.get('g.isProc');
	}.property('datefrom', 'dateto'),

	tlink: function() {
		return EsmsUiENV.ApiHost + 'reports/ReportCollections.xlsx';
	}.property('tlink'),

	actions: {
		export: function() {
			var param = {
				bcode: this.get('bcode'),
				datefrom: this.get('datefrom'),
				dateto: this.get('dateto'),
				fund: this.get('fund')
			};
			window.open(EsmsUiENV.ApiHost + EsmsUiENV.Api + '/reports/collections?' + Em.$.param(param));
		}
	}
});