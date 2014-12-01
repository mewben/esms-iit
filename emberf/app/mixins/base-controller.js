/**
 * Base Controller
 */
import Ember from 'ember';

export default Ember.Mixin.create({

	needs 	: ['application'],
	g 		: Ember.computed.alias('controllers.application'),

	isLoading 	: false,
	isNotLoading: Ember.computed.not('isLoading')

});