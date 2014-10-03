import Ember from 'ember';

export default Ember.TextField.extend({
	picker: null,
	classNames: ['form-control'],

	didInsertElement: function() {
		var picker = new Pikaday({
			field: this.$()[0],
			format: 'YYYY-MM-DD'
		});

		this.set('picker', picker);
	},

	willDestroyElement: function() {
		var picker = this.get('picker');
		if (picker) {
			picker.destroy();
		}
		this.set('picker', null);
	}
});
