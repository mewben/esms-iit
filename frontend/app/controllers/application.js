import Base from './base';

export default Base.extend({
	actions: {
		changeSem: function() {
			var self = this;
			var param = {
				sy: this.get('sy'),
				sem: this.get('sem')
			};
			this.get('g').post('/change_semester', param)
				.done(function(res) {
					self.set('g.sem', res);
					toastr.success(self.get('g.msg.sem_changed'));
				});
		}
	}	
});