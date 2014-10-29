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
		var unitsgpa = 0;
		var total = 0;
		var gpa = 0;

		var subjwgrade = 0;
		var subjforgpa = 0;

		this.get('subj').forEach(function(v) {
			lec += Number(v.subjlec_units);
			lab += Number(v.subjlab_units);

			if(Number(v.subjgpa) === 1 && v.prelim1 && v.prelim2) {
				subjwgrade++;
			}

			if(Number(v.subjgpa)) {
				lecgpa += Number(v.subjlec_units);
				labgpa += Number(v.subjlab_units);

				total += Number(v.grade) * (Number(v.subjlec_units) + Number(v.subjlab_units));
				subjforgpa++;
			}
		});
		//compute total units
		units = lec + lab;
		//compute gpa
		unitsgpa = lecgpa + labgpa;
		//gpa = Math.floor((total / unitsgpa) * 10000) / 10000;
		gpa = total / unitsgpa;
		//set property values
		this.set('studunits', units);
		if(subjwgrade === subjforgpa) {
			this.set('studgpa', gpa.toFixed(5));
		}
		else {
			this.set('studgpa', 'N/A');
		}
	}.observes('subj')
});