import Ember from 'ember';

export default Ember.ObjectController.extend({
	numlabel: function() {
		this.get('misc').map(function(v, i) {
			console.log(i+1);
			return i+1;
		});
	}.property('misc.@each'),

	misclength: function() {
		console.log(this.get('misc').length);
		return this.get('misc').length;
	}.property('misc'),
});