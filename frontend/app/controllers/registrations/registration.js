import Ember from 'ember';

export default Ember.ObjectController.extend({
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

	computegpaunits: function() {
		var lecgpa = 0;
		var labgpa = 0;
		var grade = 0;
		var total = 0;
		var gpa = 0;

		var subjwgrade = 0;
		var subjforgpa = 0;

		this.get('subj').forEach(function(v) {
			if(v.grade > 3 && v.grade < 5) {
				if(Number(v.subjgpa) === 1 && v.prelim1 && v.prelim2 && v.gcompl) {
					subjwgrade++;
				}
			} else {
				if(Number(v.subjgpa) === 1 && v.prelim1 && v.prelim2) {
					subjwgrade++;
				}
			}

			if(v.grade > 3 && v.grade < 5) {
				if(!v.prelim1 || !v.prelim2 || !v.gcompl) {
					v.isEmpty = "danger";
				}
			} else {
				if(!v.prelim1 || !v.prelim2) {
					v.isEmpty = "danger";
				}
			}
			
			if(Number(v.subjgpa)) {
				lecgpa += Number(v.subjlec_units);
				labgpa += Number(v.subjlab_units);
				if(v.grade > 3 && v.grade < 5) {
					grade = Number(v.gcompl);
				} else {
					grade = Number(v.grade);
				}
				total += grade * (Number(v.subjlec_units) + Number(v.subjlab_units));
				subjforgpa++;
			}
		});
		
		gpa = total / (lecgpa + labgpa);

		if(subjwgrade === subjforgpa) {
			this.set('studgpa', gpa.toFixed(5));
		} else {
			this.set('studgpa', 'N/A');
		}
	}.observes('subj'),
});