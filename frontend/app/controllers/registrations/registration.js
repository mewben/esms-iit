import Ember from 'ember';

export default Ember.ObjectController.extend({
	studunits: null,
	studgpa: null,

	params: function() {
		var p = this._params(true);
		return p;
	}.property('meta.studid'),

	_params: function() {
		return {
			studid: this.get('meta.studid'),
		};
	},

	checkempty: function() {
		this.get('subj').forEach(function(v) {
			if(v.grade > 3) {
				if(!v.prelim1 || !v.prelim2 || !v.gcompl) {
					v.isEmpty = "danger";
				}
			} else {
				if(!v.prelim1 || !v.prelim2) {
					v.isEmpty = "danger";
				}
			}
		});
	}.observes('subj'),
});