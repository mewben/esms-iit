import Ember from 'ember';

export default Ember.ObjectController.extend({
	studunits: null,
	studgpa: null,

	computegpaunits: function() {
		var lec = 0;
		var lab = 0;
		var units = 0;

		var lecgpa = 0;
		var labgpa = 0;
		var grade = 0;
		var total = 0;
		var gpa = 0;

		var subjwgrade = 0;
		var subjforgpa = 0;

		this.get('subj').forEach(function(v) {
			lec += Number(v.subjlec_units);
			lab += Number(v.subjlab_units);
			
			if(v.grade > 3) {
				if(Number(v.subjgpa) === 1 && v.prelim1 && v.prelim2 && v.gcompl) {
					subjwgrade++;
				}
			} else {
				if(Number(v.subjgpa) === 1 && v.prelim1 && v.prelim2) {
					subjwgrade++;
				}
			}

			if(v.grade > 3) {
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
				if(!v.gcompl) {
					grade = Number(v.grade);
				} else {
					grade = Number(v.gcompl);
				}
				total += grade * (Number(v.subjlec_units) + Number(v.subjlab_units));
				subjforgpa++;
			}
		});
		
		units = lec + lab;
		gpa = total / (lecgpa + labgpa);

		this.set('studunits', units);
		if(subjwgrade === subjforgpa) {
			this.set('studgpa', gpa.toFixed(5));
		} else {
			this.set('studgpa', 'N/A');
		}
	}.observes('subj'),
});