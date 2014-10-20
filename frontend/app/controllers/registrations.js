import Base from './base';

export default Base.extend({
	actions: {
		lookup: function() {
			this.send('searchStud', this.get('stud'));
		}
	}
});