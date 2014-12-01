import Ember from 'ember';

import BaseControllerMixin from '../mixins/base-controller';

export default Ember.Controller.extend(
	BaseControllerMixin,
	{
		pageTitle: 'B-Codes | <small>Write a small description here.</small>'
	}
);