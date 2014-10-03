import Em from 'ember';
import Base from './base';

export default Base.extend({
	tdata: [],

	disb: function() {
		return !this.get('datefrom') || !this.get('dateto') || this.get('g.isProc');
	}.property('datefrom', 'dateto'),

	tlink: function() {
		return EsmsUiENV.ApiHost + 'reports/ReportRefunds.xlsx';
	}.property('tlink'),

	// for print preview
	params: function() {
		var p = this._params(true);
		p.print = true;
		return p;
	}.property('datefrom', 'dateto'),

	actions: {
		preview: function() {
			var self = this;
			var param = this._params(true);
			this.get('g').getJSON('/reports/refunds?', param)
				.done(function(res) {
					self.set('res', res);
				});
		}, 

		export: function() {
			var param = this._params();
			window.open(EsmsUiENV.ApiHost + EsmsUiENV.Api + '/reports/refunds?' + Em.$.param(param));
		}
	},

	_params: function(preview) {
		return {
			datefrom: this.get('datefrom'),
			dateto: this.get('dateto'),
			preview: preview
		};
	}
});