import Ember from 'ember';

export default Ember.Component.extend({

	_icheck: null,

	setupElement: function() {
		console.log('Setup icheck here');
	}.on('didInsertElement'),

	teardownElement: function() {
		this.get('_icheck').destroy();
	}.on('willDestroyElement')
});