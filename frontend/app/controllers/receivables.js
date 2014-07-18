import Em from 'ember';
import Base from './base';

export default Base.extend({
	colleges: [
		{v: 'UNDR', name: 'Undergraduate'},
		{v: 'GRAD', name: 'Graduate'}
	],
	college: 'UNDR',
	data: [],
	raw: [],

	disb: function() {
		return !this.get('dd') || this.get('g.isProc');
	}.property('dd', 'g.isProc'),

	actions: {
		retrieve: function() {
			var self = this;
			var param = {
				date: this.get('dd'),
				sy: this.get('sy'),
				sem: this.get('sem'),
				college: this.get('college')
			};

			this.get('g').getJSON('/reports/receivables?', param)
				.done(function(res) {
					self.set('raw', Em.copy(res, true));
					res.data = res.data.splice(0, self.get('g.listLim'));
					self.set('data', res);
				});
		},

		export: function() {
			var dataobj = this.get('raw');

			Em.$('#export').btechco_excelexport({
				containerid: "export",
				datatype: 2, // for json
				dataset: dataobj.data,
				columns: [
					{ headertext: 'ID', datatype: 'string', datafield: 'studid'},
					{ headertext: 'Student Name', datafield: 'fullname'},
					{ headertext: 'Major', datafield: 'studmajor'},
					{ headertext: 'Status', datafield: 'schdesc'},
					{ headertext: 'Total Assessment', datatype: 'number', datafield: 'total_assess'},
					{ headertext: 'Amount Paid', datatype: 'number', datafield: 'total_pay'},
					{ headertext: 'Refund', datatype: 'number', datafield: 'total_refund'},
					{ headertext: 'Balance', datatype: 'number', datafield: 'balance'}
				]

			});
		}
	}
});