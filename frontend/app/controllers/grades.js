import Base from './base';

export default Base.extend({
	actions: {
		lookup: function() {
			this.send('searchSubj', this.get('subj'), 'grades.grade');
		}
	}
});