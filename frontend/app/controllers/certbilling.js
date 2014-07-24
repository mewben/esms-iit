import Base from './base';

export default Base.extend({
	disb: function() {
		return !this.get('stud') || this.get('g.isProc');
	}.property('stud', 'g.isProc'),

	actions: {
		lookup: function() {
			this.send('searchStud', this.get('stud'), 'certbilling.stud');
		}

	}
});