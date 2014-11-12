import Ember from 'ember';

export default Ember.ObjectController.extend({
	params: function() {
		var p = this._params(true);
		return p;
	}.property('h.studid'),

	_params: function() {
		return {
			studid: this.get('h.studid'),
			//preview: preview,
		};
	}
});