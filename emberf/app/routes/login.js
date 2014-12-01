import Ember from 'ember';
import BodyClass from '../mixins/body-class';

export default Ember.Route.extend(
	BodyClass,
	{
		classNames: ['ent-login'],

		setupController: function(controller, model) {
			this._super(controller, model);

			// hide sidebar when login
			this.controller.set('g.hideNav', true);
		}
	}
);