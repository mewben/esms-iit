import Ember from 'ember';

export default Ember.ObjectController.extend({

	actions: {

		save: function() {
			//console.log(this.get('data'));
			var data = this.get('data');

			this.get('g').post('/grades-update', data)
				.done(function() {
					console.log('done');
				});
		}
	}
	
});