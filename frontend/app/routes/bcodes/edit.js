import N from './new';

export default N.extend({
	model: function(params) {
		return this.store.find('bcode', params.id);
	}
});