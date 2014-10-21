import Ember from 'ember';

export default Ember.ObjectController.extend({
	needs: 			'g',
	g: 				Ember.computed.alias('controllers.g'),
	lock: function() {
		return this.get('meta.lock') || false;
	}.property('meta.lock'),

	actions: {
		save: function() {
			var data = {};
			data.data = JSON.stringify(this.get('data'));

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