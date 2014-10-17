import Em from 'ember';

export default Em.Handlebars.makeBoundHelper(function(value) {
	return value - 0 + 1;
});