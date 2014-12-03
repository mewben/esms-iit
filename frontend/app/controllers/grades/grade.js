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

			var validGrades = this.get('vgrades');
			var invalidCount = 0;
			var valid1 = null;
			var valid2 = null;
			
			this.get('data').forEach(function(v, i) {
				// Set isChanged property
				if(oData[i]['prelim1'] !== v.prelim1 || oData[i]['prelim2'] !== v.prelim2 || oData[i]['gcompl'] !== v.gcompl) {
					v.isChanged = true;
				} else {
					v.isChanged = false;
				}

				// Check for inValid grades
				if (v.prelim1) {
					if (Ember.$.isNumeric(v.prelim1)) {
						valid1 = validGrades.some(function(vg) {
							return Number(v.prelim1).toFixed(1) === vg.grade;
						});
					} else {
						valid1 = validGrades.some(function(vg) {
							return v.prelim1 === vg.grade;
						});
					}
					if (!valid1) {
						invalidCount++;
					}
				}
				if (v.prelim2) {
					if (Ember.$.isNumeric(v.prelim2)) {
						valid2 = validGrades.some(function(vg) {
							return v.prelim2 === vg.grade;
						});
					} else {
						valid2 = validGrades.some(function(vg) {
							return v.prelim2 === vg.grade;
						});
					}
					if (!valid2) {
						invalidCount++;
					}
				}

				iData[i] = v;
			});

			if (invalidCount === 0) {
				data.data = JSON.stringify(iData);

				this.get('g').post('/grades-update', data)
					.done(function() {
						toastr.success('Grades saved successfully!');
					});
			} else {
				toastr.error('You have entered an invalid grade. Please review your entries!');
			}
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