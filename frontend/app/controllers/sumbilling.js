import Base from './base';
export default Base.extend({
	actions: {
		retrieve: function() {
			var param = {
				sy: this.get('sy'),
				sem: this.get('sem')
			};
			this.send('search', '/reports/sumbilling?', param);
		}
	}
});