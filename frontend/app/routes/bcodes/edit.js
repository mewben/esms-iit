import N from './new';
//import Em from 'ember';

export default N.extend({
	model: function(params) {
		return this.store.find('bcode', params.id);
	}
});