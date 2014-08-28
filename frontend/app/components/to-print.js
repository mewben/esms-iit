import Em from 'ember';

export default Em.Component.extend({
	tagName: '',
	actions: {
		click: function(route, p) {
			this.sendAction('act', route, p);
		}
	}
});