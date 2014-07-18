import Em from 'ember';

export default Em.TextField.extend({
	classNames: ['form-control', 'text-left'],

	didInsertElement: function() {
		Em.run.scheduleOnce('afterRender', this, 'initt');
	},

	initt: function() {
		var options = this.get('content');

		this.$().selectize({
			maxItems: 1,
			valueField: 'feecode',
			labelField: 'feedesc',
			searchField: 'feedesc',
			options: options,
			create: false
		});
	}
});