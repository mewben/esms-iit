/**
 * Used for adding/removing class in body
 * used by routes
 */
import Ember from 'ember';

export default Ember.Mixin.create({

	activate: function() {
		this._super();
		var classes = this.get('classNames');

		if (classes) {
			Ember.run.schedule('afterRender', null, function() {
				classes.forEach(function(cl) {
					Ember.$('body').addClass(cl);
				});
			});
		}
	},

	deactivate: function() {
		this._super();
		var classes = this.get('classNames');

		if (classes) {
			Ember.run.schedule('afterRender', null, function() {
				classes.forEach(function(cl) {
					Ember.$('body').removeClass(cl);
				});
			});
		}
	}
});