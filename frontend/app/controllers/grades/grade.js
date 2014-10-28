import Ember from 'ember';

export default Ember.ObjectController.extend({
	needs: 			'g',
	g: 				Ember.computed.alias('controllers.g'),
	//isreex: true,

	lock: function() {
		return this.get('meta.lock') || false;
	}.property('meta.lock'),
	// lockre: function() {
	// 	this.get('data').forEach(function(v) {
	// 		var ave = ((Number(v.prelim1) - 0) + (Number(v.prelim2) - 0)) / 2;
	// 		var res = parseFloat(ave.toPrecision(12));
	// 		var fix = Math.floor(res * 10) / 10;

	// 		if(fix.toFixed(1) > 3) {
	// 			this.set('isreex', false);
	// 		}
	// 	});
	// }.observes('data'),

	oData: null,

	actions: {
		save: function() {
			var oData = this.get('oData');
			var iData = [];
			var data = {};

			this.get('data').forEach(function(v, i) {
				if(oData[i]['prelim1'] !== v.prelim1 || oData[i]['prelim2'] !== v.prelim2 || oData[i]['gcompl'] !== v.gcompl) {
					v.isChanged = true;
				} else {
					v.isChanged = false;
				}

				iData[i] = v;
			});

			data.data = JSON.stringify(iData);

			this.get('g').post('/grades-update', data)
				.done(function() {
					toastr.success('Grades saved successfully!');
				});
		},

		lock: function(v) {
			var self = this;

			var data = {};
			data.data = JSON.stringify(this.get('meta'));
			data.lock = v;

			//Lock grades
			if(v) {
				this.get('g').post('/grades-lock', data)
					.done(function() {
						self.set('meta.lock', true);
						toastr.success('Grades has been Locked!');
					});
			}
			else {
				this.get('g').post('/grades-lock', data)
					.done(function() {
						self.set('meta.lock', false);
						toastr.success('Grades has been Unlocked!');
					});
			}
		}
	}
	
});