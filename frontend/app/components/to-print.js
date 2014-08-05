import Em from 'ember';

export default Em.Component.extend({
	tagName: '',
	actions: {
		click: function(route, p) {
			console.log('rint');
			this.sendAction('toprint', route, p);
			//this.sendAction('toprint', route, p);
		}
	}
});