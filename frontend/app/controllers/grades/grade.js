import Ember from 'ember';

export default Ember.ObjectController.extend({
	needs: 			'g',
	g: 				Ember.computed.alias('controllers.g'),
	oData: null,

	lock: function() {
		return this.get('meta.lock') || false;
	}.property('meta.lock'),

	params: function() {
		var p = this._params(true);
		return p;
	}.property('meta.subjcode'),

	_params: function() {
		return {
			subjcode: this.get('meta.subjcode'),
			section: this.get('meta.section')
		};
	},
	
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