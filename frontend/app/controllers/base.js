import Em from 'ember';

export default Em.Controller.extend({
	needs: 			'g',
	g: 				Em.computed.alias('controllers.g'),

	version: 		Em.computed.oneWay('g.version'),
	sy: 			Em.computed.oneWay('g.sem.sy'),
	sem: 			Em.computed.oneWay('g.sem.sem'),
	currentDate: 	Em.computed.oneWay('g.currentDate'),

	// enable/disable submit button
	disb: function() {
		return !this.get('sy') || !this.get('sem') || this.get('g.isProc');
	}.property('sy', 'sem', 'g.isProc'),

	// search student enable submit button
	disbQStud: function() {
		return !this.get('stud') || this.get('g.isProc');
	}.property('stud'),

	// search subject enable submit button
	disbQSubj: function() {
		return !this.get('subj') || this.get('g.isProc');
	}.property('subj'),

	actions: {
		search: function(url, param) {
			var self = this;

			this.get('g').getJSON(url, param)
				.done(function(res) {
					self.set('data', res);
				});
		},
		searchStud: function(id, trans) {
			var self = this;

			this.get('g').getJSON('/students?', {q: id})
				.done(function(res) {
					if(res.length === 1 && trans) {
						self.transitionToRoute(trans, res[0].studid);
					} else {
						self.set('studlist', res);
					}
				});
		},
		searchStudReg: function(id, trans) {
			var self = this;

			this.get('g').getJSON('/students-registration?', {q: id})
				.done(function(res) {
					if(res.length === 1 && trans) {
						self.transitionToRoute(trans, res[0].studid);
					} else {
						self.set('studlist', res);
					}
				});
		},
		searchSubj: function(subjcode, trans) {
			var self = this;

			this.get('g').getJSON('/subjects?', {q: subjcode})
				.done(function(res) {
					res.forEach(function(v) {
						v.subjcoded = encodeURIComponent(v.subjcode);
						console.log(v.subjcoded);
					});

					if(res.length === 1 && trans) {
						self.transitionToRoute(trans, res[0].subjcode, res[0].section);
					} else {
						self.set('subjlist', res);
					}
				});
		}
	},

	_getBcodes: function() {
		var self = this;
		this.get('g').getJSON('/bcodes')
			.done(function(res) {
				self.set('bcodes', res['bcodes']);
			});
	},

	_getFees: function() {
		var self = this;
		this.get('g').getJSON('/fees' )
			.done(function(res) {
				self.set('fees', res);
			});
	}
});