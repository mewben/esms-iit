import Em from 'ember';

export default Em.Handlebars.makeBoundHelper(function(number, decimal) {
	return Number(number).toFixed(decimal);
});