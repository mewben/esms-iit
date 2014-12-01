import Ember from 'ember';

import BaseControllerMixin from '../mixins/base-controller';

export default Ember.Controller.extend(
	BaseControllerMixin,
	{
		pageTitle: 'Fees | <small>Write a small description here.</small>'
	}
);