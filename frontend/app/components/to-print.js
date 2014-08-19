import Em from 'ember';

export default Em.Component.extend({
	tagName: '',
	actions: {
		click: function(route, p, p2) {
			this.sendAction('act', route, p, p2);
		}
	}
});